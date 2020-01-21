<?php

namespace Kregel\Homestead\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kregel\Homestead\Services\Libvirt\VirtualMachineService;

class VirtualMachine extends Controller
{
    use ValidatesRequests;

    public function enable(VirtualMachineService $service, $uuid)
    {
        $service->enable($uuid);
        return response(null, 201);
    }

    public function pause(VirtualMachineService $service, $uuid)
    {
        $service->pause($uuid);
        return response(null, 201);
    }

    public function shutdown(VirtualMachineService $service, $uuid)
    {
        $service->shutdown($uuid);
        return response(null, 201);
    }

    public function forceStop(VirtualMachineService $service, $uuid)
    {
        $service->forceStop($uuid);
        return response(null, 201);
    }

    public function destroy(VirtualMachineService $service, $uuid)
    {
        $service->destroy($uuid);
        return response(null, 201);
    }

    public function reboot(VirtualMachineService $service, $uuid)
    {
        $service->reboot($uuid);
        return response(null, 201);
    }

    public function store(VirtualMachineService $service, Request $request)
    {
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
