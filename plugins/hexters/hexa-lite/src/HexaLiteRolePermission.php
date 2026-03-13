<?php

declare(strict_types=1);

namespace Hexters\HexaLite;

trait HexaLiteRolePermission
{
    public function roles()
    {
        return $this->belongsToMany(config('hexa.models.role'), 'hexa_role_user', 'user_id', 'role_id')
            ->where('guard', hexa()->guard());
    }
}
