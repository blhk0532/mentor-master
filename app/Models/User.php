<?php

declare(strict_types=1);

namespace App\Models;

use AdultDate\FilamentWirechat\Models\Conversation;
use Adultdate\Wirechat\Contracts\WirechatUser;
use Adultdate\Wirechat\Panel as WirechatStandalonePanel;
use Adultdate\Wirechat\Traits\InteractsWithWirechat;
use Andreia\FilamentUiSwitcher\Models\Traits\HasUiPreferences;
use App\Enums\UserActiveStatus;
use App\Observers\UserObserver;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use Kirschbaum\Commentions\Contracts\Commentable;
use Kirschbaum\Commentions\Contracts\Commenter;
use Kirschbaum\Commentions\HasComments;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Leek\FilamentDiceBear\Concerns\HasDiceBearAvatar;
use Leek\FilamentDiceBear\Enums\DiceBearStyle;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Wallo\FilamentCompanies\HasCompanies;
use Zap\Models\Concerns\HasSchedules;

/**
 * @property int $id
 * @property int|null $author_id
 * @property int|null $assigned_to_id
 * @property string $ulid
 * @property bool $status
 * @property string $role
 * @property int|null $type_id
 * @property string|null $name
 * @property string|null $name_first
 * @property string|null $name_last
 * @property string|null $address
 * @property string|null $email
 * @property string|null $email_private
 * @property string|null $phone
 * @property string|null $team
 * @property string|null $phone_private
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int $is_active
 * @property string|null $avatar_url
 * @property array<array-key, mixed>|null $custom_fields
 * @property string|null $locale
 * @property string|null $theme_color
 * @property UserActiveStatus $active_status
 * @property \Carbon\CarbonImmutable|null $active_at
 * @property int|null $current_team_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property array<array-key, mixed>|null $ui_preferences
 * @property string|null $notes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Zap\Models\Schedule> $activeSchedules
 * @property-read int|null $active_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Zap\Models\Schedule> $appointmentSchedules
 * @property-read int|null $appointment_schedules_count
 * @property-read User|null $assignedTo
 * @property-read User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Zap\Models\Schedule> $availabilitySchedules
 * @property-read int|null $availability_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Zap\Models\Schedule> $blockedSchedules
 * @property-read int|null $blocked_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AdultDate\FilamentWirechat\Models\Conversation> $conversations
 * @property-read int|null $conversations_count
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read string|null $cover_url
 * @property-read string|null $display_name
 * @property-read string|null $profile_url
 * @property-read string|null $wirechat_avatar_url
 * @property-read string|null $wirechat_name
 * @property-read string|null $wirechat_profile_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $oauthApps
 * @property-read int|null $oauth_apps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \AdultDate\FilamentDialer\Models\PhoneQueue> $phone_queues
 * @property-read int|null $phone_queues_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Zap\Models\Schedule> $recurringSchedules
 * @property-read int|null $recurring_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RingaData> $ringaData
 * @property-read int|null $ringa_data_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Zap\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereActiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereActiveStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAssignedToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNameFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNameLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhonePrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereThemeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUiPreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(UserObserver::class)]
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, Commentable, Commenter, FilamentUser, HasAvatar, HasDefaultTenant, HasTenants, MustVerifyEmailContract, WirechatUser
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasApiTokens;
    use HasComments;
    use HasCompanies;
    use HasDiceBearAvatar;
    use HasFactory;
    use HasRoles;
    use HasSchedules;
    use HasUiPreferences;
    use InteractsWithWirechat;
    use LogsActivity;
    use MustVerifyEmail;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'status',
        'ulid',
        'name',
        'email',
        'password',
        'avatar_url',
        'custom_fields',
        'locale',
        'theme_color',
        'current_team_id',
        'role',
        'active_status',
        'active_at',
        'custom_fields',
        'phone',
        'status',
        'active_status',
        'active_at',
        'notes',
        'team_id',
        'tenant_id',
        'current_team_id',
        'avatar_url',
        'name_first',
        'name_last',
        'email_verified_at',
        'current_company_id',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * @throws Exception
     */
    public function canAccessPanel(Panel $panel): bool
    {
        //    $currentUser = Auth::user();
        //    if ($panel->getId() === 'admin' && $currentUser->role !== 'admin' && $currentUser->role !== 'super') {
        //        return false;
        //    }

        return true;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    public function canImpersonate(): bool
    {
        $role = $this->role ?? null;
        if (in_array($role, ['super', 'super_admin', 'superadmin'], true)) {
            return true;
        }

        return false;
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function currentTeam(): BelongsTo
    {
        if (is_null($this->current_team_id) && $this->id) {
            $this->switchTeam($this->personalTeam());
        }

        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function switchTeam($team): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $this->forceFill([
            'current_team_id' => $team->id,
        ])->save();

        $this->setRelation('currentTeam', $team);

        return true;
    }

    public function belongsToTeam($team): bool
    {
        return $this->ownsTeam($team) || $this->teams->contains(fn ($t) => $t->id === $team->id);
    }

    public function ownsTeam($team): bool
    {
        if (is_null($team)) {
            return false;
        }

        return $this->id === $team->user_id;
    }

    public function personalTeam(): ?Team
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->belongsToTeam($tenant);
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->ownedTeams->merge($this->teams)->sortBy('name');
    }

    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'membership')
            ->using(Membership::class)
            ->withTimestamps()
            ->as('membership');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(self::class, 'author_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(self::class, 'assigned_to_id');
    }

    public function phone_queues(): HasMany
    {
        return $this->hasMany(\AdultDate\FilamentDialer\Models\PhoneQueue::class);
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        $currentTeam = $this->currentTeam;

        if ($currentTeam) {
            return $currentTeam;
        }

        return $this->getTenants($panel)->first();
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

    public function getUnreadCount(?Conversation $conversation = null): int
    {
        if ($conversation) {
            return method_exists($conversation, 'getUnreadCountFor')
                ? $conversation->getUnreadCountFor($this)
                : 0;
        }

        $totalUnread = 0;

        foreach ($this->conversations as $userConversation) {
            if (method_exists($userConversation, 'getUnreadCountFor')) {
                $totalUnread += $userConversation->getUnreadCountFor($this);
            }
        }

        return $totalUnread;
    }

    /**
     * Return the user's role.
     */
    public function hasRole(): ?string
    {
        return $this->role ?? null;
    }

    public function isAdmin(): bool
    {
        $role = $this->role ?? null;

        return in_array($role, ['admin', 'super', 'super_admin', 'superadmin'], true);
    }

    /**
     * Determine if the user can access wirechat panel.
     * Accepts both Filament Panel (for Filament routes) and Wirechat Panel (for standalone routes).
     */
    public function canAccessWirechatPanel(Panel|WirechatStandalonePanel $panel): bool
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

    public function oauthApps(): MorphMany  // Change return type from HasMany to MorphMany
    {
        return $this->morphMany(\Laravel\Passport\Client::class, 'owner');  // Change from hasMany to morphMany with 'owner' as the morph name
    }

    public function isOnline(): bool
    {
        return $this->active_at && $this->active_at->gt(now()->subMinutes(5));
    }

    public function canBeImpersonated(): bool
    {
        return auth()->user()->role === 'super';
    }

    public function getNdsUserName(): string
    {
        return auth()->user()->name;
    }

    public function ringaData(): HasMany
    {
        return $this->hasMany(RingaData::class, 'user_id', 'id');
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            if (empty($model->ulid)) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'custom_fields' => 'array',
            'ui_preferences' => 'array',
            'active_status' => UserActiveStatus::class,
            'active_at' => 'datetime',
        ];
    }

    protected function canManageTeam(): bool
    {
        return auth()->user()->role === 'super' || auth()->user()->role === 'admin' || auth()->user()->role === 'manager';
    }

    protected function canRegisterTeam(): bool
    {
        return auth()->user()->role === 'super' || auth()->user()->role === 'admin' || auth()->user()->role === 'manager';
    }

    protected function getCustomAvatarUrl(): ?string
    {
        if ($this->avatar_url) {
            return Storage::url($this->avatar_url);
        }

        return null; // Falls back to DiceBear
    }

    public function dicebearAvatarStyle(): DiceBearStyle
    {
        return DiceBearStyle::Thumbs;
    }
}
