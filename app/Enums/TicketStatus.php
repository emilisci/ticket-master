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
            self::New => __('New'),
            self::InProgress => __('In progress'),
            self::Waiting => __('Waiting'),
            self::Resolved => __('Resolved'),
            self::Closed => __('Closed'),
        };
    }

    /**
     * Whether a ticket in this status counts as solved (FR-8).
     */
    public function isResolved(): bool
    {
        return in_array($this, [self::Resolved, self::Closed], true);
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
