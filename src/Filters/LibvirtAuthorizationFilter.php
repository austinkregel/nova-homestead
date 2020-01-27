<?php

namespace Kregel\Supervisor\Filters;

use Kregel\Supervisor\Models\Hypervisor;

class LibvirtAuthorizationFilter
{
    public function filter(Hypervisor $hypervisor): array
    {
        $authorization = [];

        if ($hypervisor->username) {
            $authorization['username'] = $hypervisor->username;
        }

        if ($hypervisor->password) {
            $authorization['password'] = $hypervisor->password;
        }

        return $authorization;
    }
}
