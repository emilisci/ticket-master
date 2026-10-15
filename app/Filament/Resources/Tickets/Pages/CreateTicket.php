<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    /**
     * Employees always create tickets for themselves, starting unassigned and open.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->canManageTickets()) {
            return $data;
        }

        return [
            ...$data,
            'user_id' => auth()->id(),
            'admin_id' => null,
            'status' => TicketStatus::Open,
        ];
    }
}
