<?php

namespace Kregel\Supervisor\Services\Libvirt;

use Illuminate\Support\Str;
use Kregel\Supervisor\Exceptions\LibvirtException;
use Orchestra\Parser\Xml\Facade as XmlParser;

class VirtualMachineService
{
    public $connection;

    public function __construct(string $hostUrl = 'qemu:///system', array $authorization = [])
    {
        try {
            $this->connection = libvirt_connect($hostUrl, false);
        } catch (\ErrorException $exception) {
            if ($exception->getMessage() === 'libvirt_connect(): Cannot read CA certificate \'/etc/pki/CA/cacert.pem\': No such file or directory') {
                LibvirtException::throw('You must add a private key path to connect to this server.', $exception);
            }

            LibvirtException::throw("Failed to connect at $hostUrl", $exception);
        }
    }

    protected function guidv4($data)
    {
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return bin2hex($data);
    }

    public function findAll()
    {
        return array_map(function ($domainName) {
            return $this->convertDomainResource(libvirt_domain_lookup_by_name($this->connection, $domainName));
        }, libvirt_list_domains($this->connection));
    }

    public function findAllNetworks()
    {
        return array_map(function ($name) {
            return libvirt_network_get_information(libvirt_network_get($this->connection, $name));
        }, libvirt_list_networks($this->connection));
    }

    protected function convertDomainResource($domainResource)
    {
        $xml = str_replace(
            'libosinfo:libosinfo',
            'libosinfo',
            str_replace(
                'libosinfo:os',
                'os',
                libvirt_domain_get_xml_desc($domainResource, 0)
            )
        );
        $parsedXml = XmlParser::via(simplexml_load_string($xml))->parse([
            'id' => ['uses' => '::id'],
            'uuid' => ['uses' => 'uuid'],
            'name' => ['uses' => 'name'],
            'os' => ['uses' => 'metadata.libosinfo.os::id'],
            'memory' => ['uses' => 'memory'],
            'currentMemory' => ['uses' => 'currentMemory'],
            'vcpu' => ['uses' => 'vcpu'],
            'cpu_attributes' => ['uses' => 'cpu[model,vendor,feature(@=::name)]'],
            'on_poweroff' => ['uses' => 'on_poweroff'],
            'on_reboot' => ['uses' => 'on_reboot'],
            'on_crash' => ['uses' => 'on_crash'],
            'devices' => ['uses' => 'devices.disk[::type>type,::device>device,driver(@=::type),source::file>source]'],
            'mac' => ['uses' => 'devices.interface.mac::address'],
            'graphics' => ['uses' => 'devices.graphics[::port>port,::listen>address,::type>service]'],
        ]);

        $parsedXml['is_enabled'] = (bool) libvirt_domain_is_active($domainResource);

        foreach ($parsedXml['devices'] as $key => $value) {
            if ($value['device'] !== 'disk') {
                continue;
            }

            if (empty($value['source'])) {
                continue;
            }

            $filesize = filesize($value['source']);

            $value['disk_size'] = $filesize;

            $parsedXml['devices'][$key] = $value;
        }

        foreach (libvirt_list_all_networks($this->connection) as $networkResource) {
            $networkInfo = libvirt_network_get_information($networkResource);
            if (!array_key_exists('networks', $parsedXml) || !is_array($parsedXml['networks'])) {
                $parsedXml['networks'] = [];
            }
            $parsedXml['networks'][] = $networkInfo;
        }

        return $parsedXml;
    }

    public function enable(string $uuid)
    {
        $domainResource = libvirt_domain_lookup_by_uuid_string($this->connection, $uuid);

        $domain = $this->convertDomainResource($domainResource);

        if ($domain['is_enabled']) {
            return $this->startVirtualMachineFromSuspension($domainResource);
        } else {
            return $this->startVirtualMachineFromOffState($domainResource);
        }
    }

    public function startVirtualMachineFromSuspension($domainResource)
    {
        $didSuspend = libvirt_domain_resume($domainResource);

        if (!$didSuspend) {
            LibvirtException::throw('Failed to resume the virtual machine.');
        }
    }

    public function startVirtualMachineFromOffState($domainResource)
    {
        $startState = libvirt_domain_create($domainResource);

        if (!$startState) {
            LibvirtException::throw('Failed to start the virtual machine.');
        }
    }

    public function forceStop(string $uuid)
    {
        $domainResource = libvirt_domain_lookup_by_uuid_string($this->connection, $uuid);
        $isDestroyed = libvirt_domain_destroy($domainResource);

        if (!$isDestroyed) {
            LibvirtException::throw('Failed to stop virtual machine forcefully.');
        }
    }

    public function pause(string $uuid)
    {
        $domainResource = libvirt_domain_lookup_by_uuid_string($this->connection, $uuid);
        $didSuspend = libvirt_domain_suspend($domainResource);

        if (!$didSuspend) {
            LibvirtException::throw('Failed to suspend virtual machine.');
        }
    }

    public function shutdown(string $uuid)
    {
        $domainResource = libvirt_domain_lookup_by_uuid_string($this->connection, $uuid);

        $didSuspend = libvirt_domain_shutdown($domainResource);

        if (!$didSuspend) {
            LibvirtException::throw('Failed to shutdown virtual machine.');
        }
    }

    public function destroy(string $uuid)
    {
        $domainResource = libvirt_domain_lookup_by_uuid_string($this->connection, $uuid);

        $didDestroy = libvirt_domain_undefine($domainResource);

        if (!$didDestroy) {
            LibvirtException::throw('Failed to destroy virtual machine.');
        }
    }

    public function reboot(string $uuid)
    {
        $domainResource = libvirt_domain_lookup_by_uuid_string($this->connection, $uuid);

        $didReboot = libvirt_domain_reboot($domainResource);

        if (!$didReboot) {
            LibvirtException::throw('Failed to reboot virtual machine.');
        }
    }

    public function create(array $options): array
    {
        libvirt_image_create(
            $this->connection,
            $diskName = Str::snake($options['name']) . '.' . trim($options['disk']['driver'], '2'),
            $options['disk']['size'],
            $options['disk']['driver']
        );

        $options['disk']['name'] = $diskName;
        $options['disk']['path'] = storage_path($diskName);

        $domain = libvirt_domain_new(
            $this->connection,
            $options['name'],
            false,
            $options['memory'],
            $options['memory'],
            $options['vcpu'],
            $options['iso'], // $image
            [$options['disk']], // $disks
            $options['networks'],
            VIR_DOMAIN_FLAG_FEATURE_ACPI | VIR_DOMAIN_FLAG_FEATURE_APIC | VIR_DOMAIN_FLAG_FEATURE_PAE
        );
        return $this->convertDomainResource($domain);
    }
}
