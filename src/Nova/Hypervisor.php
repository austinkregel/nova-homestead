<?php

namespace Kregel\Supervisor\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Kregel\Supervisor\Models\Hypervisor as ModelHypervisor;
use Kregel\Supervisor\Nova\Cards\Help;

class Hypervisor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ModelHypervisor::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('name')->rules([
                'required'
            ]),
            Text::make('host_os')->nullable(),
            Text::make('path_to_isos')->rules([
                'required'
            ]),
            Select::make('hypervisor')->options([
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_LXC),
                    'label' => ModelHypervisor::HYPERVISOR_LXC,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_OPENVZ),
                    'label' => ModelHypervisor::HYPERVISOR_OPENVZ,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_QEMU),
                    'label' => ModelHypervisor::HYPERVISOR_QEMU,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_TEST),
                    'label' => ModelHypervisor::HYPERVISOR_TEST,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_VIRTUALBOX),
                    'label' => ModelHypervisor::HYPERVISOR_VIRTUALBOX,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_VMWARE_ESX),
                    'label' => ModelHypervisor::HYPERVISOR_VMWARE_ESX,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_VMWARE_WORKSTATION_PLAYER),
                    'label' => ModelHypervisor::HYPERVISOR_VMWARE_WORKSTATION_PLAYER,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_XEN),
                    'label' => ModelHypervisor::HYPERVISOR_XEN,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_HYPER_V),
                    'label' => ModelHypervisor::HYPERVISOR_HYPER_V,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_VIRTUOZZO),
                    'label' => ModelHypervisor::HYPERVISOR_VIRTUOZZO,
                ],
                [
                    'value' => Str::lower(ModelHypervisor::HYPERVISOR_BHYVE),
                    'label' => ModelHypervisor::HYPERVISOR_BHYVE,
                ],
            ])->rules([
                'required'
            ]),
            Select::make('transport_type')->options([
                [
                    'value' => '',
                    'label' => 'Default',
                ],
                [
                    'value' => 'ssh',
                    'label' => 'SSH',
                ],
                [
                    'value' => 'unix',
                    'label' => 'Unix',
                ],
                [
                    'value' => 'libssh',
                    'label' => 'LibSSH',
                ],
                [
                    'value' => 'libssh2',
                    'label' => 'LibSSH 2',
                ],
                [
                    'value' => 'tcp',
                    'label' => 'TCP',
                ],
            ])->nullable(),
            Text::make('ip')->nullable(),
            Text::make('port')->nullable()->hideFromIndex(),
            Text::make('username')->hideFromIndex(),
            Text::make('password')->nullable()->hideFromIndex(),
            Text::make('private_key_absolute_path')->nullable()->hideFromIndex(),
            Text::make('libvirt_path')->nullable()->hideFromIndex(),
            Text::make('libvirt_ssh_command')->nullable(),
            Text::make('libvirt_socket')->nullable()->hideFromIndex(),
            Boolean::make('libvirt_no_tty')->nullable()->hideFromIndex(),
            Boolean::make('libvirt_no_verify')->nullable()->hideFromIndex(),
            Text::make('libvirt_known_hosts')->nullable()->hideFromIndex(),
            Text::make('libvirt_sshauth')->nullable()->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
