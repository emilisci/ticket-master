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
            self::Hardware => 'Įranga',
            self::Software => 'Programinė įranga',
            self::Access => 'Prieigos ir paskyros',
            self::Network => 'Tinklas',
            self::Other => 'Kita',
        };
    }
}
