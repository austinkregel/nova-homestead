<?php

namespace Kregel\Supervisor\Models;

use Illuminate\Database\Eloquent\Model;
use Kregel\Supervisor\Filters\LibvirtConnectionFilter;

/**
 * Kregel\Supervisor\Models\Hypervisor
 *
 * @property int $id
 * @property string $name
 * @property string $hypervisor
 * @property string|null $transport_type
 * @property string|null $username
 * @property string|null $ip
 * @property int $port
 * @property int|null $path
 * @property string|null $ssh_command
 * @property string|null $socket
 * @property int|null $no_tty
 * @property string|null $no_verify
 * @property string|null $known_hosts
 * @property string|null $sshauth
 * @property string|null $private_key_absolute_path
 * @property string|null $password
 * @property string|null $host_os
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereHostOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereHypervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereKnownHosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereNoTty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereNoVerify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor wherePrivateKeyAbsolutePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereSocket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereSshCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereSshauth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereTransportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Kregel\Supervisor\Models\Hypervisor whereUsername($value)
 * @mixin \Eloquent
 */
class Hypervisor extends Model
{
    protected $table = 'supervisor_hypervisors';

    public const HYPERVISOR_LXC = 'lxc';
    public const HYPERVISOR_OPENVZ= 'vz';
    public const HYPERVISOR_QEMU = 'qemu';
    public const HYPERVISOR_TEST = 'test';
    public const HYPERVISOR_VIRTUALBOX = 'vbox';
    public const HYPERVISOR_VMWARE_ESX = 'ESX';
    public const HYPERVISOR_VMWARE_WORKSTATION_PLAYER = 'vmwarewplayer';
    public const HYPERVISOR_XEN = 'xen';
    public const HYPERVISOR_HYPER_V = 'hyper-v';
    public const HYPERVISOR_VIRTUOZZO = 'virtuozzo';
    public const HYPERVISOR_BHYVE = 'bhyve';

    public $fillable = [
        'name',
        'hypervisor',
        'transport_type',
        'path_to_isos',
        'ip',
        'port',
        'username',
        'password',
        'private_key_absolute_path',
        'libvirt_path',
        'libvirt_ssh_command',
        'libvirt_socket',
        'libvirt_no_tty',
        'libvirt_no_verify',
        'libvirt_known_hosts',
        'libvirt_sshauth',
        'host_os',
    ];

    public $hidden = [
        'private_key_absolute_path',
        'password',
    ];

    public $appends = ['connection', 'default_path'];

    public function getConnectionAttribute()
    {
        return (new LibvirtConnectionFilter)->filter($this);
    }

    public function getDefaultPathAttribute()
    {
        return in_array($this->hypervisor, [
            self::HYPERVISOR_QEMU,
            self::HYPERVISOR_BHYVE,
            self::HYPERVISOR_XEN,
            self::HYPERVISOR_VIRTUOZZO,
            self::HYPERVISOR_LXC,
            self::HYPERVISOR_BHYVE,
        ]) ? 'system' : '';
    }
}
