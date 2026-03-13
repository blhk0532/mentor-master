<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TallCms\Cms\Models\CmsPage;

class CmsPagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:CmsPage');
    }

    public function view(User $authUser, CmsPage $cmsPage): bool
    {
        return $authUser->can('View:CmsPage');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:CmsPage');
    }

    public function update(User $authUser, CmsPage $cmsPage): bool
    {
        return $authUser->can('Update:CmsPage');
    }

    public function delete(User $authUser, CmsPage $cmsPage): bool
    {
        return $authUser->can('Delete:CmsPage');
    }

    public function restore(User $authUser, CmsPage $cmsPage): bool
    {
        return $authUser->can('Restore:CmsPage');
    }

    public function forceDelete(User $authUser, CmsPage $cmsPage): bool
    {
        return $authUser->can('ForceDelete:CmsPage');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CmsPage');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:CmsPage');
    }

    public function replicate(User $authUser, CmsPage $cmsPage): bool
    {
        return $authUser->can('Replicate:CmsPage');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:CmsPage');
    }
}
