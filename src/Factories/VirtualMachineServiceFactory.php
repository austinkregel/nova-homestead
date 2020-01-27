<?php

namespace Kregel\Supervisor\Factories;

use Illuminate\Auth\Access\AuthorizationException;
use Kregel\Supervisor\Filters\LibvirtAuthorizationFilter;
use Kregel\Supervisor\Filters\LibvirtConnectionFilter;
use Kregel\Supervisor\Models\Hypervisor;
use Kregel\Supervisor\Services\Libvirt\VirtualMachineService;

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
