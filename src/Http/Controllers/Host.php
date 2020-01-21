<?php

namespace Kregel\Homestead\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kregel\Homestead\Factories\VirtualMachineServiceFactory;
use Kregel\Homestead\Models\Hypervisor;
use Kregel\Homestead\Services\Libvirt\VirtualMachineService;

class Host
{
    use ValidatesRequests;

    public function show($path)
    {
        $path = base64_decode($path);
        $directory = libvirt_get_iso_images($path);

        if (!$directory) {
            $directory = array_filter(scandir($path), function ($path) {
                return Str::endsWith($path, ['.img', '.iso']);
            });
        }
        $itemsInDir = array_map(function ($path) {
            return [
                'path' => $path,
                'name' => basename($path),
            ];
        }, array_values(array_filter($directory, function ($path) {
            return !in_array($path, ['.', '..']);
        })));

        return $itemsInDir;
    }

    public function network(VirtualMachineServiceFactory $factory, Request $request)
    {
        $this->validate($request, [
            'hypervisor_id' => 'required'
        ]);

        $hypervisor = Hypervisor::findOrFail($request->get('hypervisor_id'));

        /** @var VirtualMachineService $service */
        $service = $factory->factory($hypervisor);

        return $service->findAllNetworks();
    }

    public function path(Request $request)
    {
        return config('homestead.iso_path');
    }
}
