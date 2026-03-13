<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TallCms\Cms\Models\TallcmsMedia;

class TallcmsMediaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:TallcmsMedia');
    }

    public function view(User $authUser, TallcmsMedia $tallcmsMedia): bool
    {
        return $authUser->can('View:TallcmsMedia');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:TallcmsMedia');
    }

    public function update(User $authUser, TallcmsMedia $tallcmsMedia): bool
    {
        return $authUser->can('Update:TallcmsMedia');
    }

    public function delete(User $authUser, TallcmsMedia $tallcmsMedia): bool
    {
        return $authUser->can('Delete:TallcmsMedia');
    }

    public function restore(User $authUser, TallcmsMedia $tallcmsMedia): bool
    {
        return $authUser->can('Restore:TallcmsMedia');
    }

    public function forceDelete(User $authUser, TallcmsMedia $tallcmsMedia): bool
    {
        return $authUser->can('ForceDelete:TallcmsMedia');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TallcmsMedia');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:TallcmsMedia');
    }

    public function replicate(User $authUser, TallcmsMedia $tallcmsMedia): bool
    {
        return $authUser->can('Replicate:TallcmsMedia');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:TallcmsMedia');
    }
}
