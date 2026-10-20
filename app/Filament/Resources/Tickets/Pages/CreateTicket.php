<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    /**
     * Admins are assigned the tickets they create, so they can still see them afterwards.
     * Employees always create tickets for themselves, starting unassigned and new.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return match (auth()->user()->role) {
            UserRole::Superadmin => $data,
            UserRole::Admin => [
                ...$data,
                'admin_id' => auth()->id(),
            ],
            UserRole::User => [
                ...$data,
                'user_id' => auth()->id(),
                'admin_id' => null,
                'status' => TicketStatus::New,
            ],
        };
    }
}
