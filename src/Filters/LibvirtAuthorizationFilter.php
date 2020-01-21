<?php

namespace Kregel\Homestead\Filters;

use Kregel\Homestead\Models\Hypervisor;

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
