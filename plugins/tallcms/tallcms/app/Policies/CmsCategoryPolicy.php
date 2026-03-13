<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TallCms\Cms\Models\CmsCategory;

class CmsCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:CmsCategory');
    }

    public function view(User $authUser, CmsCategory $cmsCategory): bool
    {
        return $authUser->can('View:CmsCategory');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:CmsCategory');
    }

    public function update(User $authUser, CmsCategory $cmsCategory): bool
    {
        return $authUser->can('Update:CmsCategory');
    }

    public function delete(User $authUser, CmsCategory $cmsCategory): bool
    {
        return $authUser->can('Delete:CmsCategory');
    }

    public function restore(User $authUser, CmsCategory $cmsCategory): bool
    {
        return $authUser->can('Restore:CmsCategory');
    }

    public function forceDelete(User $authUser, CmsCategory $cmsCategory): bool
    {
        return $authUser->can('ForceDelete:CmsCategory');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CmsCategory');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:CmsCategory');
    }

    public function replicate(User $authUser, CmsCategory $cmsCategory): bool
    {
        return $authUser->can('Replicate:CmsCategory');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:CmsCategory');
    }
}
