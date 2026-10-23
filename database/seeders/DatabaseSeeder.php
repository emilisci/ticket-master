<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->superadmin()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
        ]);

        $admin = User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
        ]);

        $createdDuringLastMonth = function (): array {
            $createdAt = fake()->dateTimeBetween('-30 days');

            return ['created_at' => $createdAt, 'updated_at' => $createdAt];
        };

        Ticket::factory(3)->for($user)->for($admin, 'admin')->state($createdDuringLastMonth)->create();
        Ticket::factory(2)->for($user)->state($createdDuringLastMonth)->create(['admin_id' => null]);
        Ticket::factory(50)->for($admin, 'admin')->state($createdDuringLastMonth)->create();
    }
}
