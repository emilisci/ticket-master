<?php

namespace Tests\Feature;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Models\Ticket;
use App\Models\User;
use Database\Factories\TicketFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TicketValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validTicketData(): array
    {
        return [
            'title' => 'Neveikia spausdintuvas',
            'description' => 'Antro aukšto spausdintuvas rodo klaidą 0x0001 ir nespausdina.',
            'category' => TicketCategory::Hardware,
            'priority' => TicketPriority::Medium,
        ];
    }

    /**
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function invalidTicketData(): array
    {
        return [
            'title is missing' => [['title' => null], 'title'],
            'title is only spaces' => [['title' => '        '], 'title'],
            'title is too short' => [['title' => 'Abc'], 'title'],
            'title is too short once trimmed' => [['title' => '   Ab     '], 'title'],
            'title is too long' => [['title' => str_repeat('Labas ', 26)], 'title'],
            'title is numbers only' => [['title' => '1234567'], 'title'],
            'title is symbols only' => [['title' => '!?!?!?.,-'], 'title'],
            'title is keyboard mashing' => [['title' => 'aaaaaaaaaa'], 'title'],
            'title has repeated punctuation' => [['title' => 'Padėkit!!!!!!!'], 'title'],
            'description is missing' => [['description' => null], 'description'],
            'description is too short' => [['description' => 'Neveikia'], 'description'],
            'description is too long' => [['description' => str_repeat('a b ', 1251)], 'description'],
            'description is numbers only' => [['description' => '123 456 789 000'], 'description'],
            'category is missing' => [['category' => null], 'category'],
            'category is not a known value' => [['category' => 'hacking'], 'category'],
            'priority is missing' => [['priority' => null], 'priority'],
            'priority is not a known value' => [['priority' => 'urgent!!!'], 'priority'],
        ];
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    #[DataProvider('invalidTicketData')]
    public function test_invalid_ticket_is_rejected(array $overrides, string $invalidField): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicket::class)
            ->fillForm([...$this->validTicketData(), ...$overrides])
            ->call('create')
            ->assertHasFormErrors([$invalidField]);

        $this->assertDatabaseCount(Ticket::class, 0);
    }

    public function test_valid_lithuanian_ticket_is_accepted_and_trimmed(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicket::class)
            ->fillForm([
                ...$this->validTicketData(),
                'title' => '   Neveikia spausdintuvas   ',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Ticket::class, ['title' => 'Neveikia spausdintuvas']);
    }

    public function test_numbers_inside_text_and_repeated_digits_are_allowed(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicket::class)
            ->fillForm([
                ...$this->validTicketData(),
                'title' => 'Klaida 1000000 kabinete 204',
            ])
            ->call('create')
            ->assertHasNoFormErrors();
    }

    /**
     * @return array<string, array{array{title: string, description: string, category: TicketCategory, priority: TicketPriority}}>
     */
    public static function sampleTickets(): array
    {
        return collect(TicketFactory::SAMPLE_TICKETS)
            ->mapWithKeys(fn (array $sampleTicket): array => [$sampleTicket['title'] => [$sampleTicket]])
            ->all();
    }

    /**
     * @param  array{title: string, description: string, category: TicketCategory, priority: TicketPriority}  $sampleTicket
     */
    #[DataProvider('sampleTickets')]
    public function test_factory_sample_ticket_passes_form_validation(array $sampleTicket): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicket::class)
            ->fillForm($sampleTicket)
            ->call('create')
            ->assertHasNoFormErrors();
    }

    public function test_edit_form_uses_the_same_validation(): void
    {
        $this->actingAs(User::factory()->superadmin()->create());

        $ticket = Ticket::factory()->create();

        Livewire::test(EditTicket::class, ['record' => $ticket->id])
            ->fillForm(['title' => '12345', 'status' => 'deleted'])
            ->call('save')
            ->assertHasFormErrors(['title', 'status']);

        $this->assertNotSame('12345', $ticket->refresh()->title);
    }
}
