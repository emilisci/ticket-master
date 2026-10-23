<?php

namespace App\Observers;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class TicketObserver
{
    /**
     * Let the employee who submitted the ticket know when staff act on it (US-2).
     */
    public function updated(Ticket $ticket): void
    {
        if ($ticket->user_id === auth()->id()) {
            return;
        }

        $changes = $this->describeChanges($ticket);

        if ($changes === []) {
            return;
        }

        Notification::make()
            ->title(__('Your ticket was updated'))
            ->body(e($ticket->title).'<br>'.implode('<br>', $changes))
            ->icon('heroicon-o-ticket')
            ->info()
            ->actions([
                Action::make('view')
                    ->label(__('View ticket'))
                    ->button()
                    ->url(TicketResource::getUrl('view', ['record' => $ticket], panel: 'admin'))
                    ->markAsRead(),
            ])
            ->sendToDatabase($ticket->user);
    }

    /**
     * @return list<string>
     */
    private function describeChanges(Ticket $ticket): array
    {
        $changes = [];

        if ($ticket->wasChanged('status')) {
            $changes[] = $this->describeChange(__('Status'), $ticket->getOriginal('status'), $ticket->status);
        }

        if ($ticket->wasChanged('priority')) {
            $changes[] = $this->describeChange(__('Priority'), $ticket->getOriginal('priority'), $ticket->priority);
        }

        if ($ticket->wasChanged('admin_id')) {
            $admin = $ticket->admin()->first();

            $changes[] = $admin instanceof User
                ? __('Assigned admin').': '.e($admin->name)
                : __('No longer assigned to anyone');
        }

        return $changes;
    }

    private function describeChange(string $field, TicketStatus|TicketPriority|null $from, TicketStatus|TicketPriority $to): string
    {
        return $from === null
            ? "{$field}: {$to->getLabel()}"
            : "{$field}: {$from->getLabel()} → {$to->getLabel()}";
    }
}
