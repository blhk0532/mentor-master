<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TallCms\Cms\Models\TallcmsContactSubmission;

class TallcmsContactSubmissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:TallcmsContactSubmission');
    }

    public function view(User $authUser, TallcmsContactSubmission $tallcmsContactSubmission): bool
    {
        return $authUser->can('View:TallcmsContactSubmission');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:TallcmsContactSubmission');
    }

    public function update(User $authUser, TallcmsContactSubmission $tallcmsContactSubmission): bool
    {
        return $authUser->can('Update:TallcmsContactSubmission');
    }

    public function delete(User $authUser, TallcmsContactSubmission $tallcmsContactSubmission): bool
    {
        return $authUser->can('Delete:TallcmsContactSubmission');
    }

    public function restore(User $authUser, TallcmsContactSubmission $tallcmsContactSubmission): bool
    {
        return $authUser->can('Restore:TallcmsContactSubmission');
    }

    public function forceDelete(User $authUser, TallcmsContactSubmission $tallcmsContactSubmission): bool
    {
        return $authUser->can('ForceDelete:TallcmsContactSubmission');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TallcmsContactSubmission');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:TallcmsContactSubmission');
    }

    public function replicate(User $authUser, TallcmsContactSubmission $tallcmsContactSubmission): bool
    {
        return $authUser->can('Replicate:TallcmsContactSubmission');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:TallcmsContactSubmission');
    }
}
