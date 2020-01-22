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
}
