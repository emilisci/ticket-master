<?php

namespace Tests\Feature;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Filament\Resources\Tickets\Pages\ViewTicket;
use App\Models\Ticket;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::factory()->superadmin()->create(['name' => 'Jonas Vadovas']);

        $this->actingAs($this->superadmin);
    }

    public function test_superadmin_can_assign_ticket_to_themselves_from_the_list(): void
    {
        $ticket = Ticket::factory()->create(['admin_id' => null]);

        Livewire::test(ListTickets::class)
            ->callAction(TestAction::make('assignToMe')->table($ticket))
            ->assertNotified();

        $this->assertTrue($ticket->refresh()->admin->is($this->superadmin));
    }

    public function test_assigned_admin_name_is_shown_after_assigning_from_view_page(): void
    {
        $ticket = Ticket::factory()->create();

        Livewire::test(ViewTicket::class, ['record' => $ticket->id])
            ->callAction('assignToMe')
            ->assertNotified()
            ->assertSee('Jonas Vadovas');

        $this->assertSame($this->superadmin->id, $ticket->refresh()->admin_id);
    }

    public function test_assign_to_me_is_hidden_when_already_assigned_to_me(): void
    {
        $ticket = Ticket::factory()->for($this->superadmin, 'admin')->create();

        Livewire::test(ListTickets::class)
            ->assertActionHidden(TestAction::make('assignToMe')->table($ticket));
    }

    public function test_tickets_can_be_filtered_by_priority(): void
    {
        $highPriorityTicket = Ticket::factory()->create(['priority' => TicketPriority::High]);
        $lowPriorityTicket = Ticket::factory()->create(['priority' => TicketPriority::Low]);

        Livewire::test(ListTickets::class)
            ->filterTable('priority', [TicketPriority::High->value])
            ->assertCanSeeTableRecords([$highPriorityTicket])
            ->assertCanNotSeeTableRecords([$lowPriorityTicket]);
    }

    public function test_tickets_can_be_filtered_by_status(): void
    {
        $waitingTicket = Ticket::factory()->create(['status' => TicketStatus::Waiting]);
        $closedTicket = Ticket::factory()->create(['status' => TicketStatus::Closed]);

        Livewire::test(ListTickets::class)
            ->filterTable('status', [TicketStatus::Waiting->value])
            ->assertCanSeeTableRecords([$waitingTicket])
            ->assertCanNotSeeTableRecords([$closedTicket]);
    }

    public function test_tickets_can_be_filtered_by_responsible_admin(): void
    {
        $myTicket = Ticket::factory()->for($this->superadmin, 'admin')->create();
        $otherAdminTicket = Ticket::factory()->create();

        Livewire::test(ListTickets::class)
            ->filterTable('admin', $this->superadmin->id)
            ->assertCanSeeTableRecords([$myTicket])
            ->assertCanNotSeeTableRecords([$otherAdminTicket]);
    }

    public function test_tickets_can_be_filtered_to_unassigned_only(): void
    {
        $unassignedTicket = Ticket::factory()->create(['admin_id' => null]);
        $assignedTicket = Ticket::factory()->create();

        Livewire::test(ListTickets::class)
            ->filterTable('unassigned')
            ->assertCanSeeTableRecords([$unassignedTicket])
            ->assertCanNotSeeTableRecords([$assignedTicket]);
    }

    public function test_list_is_sorted_by_priority_with_most_urgent_first(): void
    {
        $lowPriorityTicket = Ticket::factory()->create(['priority' => TicketPriority::Low]);
        $highPriorityTicket = Ticket::factory()->create(['priority' => TicketPriority::High]);
        $mediumPriorityTicket = Ticket::factory()->create(['priority' => TicketPriority::Medium]);

        Livewire::test(ListTickets::class)
            ->assertCanSeeTableRecords([$highPriorityTicket, $mediumPriorityTicket, $lowPriorityTicket], inOrder: true);
    }
}
