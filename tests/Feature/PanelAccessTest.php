<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('filament.admin.auth.login'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function roles(): array
    {
        return [
            'superadmin' => ['superadmin'],
            'admin' => ['admin'],
            'user' => ['user'],
        ];
    }

    #[DataProvider('roles')]
    public function test_every_role_can_open_dashboard(string $role): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)->get('/')->assertOk();
    }

    public function test_superadmin_can_manage_users(): void
    {
        $superadmin = User::factory()->superadmin()->create();

        $this->actingAs($superadmin)->get(route('filament.admin.resources.users.index'))->assertOk();
    }

    public function test_admin_and_user_cannot_manage_users(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('filament.admin.resources.users.index'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create())
            ->get(route('filament.admin.resources.users.index'))
            ->assertForbidden();
    }
}
