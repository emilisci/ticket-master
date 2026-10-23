<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TicketCategory: string implements HasLabel
{
    case Hardware = 'hardware';
    case Software = 'software';
    case Access = 'access';
    case Network = 'network';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Hardware => __('Hardware'),
            self::Software => __('Software'),
            self::Access => __('Accounts and access'),
            self::Network => __('Network'),
            self::Other => __('Other'),
        };
    }
}
