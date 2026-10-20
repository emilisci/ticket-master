<?php

namespace Tests\Feature;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = User::factory()->create();
        $this->admin = User::factory()->admin()->create(['name' => 'Jonas Specialistas']);
    }

    public function test_employee_is_notified_when_admin_changes_status(): void
    {
        $ticket = Ticket::factory()->for($this->employee)->for($this->admin, 'admin')->create([
            'status' => TicketStatus::New,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm(['status' => TicketStatus::InProgress])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertCount(1, $this->employee->notifications);

        $notification = $this->employee->notifications->first()->data;

        $this->assertSame('Your ticket was updated', $notification['title']);
        $this->assertStringContainsString('Status: Nauja → Vykdoma', $notification['body']);
        $this->assertSame(
            TicketResource::getUrl('view', ['record' => $ticket], panel: 'admin'),
            $notification['actions'][0]['url'],
        );
    }

    public function test_employee_is_notified_when_ticket_is_assigned(): void
    {
        $superadmin = User::factory()->superadmin()->create(['name' => 'Vadovė Asta']);
        $ticket = Ticket::factory()->for($this->employee)->create(['admin_id' => null]);

        $this->actingAs($superadmin);

        Livewire::test(ListTickets::class)
            ->callAction(TestAction::make('assignToMe')->table($ticket));

        $this->assertCount(1, $this->employee->notifications);
        $this->assertStringContainsString('Assigned to: Vadovė Asta', $this->employee->notifications->first()->data['body']);
    }

    public function test_several_changes_in_one_save_send_a_single_notification(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->for($this->employee)->create([
            'admin_id' => null,
            'status' => TicketStatus::New,
            'priority' => TicketPriority::Low,
        ]);

        $this->actingAs($superadmin);

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm([
                'status' => TicketStatus::Waiting,
                'priority' => TicketPriority::High,
                'admin_id' => $this->admin->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertCount(1, $this->employee->notifications);

        $body = $this->employee->notifications->first()->data['body'];

        $this->assertStringContainsString('Status: Nauja → Laukiama', $body);
        $this->assertStringContainsString('Priority: Žemas → Aukštas', $body);
        $this->assertStringContainsString('Assigned to: Jonas Specialistas', $body);
    }

    public function test_employee_is_not_notified_about_text_only_edits(): void
    {
        $ticket = Ticket::factory()->for($this->employee)->for($this->admin, 'admin')->create();

        $this->actingAs($this->admin);

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm(['title' => 'Neveikia spausdintuvas kabinete 204'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertCount(0, $this->employee->notifications);
    }

    public function test_nobody_is_notified_about_their_own_changes(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->for($superadmin)->create(['status' => TicketStatus::New]);

        $this->actingAs($superadmin);

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm(['status' => TicketStatus::Closed])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertCount(0, $superadmin->notifications);
    }

    public function test_ticket_title_is_escaped_in_notification(): void
    {
        $ticket = Ticket::factory()->for($this->employee)->for($this->admin, 'admin')->create([
            'title' => 'Klaida <script>alert(1)</script>',
            'status' => TicketStatus::New,
        ]);

        $this->actingAs($this->admin);

        $ticket->update(['status' => TicketStatus::Resolved]);

        $body = $this->employee->notifications->first()->data['body'];

        $this->assertStringNotContainsString('<script>', $body);
        $this->assertStringContainsString('&lt;script&gt;', $body);
    }
}
