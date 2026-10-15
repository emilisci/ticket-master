<?php

namespace Tests\Feature;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_sees_only_own_tickets_in_list(): void
    {
        $employee = User::factory()->create();
        $ownTicket = Ticket::factory()->for($employee)->create();
        $otherTicket = Ticket::factory()->create();

        $this->actingAs($employee);

        Livewire::test(ListTickets::class)
            ->assertCanSeeTableRecords([$ownTicket])
            ->assertCanNotSeeTableRecords([$otherTicket]);
    }

    public function test_employee_cannot_open_someone_elses_ticket(): void
    {
        $otherTicket = Ticket::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('filament.admin.resources.tickets.view', $otherTicket))
            ->assertNotFound();
    }

    public function test_employee_can_view_but_not_edit_own_ticket(): void
    {
        $employee = User::factory()->create();
        $ticket = Ticket::factory()->for($employee)->create();

        $this->actingAs($employee);

        $this->get(route('filament.admin.resources.tickets.view', $ticket))->assertOk();
        $this->get(route('filament.admin.resources.tickets.edit', $ticket))->assertForbidden();
    }

    public function test_employee_created_ticket_is_own_open_and_unassigned(): void
    {
        $employee = User::factory()->create();

        $this->actingAs($employee);

        Livewire::test(CreateTicket::class)
            ->assertFormFieldHidden('status')
            ->assertFormFieldHidden('user_id')
            ->assertFormFieldHidden('admin_id')
            ->fillForm([
                'title' => 'Printer does not work',
                'description' => 'The second floor printer is jammed.',
                'priority' => TicketPriority::High,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Ticket::class, [
            'title' => 'Printer does not work',
            'user_id' => $employee->id,
            'admin_id' => null,
            'status' => TicketStatus::Open->value,
            'priority' => TicketPriority::High->value,
        ]);
    }

    public function test_admin_sees_all_tickets_and_can_edit(): void
    {
        $admin = User::factory()->admin()->create();
        $tickets = Ticket::factory(3)->create();

        $this->actingAs($admin);

        Livewire::test(ListTickets::class)->assertCanSeeTableRecords($tickets);

        $this->get(route('filament.admin.resources.tickets.edit', $tickets->first()))->assertOk();
    }

    public function test_only_superadmin_can_force_delete(): void
    {
        $ticket = Ticket::factory()->create();

        $this->assertFalse(User::factory()->admin()->create()->can('forceDelete', $ticket));
        $this->assertTrue(User::factory()->superadmin()->create()->can('forceDelete', $ticket));
    }
}
