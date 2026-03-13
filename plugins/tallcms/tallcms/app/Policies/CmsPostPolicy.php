<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TallCms\Cms\Models\CmsPost;

class CmsPostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:CmsPost');
    }

    public function view(User $authUser, CmsPost $cmsPost): bool
    {
        return $authUser->can('View:CmsPost');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:CmsPost');
    }

    public function update(User $authUser, CmsPost $cmsPost): bool
    {
        return $authUser->can('Update:CmsPost');
    }

    public function delete(User $authUser, CmsPost $cmsPost): bool
    {
        return $authUser->can('Delete:CmsPost');
    }

    public function restore(User $authUser, CmsPost $cmsPost): bool
    {
        return $authUser->can('Restore:CmsPost');
    }

    public function forceDelete(User $authUser, CmsPost $cmsPost): bool
    {
        return $authUser->can('ForceDelete:CmsPost');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CmsPost');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:CmsPost');
    }

    public function replicate(User $authUser, CmsPost $cmsPost): bool
    {
        return $authUser->can('Replicate:CmsPost');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:CmsPost');
    }
}
