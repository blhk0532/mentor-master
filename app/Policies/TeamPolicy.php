<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Team');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->can('View:Team');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Team');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->can('Update:Team');
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->can('Delete:Team');
    }

    public function restore(User $user, Team $team): bool
    {
        return $user->can('Restore:Team');
    }

    public function forceDelete(User $user, Team $team): bool
    {
        return $user->can('ForceDelete:Team');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Team');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Team');
    }

    public function replicate(User $user, Team $team): bool
    {
        return $user->can('Replicate:Team');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Team');
    }
}
