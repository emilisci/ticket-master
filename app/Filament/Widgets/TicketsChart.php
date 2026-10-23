<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class TicketsChart extends ChartWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    public ?string $filter = '30';

    /**
     * Only staff see the chart; employees have their own ticket list instead.
     */
    public static function canView(): bool
    {
        return auth()->user()?->canManageTickets() ?? false;
    }

    public function getHeading(): string
    {
        return __('Created and resolved tickets');
    }

    public function getDescription(): string
    {
        return auth()->user()->isSuperadmin()
            ? __('All tickets')
            : __('Tickets assigned to you');
    }

    /**
     * @return array<string, string>
     */
    protected function getFilters(): array
    {
        return [
            '7' => __('Last 7 days'),
            '30' => __('Last 30 days'),
            '90' => __('Last 90 days'),
        ];
    }

    /**
     * The filter is user-controllable, so anything unexpected falls back to 30 days.
     */
    private function periodInDays(): int
    {
        return match ($this->filter) {
            '7' => 7,
            '90' => 90,
            default => 30,
        };
    }

    /**
     * @return array{datasets: list<array{label: string, data: list<int>}>, labels: list<string>}
     */
    protected function getData(): array
    {
        $firstDay = CarbonImmutable::today()->subDays($this->periodInDays() - 1);

        $days = collect(range(0, $this->periodInDays() - 1))
            ->map(fn (int $offset): CarbonImmutable => $firstDay->addDays($offset));

        return [
            'datasets' => [
                [
                    'label' => __('Tickets created'),
                    'data' => $this->countPerDay('created_at', $firstDay, $days),
                ],
                [
                    'label' => __('Tickets resolved'),
                    'data' => $this->countPerDay('resolved_at', $firstDay, $days),
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.1)',
                ],
            ],
            'labels' => $days->map(fn (CarbonImmutable $day): string => $day->format('m-d'))->all(),
        ];
    }

    /**
     * @param  'created_at'|'resolved_at'  $dateColumn
     * @param  Collection<int, CarbonImmutable>  $days
     * @return list<int>
     */
    private function countPerDay(string $dateColumn, CarbonImmutable $firstDay, Collection $days): array
    {
        $ticketsPerDay = Ticket::query()
            ->visibleTo(auth()->user())
            ->where($dateColumn, '>=', $firstDay)
            ->pluck($dateColumn)
            ->countBy(fn (CarbonInterface $date): string => $date->toDateString());

        return $days->map(fn (CarbonImmutable $day): int => $ticketsPerDay->get($day->toDateString(), 0))->all();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
