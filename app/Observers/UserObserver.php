<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Get the currently logged-in user
        $currentUser = Auth::user();
        $currentTeamId = $currentUser?->current_team_id;

        if ($currentUser) {
            // Find the current user's non-personal team
            $nonPersonalTeam = $currentUser->ownedTeams()
                ->where('personal_team', false)
                ->first();

            // If a non-personal team exists, add the new user to it
            if ($nonPersonalTeam) {
                $nonPersonalTeam->users()->attach($user);

                // Set the new user's current team to this team
                $user->forceFill(['current_team_id' => $nonPersonalTeam->id])->save();
            }
        }

        if ($currentTeamId === null) {
            $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'slug' => $user->ulid,
                'personal_team' => true,
            ]));
        }

        try {
            Cache::delete('users_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        try {
            Cache::delete('users_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
