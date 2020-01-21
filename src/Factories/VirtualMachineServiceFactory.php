<?php

namespace Kregel\Homestead\Factories;

use Illuminate\Auth\Access\AuthorizationException;
use Kregel\Homestead\Filters\LibvirtAuthorizationFilter;
use Kregel\Homestead\Filters\LibvirtConnectionFilter;
use Kregel\Homestead\Models\Hypervisor;
use Kregel\Homestead\Services\Libvirt\VirtualMachineService;

class VirtualMachineServiceFactory
{
    /**
     * @var LibvirtConnectionFilter
     */
    protected $connectionFilter;

    /**
     * @var LibvirtAuthorizationFilter
     */
    protected $authorizationFilter;

    public function __construct(LibvirtConnectionFilter $connectionFilter, LibvirtAuthorizationFilter $authorizationFilter)
    {
        $this->connectionFilter = $connectionFilter;
        $this->authorizationFilter = $authorizationFilter;
    }

    public function factory(Hypervisor $hypervisor): VirtualMachineService
    {
        try {
            return new VirtualMachineService($this->connectionFilter->filter($hypervisor), $this->authorizationFilter->filter($hypervisor));
        } catch (AuthorizationException $e) {
            dd($e);
        }
    }
}
