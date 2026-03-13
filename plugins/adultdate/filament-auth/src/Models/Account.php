<?php

declare(strict_types=1);

namespace Adultdate\FilamentAuth\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class UserAccount extends Authenticatable implements FilamentUser, HasAvatar, HasDefaultTenant, HasName, HasTenants, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'type',
        'is_active',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'avatar_url',
    ];

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->allCompanies();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->belongsToCompany($tenant);
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->personalCompany();
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Determine if the user can create new groups.
     */
    public function canCreateGroups(): bool
    {
        // By default, allow all authenticated users to create groups
        // You can customize this logic based on your requirements
        return true;
    }

    /**
     * Determine if the user can create new chats with other users.
     */
    public function canCreateChats(): bool
    {
        // By default, allow all authenticated users to create chats
        // You can customize this logic based on your requirements
        return true;
    }

    /**
     * Determine if the user can access wirechat panel.
     * Accepts both Filament Panel (for Filament routes) and Wirechat Panel (for standalone routes).
     */
    public function canAccessWirechatPanel($panel): bool
    {
        // By default, allow all authenticated users to access the panel
        // You can customize this logic based on your requirements
        return true;
    }

    /**
     * Override belongsToConversation to accept both Filament and standalone Conversation types.
     * This method works with both Filament wirechat routes and standalone wirechat routes.
     */
    public function belongsToConversation(\AdultDate\FilamentWirechat\Models\Conversation $conversation, bool $withoutGlobalScopes = false): bool
    {
        // Check if participants are already loaded
        if ($conversation->relationLoaded('participants')) {
            // If loaded, simply check the existing collection
            $participants = $conversation->participants;

            if ($withoutGlobalScopes) {
                $participants->withoutGlobalScopes();
            }

            return $participants->contains(function ($participant) {
                return $participant->participantable_id === $this->getKey() &&
                    $participant->participantable_type === $this->getMorphClass();
            });
        }

        $participants = $conversation->participants();

        if ($withoutGlobalScopes) {
            $participants->withoutGlobalScopes();
        }

        // Perform the query to check if user is a participant
        return $participants->where('participantable_id', $this->getKey())
            ->where('participantable_type', $this->getMorphClass())
            ->exists();
    }

    /**
     * Determine if the user belongs to the given team.
     */
    public function belongsToTeam(Team $team): bool
    {
        return $this->teams->contains($team);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (blank($this->avatar_url)) {
            return null;
        }

        if (Str::startsWith($this->avatar_url, ['http://', 'https://', '//'])) {
            return $this->avatar_url;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->avatar_url);
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
