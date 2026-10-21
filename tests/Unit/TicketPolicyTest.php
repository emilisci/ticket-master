<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use App\Policies\TicketPolicy;
use Tests\TestCase;

/**
 * Checks who may do what with a ticket, using in-memory models only (no database).
 */
class TicketPolicyTest extends TestCase
{
    private TicketPolicy $policy;

    private User $employee;

    private User $otherEmployee;

    private User $admin;

    private User $otherAdmin;

    private User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new TicketPolicy;

        $this->employee = User::factory()->make(['id' => 1]);
        $this->otherEmployee = User::factory()->make(['id' => 2]);
        $this->admin = User::factory()->admin()->make(['id' => 3]);
        $this->otherAdmin = User::factory()->admin()->make(['id' => 4]);
        $this->superadmin = User::factory()->superadmin()->make(['id' => 5]);
    }

    private function ticketOf(User $owner, ?User $assignedAdmin = null): Ticket
    {
        return Ticket::factory()->make([
            'id' => 100,
            'user_id' => $owner->id,
            'admin_id' => $assignedAdmin?->id,
        ]);
    }

    public function test_employee_can_create_ticket(): void
    {
        $this->assertTrue($this->policy->create($this->employee));
    }

    public function test_employee_can_view_own_ticket(): void
    {
        $this->assertTrue($this->policy->view($this->employee, $this->ticketOf($this->employee)));
    }

    public function test_employee_cannot_view_someone_elses_ticket(): void
    {
        $this->assertFalse($this->policy->view($this->employee, $this->ticketOf($this->otherEmployee)));
    }

    public function test_employee_cannot_edit_or_delete_own_ticket(): void
    {
        $ownTicket = $this->ticketOf($this->employee, $this->admin);

        $this->assertFalse($this->policy->update($this->employee, $ownTicket));
        $this->assertFalse($this->policy->delete($this->employee, $ownTicket));
    }

    public function test_admin_can_view_and_edit_ticket_assigned_to_them(): void
    {
        $assignedTicket = $this->ticketOf($this->employee, $this->admin);

        $this->assertTrue($this->policy->view($this->admin, $assignedTicket));
        $this->assertTrue($this->policy->update($this->admin, $assignedTicket));
    }

    public function test_admin_cannot_view_ticket_assigned_to_another_admin(): void
    {
        $otherAdminTicket = $this->ticketOf($this->employee, $this->otherAdmin);

        $this->assertFalse($this->policy->view($this->admin, $otherAdminTicket));
        $this->assertFalse($this->policy->update($this->admin, $otherAdminTicket));
    }

    public function test_admin_cannot_assign_or_permanently_delete_ticket(): void
    {
        $assignedTicket = $this->ticketOf($this->employee, $this->admin);

        $this->assertFalse($this->policy->assign($this->admin, $assignedTicket));
        $this->assertFalse($this->policy->forceDelete($this->admin, $assignedTicket));
    }

    public function test_superadmin_can_view_any_ticket(): void
    {
        $this->assertTrue($this->policy->view($this->superadmin, $this->ticketOf($this->employee, $this->admin)));
        $this->assertTrue($this->policy->view($this->superadmin, $this->ticketOf($this->otherEmployee)));
    }

    public function test_superadmin_can_delete_restore_and_permanently_delete_ticket(): void
    {
        $ticket = $this->ticketOf($this->employee, $this->admin);

        $this->assertTrue($this->policy->delete($this->superadmin, $ticket));
        $this->assertTrue($this->policy->restore($this->superadmin, $ticket));
        $this->assertTrue($this->policy->forceDelete($this->superadmin, $ticket));
    }

    public function test_superadmin_can_assign_unassigned_ticket(): void
    {
        $this->assertTrue($this->policy->assign($this->superadmin, $this->ticketOf($this->employee)));
    }
}
