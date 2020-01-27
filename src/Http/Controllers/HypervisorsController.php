<?php

namespace Kregel\Supervisor\Http\Controllers;

use Kregel\Supervisor\Factories\VirtualMachineServiceFactory;
use Kregel\Supervisor\Models\Hypervisor;

class HypervisorsController
{
    public function __invoke()
    {
        return Hypervisor::all();
    }
}
