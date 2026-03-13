<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:Role');
    }

    public function view(User $authUser, Role $role): bool
    {
        return $authUser->can('View:Role');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:Role');
    }

    public function update(User $authUser, Role $role): bool
    {
        return $authUser->can('Update:Role');
    }

    public function delete(User $authUser, Role $role): bool
    {
        return $authUser->can('Delete:Role');
    }

    public function restore(User $authUser, Role $role): bool
    {
        return $authUser->can('Restore:Role');
    }

    public function forceDelete(User $authUser, Role $role): bool
    {
        return $authUser->can('ForceDelete:Role');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Role');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:Role');
    }

    public function replicate(User $authUser, Role $role): bool
    {
        return $authUser->can('Replicate:Role');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:Role');
    }
}
