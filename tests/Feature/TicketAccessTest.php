<?php

namespace Tests\Feature;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Filament\Resources\Tickets\Pages\ViewTicket;
use App\Models\Ticket;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
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

    public function test_missing_ticket_shows_not_found_page(): void
    {
        $this->actingAs(User::factory()->superadmin()->create());

        $this->get(route('filament.admin.resources.tickets.view', 999))->assertNotFound()->assertSee('Puslapis nerastas');
        $this->get(route('filament.admin.resources.tickets.edit', 999))->assertNotFound()->assertSee('Puslapis nerastas');
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
                'category' => TicketCategory::Hardware,
                'priority' => TicketPriority::High,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Ticket::class, [
            'title' => 'Printer does not work',
            'user_id' => $employee->id,
            'admin_id' => null,
            'status' => TicketStatus::New->value,
            'category' => TicketCategory::Hardware->value,
            'priority' => TicketPriority::High->value,
        ]);
    }

    public function test_employee_cannot_assign_tickets(): void
    {
        $employee = User::factory()->create();
        $ticket = Ticket::factory()->for($employee)->create(['admin_id' => null]);

        $this->actingAs($employee);

        Livewire::test(ListTickets::class)
            ->assertActionHidden(TestAction::make('assignToMe')->table($ticket));

        Livewire::test(ViewTicket::class, ['record' => $ticket->id])
            ->assertActionHidden('assignToMe');
    }

    public function test_admin_sees_only_tickets_assigned_to_them(): void
    {
        $admin = User::factory()->admin()->create();
        $assignedTicket = Ticket::factory()->for($admin, 'admin')->create();
        $otherAdminTicket = Ticket::factory()->create();
        $unassignedTicket = Ticket::factory()->create(['admin_id' => null]);

        $this->actingAs($admin);

        Livewire::test(ListTickets::class)
            ->assertCanSeeTableRecords([$assignedTicket])
            ->assertCanNotSeeTableRecords([$otherAdminTicket, $unassignedTicket]);
    }

    public function test_admin_can_edit_assigned_ticket_but_cannot_open_others(): void
    {
        $admin = User::factory()->admin()->create();
        $assignedTicket = Ticket::factory()->for($admin, 'admin')->create();
        $otherAdminTicket = Ticket::factory()->create();

        $this->actingAs($admin);

        $this->get(route('filament.admin.resources.tickets.edit', $assignedTicket))->assertOk();
        $this->get(route('filament.admin.resources.tickets.view', $otherAdminTicket))->assertNotFound();
        $this->get(route('filament.admin.resources.tickets.edit', $otherAdminTicket))->assertNotFound();
    }

    public function test_admin_cannot_change_responsible_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $anotherAdmin = User::factory()->admin()->create();
        $ticket = Ticket::factory()->for($admin, 'admin')->create();

        $this->actingAs($admin);

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->assertFormFieldDisabled('admin_id')
            ->fillForm([
                'title' => 'Updated by admin',
                'admin_id' => $anotherAdmin->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $ticket->refresh();

        $this->assertSame('Updated by admin', $ticket->title);
        $this->assertSame($admin->id, $ticket->admin_id);
    }

    public function test_admin_cannot_assign_tickets(): void
    {
        $admin = User::factory()->admin()->create();
        $ticket = Ticket::factory()->for($admin, 'admin')->create();

        $this->assertFalse($admin->can('assign', $ticket));
    }

    public function test_ticket_created_by_admin_is_assigned_to_them(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->create();

        $this->actingAs($admin);

        Livewire::test(CreateTicket::class)
            ->fillForm([
                'title' => 'Reported by phone',
                'description' => 'Cannot log in to the accounting system.',
                'category' => TicketCategory::Access,
                'priority' => TicketPriority::Medium,
                'status' => TicketStatus::New,
                'user_id' => $employee->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Ticket::class, [
            'title' => 'Reported by phone',
            'user_id' => $employee->id,
            'admin_id' => $admin->id,
        ]);
    }

    public function test_superadmin_sees_all_tickets_and_can_change_responsible_admin(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $newAdmin = User::factory()->admin()->create();
        $tickets = Ticket::factory(2)->create();
        $unassignedTicket = Ticket::factory()->create(['admin_id' => null]);

        $this->actingAs($superadmin);

        Livewire::test(ListTickets::class)
            ->assertCanSeeTableRecords([...$tickets, $unassignedTicket]);

        Livewire::test(EditTicket::class, ['record' => $unassignedTicket->id])
            ->assertFormFieldEnabled('admin_id')
            ->fillForm(['admin_id' => $newAdmin->id])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame($newAdmin->id, $unassignedTicket->refresh()->admin_id);
    }

    public function test_only_superadmin_can_force_delete(): void
    {
        $ticket = Ticket::factory()->create();

        $this->assertFalse(User::factory()->admin()->create()->can('forceDelete', $ticket));
        $this->assertTrue(User::factory()->superadmin()->create()->can('forceDelete', $ticket));
    }
}
