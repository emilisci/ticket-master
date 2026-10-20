<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Waiting = 'waiting';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Nauja',
            self::InProgress => 'Vykdoma',
            self::Waiting => 'Laukiama',
            self::Resolved => 'Išspręsta',
            self::Closed => 'Uždaryta',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'info',
            self::InProgress => 'primary',
            self::Waiting => 'warning',
            self::Resolved => 'success',
            self::Closed => 'gray',
        };
    }
}
