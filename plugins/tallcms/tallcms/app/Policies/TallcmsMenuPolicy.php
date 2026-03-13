<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TallCms\Cms\Models\TallcmsMenu;

class TallcmsMenuPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:TallcmsMenu');
    }

    public function view(User $authUser, TallcmsMenu $tallcmsMenu): bool
    {
        return $authUser->can('View:TallcmsMenu');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:TallcmsMenu');
    }

    public function update(User $authUser, TallcmsMenu $tallcmsMenu): bool
    {
        return $authUser->can('Update:TallcmsMenu');
    }

    public function delete(User $authUser, TallcmsMenu $tallcmsMenu): bool
    {
        return $authUser->can('Delete:TallcmsMenu');
    }

    public function restore(User $authUser, TallcmsMenu $tallcmsMenu): bool
    {
        return $authUser->can('Restore:TallcmsMenu');
    }

    public function forceDelete(User $authUser, TallcmsMenu $tallcmsMenu): bool
    {
        return $authUser->can('ForceDelete:TallcmsMenu');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TallcmsMenu');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:TallcmsMenu');
    }

    public function replicate(User $authUser, TallcmsMenu $tallcmsMenu): bool
    {
        return $authUser->can('Replicate:TallcmsMenu');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:TallcmsMenu');
    }
}
