<?php

namespace Kregel\Homestead\Commands;

use Illuminate\Console\Command;
use Kregel\Homestead\Models\Hypervisor;
use Kregel\Homestead\Services\Libvirt\VirtualMachineService;

class SyncDatabaseWithHost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:database-with-host';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(VirtualMachineService $service)
    {
        Hypervisor::all()->map(function (Hypervisor $hypervisor) use ($service) {
            $this->sync($hypervisor, $service);
        });
    }

    public function sync(Hypervisor $hypervisor, VirtualMachineService $service)
    {
        $vms = $service->findAll();

        foreach ($vms as $vm) {
            dd($vm);
        }
    }
}
