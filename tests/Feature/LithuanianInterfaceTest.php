<?php

namespace Tests\Feature;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LithuanianInterfaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_list_is_shown_in_lithuanian(): void
    {
        Ticket::factory()->create(['admin_id' => null]);

        $this->actingAs(User::factory()->superadmin()->create())
            ->get(route('filament.admin.resources.tickets.index'))
            ->assertOk()
            ->assertSee('Užklausos')
            ->assertSee('Pavadinimas')
            ->assertSee('Atsakingas')
            ->assertSee('Nepriskirta')
            ->assertSee('Priskirti man');
    }

    public function test_create_form_labels_are_in_lithuanian(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('filament.admin.resources.tickets.create'))
            ->assertOk()
            ->assertSee('Pavadinimas')
            ->assertSee('Aprašymas')
            ->assertSee('Kategorija')
            ->assertSee('Prioritetas');
    }

    public function test_validation_messages_are_in_lithuanian(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicket::class)
            ->fillForm([
                'title' => null,
                'description' => '123 456 789 000',
                'category' => TicketCategory::Other,
                'priority' => TicketPriority::Low,
            ])
            ->call('create')
            ->assertSee('Laukas „pavadinimas“ yra privalomas.')
            ->assertSee('Lauke „aprašymas“ turi būti raidžių, ne vien skaičiai ar simboliai.');
    }

    public function test_enum_labels_are_in_lithuanian(): void
    {
        $this->assertSame('Aukštas', TicketPriority::High->getLabel());
        $this->assertSame('Prieigos ir paskyros', TicketCategory::Access->getLabel());
    }
}
