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
        return $user->canManageTickets() || $ticket->user()->is($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->canManageTickets();
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->canManageTickets();
    }

    public function deleteAny(User $user): bool
    {
        return $user->canManageTickets();
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->canManageTickets();
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
