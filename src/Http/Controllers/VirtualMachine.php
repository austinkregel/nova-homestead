<?php

namespace Kregel\Supervisor\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kregel\Supervisor\Factories\VirtualMachineServiceFactory;
use Kregel\Supervisor\Models\Hypervisor;

class VirtualMachine extends Controller
{
    use ValidatesRequests;

    public function index(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor)
    {
        $service = $factory->factory($hypervisor);

        return $service->findAll();
    }

    public function enable(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor,  $uuid)
    {
        $service = $factory->factory($hypervisor);

        $service->enable($uuid);
        return response(null, 201);
    }

    public function pause(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor, $uuid)
    {
        $service = $factory->factory($hypervisor);

        $service->pause($uuid);
        return response(null, 201);
    }

    public function shutdown(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor, $uuid)
    {
        $service = $factory->factory($hypervisor);

        $service->shutdown($uuid);
        return response(null, 201);
    }

    public function forceStop(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor, $uuid)
    {
        $service = $factory->factory($hypervisor);

        $service->forceStop($uuid);
        return response(null, 201);
    }

    public function destroy(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor, $uuid)
    {
        $service = $factory->factory($hypervisor);

        $service->destroy($uuid);
        return response(null, 201);
    }

    public function reboot(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor, $uuid)
    {
        $service = $factory->factory($hypervisor);

        $service->reboot($uuid);
        return response(null, 201);
    }

    public function store(VirtualMachineServiceFactory $factory, Hypervisor $hypervisor, Request $request)
    {
        $service = $factory->factory($hypervisor);

        $this->validate($request, [
            'name' => 'required',
            'memory' => 'required|int|min:256',
            'vcpu' => 'required|min:1',
            'iso' => 'required',
            'disk' => 'required',
            'networks' => 'required',
        ]);

        if (!file_exists($request->get('iso'))) {
            return response([
                'message' => 'Your iso file does not exist, or it\'s not using absolute positioning...'
            ], 400);
        }

        return $service->create([
            'name' => $request->get('name'),
            'memory' => $request->get('memory'),
            'vcpu' => $request->get('vcpu'),
            'iso' => $request->get('iso'),
            'disk' => $request->get('disk'),
            'networks' => $request->get('networks'),
        ]);
    }
}
