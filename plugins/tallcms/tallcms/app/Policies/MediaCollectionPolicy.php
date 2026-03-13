<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TallCms\Cms\Models\MediaCollection;

class MediaCollectionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:MediaCollection');
    }

    public function view(User $authUser, MediaCollection $mediaCollection): bool
    {
        return $authUser->can('View:MediaCollection');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:MediaCollection');
    }

    public function update(User $authUser, MediaCollection $mediaCollection): bool
    {
        return $authUser->can('Update:MediaCollection');
    }

    public function delete(User $authUser, MediaCollection $mediaCollection): bool
    {
        return $authUser->can('Delete:MediaCollection');
    }

    public function restore(User $authUser, MediaCollection $mediaCollection): bool
    {
        return $authUser->can('Restore:MediaCollection');
    }

    public function forceDelete(User $authUser, MediaCollection $mediaCollection): bool
    {
        return $authUser->can('ForceDelete:MediaCollection');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MediaCollection');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:MediaCollection');
    }

    public function replicate(User $authUser, MediaCollection $mediaCollection): bool
    {
        return $authUser->can('Replicate:MediaCollection');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:MediaCollection');
    }
}
