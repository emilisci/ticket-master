<?php

namespace Tests\Feature;

use App\Filament\Widgets\TicketsChart;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketsChartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{datasets: list<array{label: string, data: list<int>}>, labels: list<string>}
     */
    private function chartData(?string $filter = null): array
    {
        $component = Livewire::test(TicketsChart::class);

        if ($filter !== null) {
            $component->set('filter', $filter);
        }

        return (fn (): array => $this->getData())->call($component->instance());
    }

    public function test_superadmin_and_admin_see_the_chart_on_the_dashboard(): void
    {
        $this->actingAs(User::factory()->superadmin()->create())
            ->get(route('filament.admin.pages.dashboard'))
            ->assertSeeLivewire(TicketsChart::class);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('filament.admin.pages.dashboard'))
            ->assertSeeLivewire(TicketsChart::class);
    }

    public function test_employee_does_not_see_the_chart(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('filament.admin.pages.dashboard'))
            ->assertOk()
            ->assertDontSeeLivewire(TicketsChart::class);
    }

    public function test_superadmin_chart_counts_all_tickets_per_day(): void
    {
        $this->travelTo(now()->startOfDay()->addHours(12));

        Ticket::factory(2)->create(['created_at' => now()]);
        Ticket::factory()->create(['created_at' => now()->subDays(3)]);
        Ticket::factory()->create(['created_at' => now()->subDays(40)]);

        $this->actingAs(User::factory()->superadmin()->create());

        $data = $this->chartData();

        $this->assertCount(30, $data['labels']);
        $this->assertSame(now()->format('m-d'), $data['labels'][29]);
        $this->assertSame(2, $data['datasets'][0]['data'][29]);
        $this->assertSame(1, $data['datasets'][0]['data'][26]);
        $this->assertSame(3, array_sum($data['datasets'][0]['data']));
    }

    public function test_admin_chart_counts_only_tickets_assigned_to_them(): void
    {
        $admin = User::factory()->admin()->create();

        Ticket::factory(2)->for($admin, 'admin')->create();
        Ticket::factory(3)->create();
        Ticket::factory()->create(['admin_id' => null]);

        $this->actingAs($admin);

        $this->assertSame(2, array_sum($this->chartData()['datasets'][0]['data']));
    }

    public function test_period_filter_changes_the_range(): void
    {
        Ticket::factory()->create(['created_at' => now()->subDays(10)]);
        Ticket::factory()->create(['created_at' => now()->subDays(60)]);

        $this->actingAs(User::factory()->superadmin()->create());

        $lastWeek = $this->chartData('7');
        $lastQuarter = $this->chartData('90');

        $this->assertCount(7, $lastWeek['labels']);
        $this->assertSame(0, array_sum($lastWeek['datasets'][0]['data']));
        $this->assertCount(90, $lastQuarter['labels']);
        $this->assertSame(2, array_sum($lastQuarter['datasets'][0]['data']));
    }

    public function test_unknown_filter_value_falls_back_to_30_days(): void
    {
        $this->actingAs(User::factory()->superadmin()->create());

        $this->assertCount(30, $this->chartData('9999')['labels']);
    }
}
