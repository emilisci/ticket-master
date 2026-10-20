<?php

namespace Database\Factories;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(TicketCategory::cases()),
            'status' => $this->faker->randomElement(TicketStatus::cases()),
            'priority' => $this->faker->randomElement(TicketPriority::cases()),
            'user_id' => User::factory(),
            'admin_id' => User::factory()->admin(),
        ];
    }
}
