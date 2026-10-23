<?php

namespace Tests\Feature;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Widgets\TicketsChart;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketResolvedAtTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolving_ticket_in_edit_form_records_resolution_time(): void
    {
        $this->freezeSecond();

        $admin = User::factory()->admin()->create();
        $ticket = Ticket::factory()->for($admin, 'admin')->create(['status' => TicketStatus::InProgress]);

        $this->assertNull($ticket->resolved_at);

        $this->actingAs($admin);

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm(['status' => TicketStatus::Resolved])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue($ticket->refresh()->resolved_at->equalTo(now()));
    }

    public function test_closing_resolved_ticket_keeps_original_resolution_time(): void
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatus::InProgress]);

        $this->travelTo(now()->subDays(2));
        $ticket->update(['status' => TicketStatus::Resolved]);
        $resolvedAt = $ticket->resolved_at;
        $this->travelBack();

        $ticket->update(['status' => TicketStatus::Closed]);

        $this->assertTrue($ticket->refresh()->resolved_at->equalTo($resolvedAt));
    }

    public function test_closing_ticket_directly_also_records_resolution_time(): void
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatus::New]);

        $ticket->update(['status' => TicketStatus::Closed]);

        $this->assertNotNull($ticket->refresh()->resolved_at);
    }

    public function test_reopening_ticket_clears_resolution_time(): void
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatus::New]);
        $ticket->update(['status' => TicketStatus::Resolved]);

        $ticket->update(['status' => TicketStatus::InProgress]);

        $this->assertNull($ticket->refresh()->resolved_at);
    }

    public function test_new_ticket_from_employee_is_not_resolved(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicket::class)
            ->fillForm([
                'title' => 'Neveikia spausdintuvas',
                'description' => 'Antro aukšto spausdintuvas nespausdina.',
                'category' => TicketCategory::Hardware,
                'priority' => TicketPriority::Medium,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertNull(Ticket::sole()->resolved_at);
    }

    public function test_resolution_time_cannot_be_mass_assigned(): void
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatus::New]);

        $ticket->update(['resolved_at' => now()->subYear()]);

        $this->assertNull($ticket->refresh()->resolved_at);
    }

    public function test_factory_resolved_tickets_are_resolved_after_they_were_created(): void
    {
        $ticket = Ticket::factory()->create([
            'status' => TicketStatus::Resolved,
            'created_at' => now()->subDays(10),
        ]);

        $this->assertTrue($ticket->resolved_at->between($ticket->created_at, now()));
    }

    public function test_chart_shows_resolved_tickets_per_day(): void
    {
        $this->travelTo(now()->startOfDay()->addHours(12));

        $superadmin = User::factory()->superadmin()->create();
        $ticket = Ticket::factory()->create(['status' => TicketStatus::InProgress, 'created_at' => now()->subDays(5)]);
        $ticket->update(['status' => TicketStatus::Resolved]);

        $this->actingAs($superadmin);

        $data = (fn (): array => $this->getData())->call(Livewire::test(TicketsChart::class)->instance());

        $this->assertSame(1, $data['datasets'][1]['data'][29]);
        $this->assertSame(1, array_sum($data['datasets'][1]['data']));
    }
}
