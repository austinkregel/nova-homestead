<?php

namespace Kregel\Homestead\Filters;

use Kregel\Homestead\Models\Hypervisor;

class LibvirtConnectionFilter
{
    /**
     * @var Hypervisor
     */
    private $hypervisor;

    public function filter(Hypervisor $hypervisor): string
    {
        $this->hypervisor = $hypervisor;
        return $this->formatHypervisorConnection();
    }

    protected function formatHypervisorConnection()
    {
        return trim(sprintf(
            '%s%s://%s%s%s/%s%s',
            $this->hypervisor->hypervisor,
            $this->nullableWithDefault('transport_type', '+'.$this->hypervisor->transport_type),
            $this->nullableWithDefault('username', $this->hypervisor->username.'@'),
            $this->nullableWithDefault('ip', $this->hypervisor->ip),
            $this->nullableWithDefault('port', ':'.$this->hypervisor->port),
            $this->nullableWithDefault('path', $this->hypervisor->path, $this->hypervisor->default_path),
            $this->buildExtraParameters()
        ));
    }

    protected function nullableWithDefault($field, $modify, $default = '')
    {
        return empty($this->hypervisor->$field) ? $default : $modify;
    }

    protected function buildExtraParameters(): string
    {
        $parameters = [];
        if (isset($this->hypervisor->no_tty)) {
            $parameters['no_tty'] = $this->hypervisor->no_tty;
        }
        if (isset($this->hypervisor->no_verify)) {
            $parameters['no_verify'] = $this->hypervisor->no_verify;
        }
        if (isset($this->hypervisor->sshauth)) {
            $parameters['sshauth'] = $this->hypervisor->sshauth;
        }
        if (isset($this->hypervisor->known_hosts)) {
            $parameters['known_hosts'] = $this->hypervisor->known_hosts;
        }
        if (isset($this->hypervisor->private_key_absolute_path)) {
            $parameters['keyfile'] = $this->hypervisor->private_key_absolute_path;
        }
        if (isset($this->hypervisor->ssh_command)) {
            $parameters['command'] = $this->hypervisor->ssh_command;
        }
        return '?'.urldecode(http_build_query($parameters));
    }
}
