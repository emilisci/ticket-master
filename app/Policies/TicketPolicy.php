<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $ticket->isVisibleTo($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->canManageTickets() && $ticket->isVisibleTo($user);
    }

    /**
     * Only superadmins decide which admin is responsible for a ticket.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->isSuperadmin();
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->canManageTickets() && $ticket->isVisibleTo($user);
    }

    public function deleteAny(User $user): bool
    {
        return $user->canManageTickets();
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->canManageTickets() && $ticket->isVisibleTo($user);
    }

    public function restoreAny(User $user): bool
    {
        return $user->canManageTickets();
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->isSuperadmin();
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->isSuperadmin();
    }
}
