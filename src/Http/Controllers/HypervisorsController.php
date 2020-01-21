<?php

namespace Kregel\Homestead\Http\Controllers;

use Kregel\Homestead\Factories\VirtualMachineServiceFactory;
use Kregel\Homestead\Models\Hypervisor;

class HypervisorsController
{
    public function __invoke()
    {
        return Hypervisor::all();
    }

    public function machines(Hypervisor $hypervisor, VirtualMachineServiceFactory $factory)
    {
        $service = $factory->factory($hypervisor);

        return $service->findAll();
    }
}
