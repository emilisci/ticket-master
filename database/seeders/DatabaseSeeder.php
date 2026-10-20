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

        Ticket::factory(3)->for($user)->for($admin, 'admin')->create();
        Ticket::factory(2)->for($user)->create(['admin_id' => null]);
        Ticket::factory(50)->for($admin, 'admin')->create();
    }
}
