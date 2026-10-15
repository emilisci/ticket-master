<?php

namespace Tests\Feature;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Resources\Tickets\Pages\ViewTicket;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->superadmin()->create());
    }

    public function test_view_page_shows_ticket_details(): void
    {
        $ticket = Ticket::factory()->create([
            'status' => TicketStatus::InProgress,
            'priority' => TicketPriority::High,
        ]);

        Livewire::test(ViewTicket::class, ['record' => $ticket->id])
            ->assertOk()
            ->assertSee($ticket->title)
            ->assertSee($ticket->user->name)
            ->assertSee($ticket->admin->name)
            ->assertSee(TicketStatus::InProgress->getLabel())
            ->assertSee(TicketPriority::High->getLabel());
    }

    public function test_edit_page_is_filled_with_ticket_data(): void
    {
        $ticket = Ticket::factory()->create();

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->assertOk()
            ->assertSchemaStateSet([
                'title' => $ticket->title,
                'description' => $ticket->description,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
                'user_id' => $ticket->user_id,
                'admin_id' => $ticket->admin_id,
            ]);
    }

    public function test_ticket_can_be_updated(): void
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatus::Open]);
        $newAdmin = User::factory()->admin()->create();

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm([
                'title' => 'Updated title',
                'status' => TicketStatus::Closed,
                'priority' => TicketPriority::Low,
                'admin_id' => $newAdmin->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas(Ticket::class, [
            'id' => $ticket->id,
            'title' => 'Updated title',
            'status' => TicketStatus::Closed->value,
            'priority' => TicketPriority::Low->value,
            'admin_id' => $newAdmin->id,
        ]);
    }

    public function test_regular_user_cannot_be_assigned_as_admin(): void
    {
        $ticket = Ticket::factory()->create();
        $regularUser = User::factory()->create();

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm(['admin_id' => $regularUser->id])
            ->call('save')
            ->assertHasFormErrors(['admin_id']);
    }

    public function test_required_fields_are_validated(): void
    {
        $ticket = Ticket::factory()->create();

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm([
                'title' => null,
                'description' => null,
                'status' => null,
            ])
            ->call('save')
            ->assertHasFormErrors([
                'title' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);
    }
}
