<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case Superadmin = 'superadmin';
    case Admin = 'admin';
    case User = 'user';

    public function getLabel(): string
    {
        return match ($this) {
            self::Superadmin => __('Superadmin'),
            self::Admin => __('Admin'),
            self::User => __('User'),
        };
    }
}
