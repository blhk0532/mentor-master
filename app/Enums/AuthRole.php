<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AuthRole: string implements HasColor, HasLabel
{
    case ADMIN = 'admin';
    case SUPER = 'super';
    case MANAGER = 'manager';
    case SERVICE = 'service';
    case BOOKING = 'booking';
    case PARTNER = 'partner';
    case GUEST = 'guest';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            AuthRole::ADMIN => 'Administrator',
            AuthRole::SUPER => 'Super Admin',
            AuthRole::MANAGER => 'Manager',
            AuthRole::SERVICE => 'Service',
            AuthRole::BOOKING => 'Booking',
            AuthRole::PARTNER => 'Partner',
            AuthRole::GUEST => 'Guest',
            AuthRole::USER => 'User',
        };
    }

    public function getLabel(): string
    {
        return __('messages.auth_role.'.$this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ADMIN => ['display' => '#3b82f6', 'dropdown' => 'primary'],
            self::SUPER => ['display' => '#9333ea', 'dropdown' => 'secondary'],
            self::MANAGER => ['display' => '#10b981', 'dropdown' => 'success'],
            self::SERVICE => ['display' => '#f97316', 'dropdown' => 'warning'],
            self::BOOKING => ['display' => '#6366f1', 'dropdown' => 'info'],
            self::PARTNER => ['display' => '#6b7280', 'dropdown' => 'gray'],
            self::GUEST => ['display' => '#ef4444', 'dropdown' => 'danger'],
            self::USER => ['display' => '#3b82f6', 'dropdown' => 'primary'],
        };
    }
}
