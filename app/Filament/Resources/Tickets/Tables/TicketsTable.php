<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Tickets\Actions\AssignToMeAction;
use App\Models\Ticket;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        $canManageTickets = fn (): bool => auth()->user()->canManageTickets();
        $isSuperadmin = fn (): bool => auth()->user()->isSuperadmin();

        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->description(fn (Ticket $record): string => Str::limit($record->description, 60))
                    ->wrap(),
                TextColumn::make('category')
                    ->label(__('Category')),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('priority')
                    ->label(__('Priority'))
                    ->badge()
                    ->sortable(query: fn (Builder $query, string $direction): Builder => static::orderByPriority($query, $direction)),
                TextColumn::make('user.name')
                    ->label(__('Submitted by'))
                    ->searchable()
                    ->visible($canManageTickets),
                TextColumn::make('admin.name')
                    ->label(__('Assigned admin'))
                    ->placeholder(__('Unassigned')),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('priority', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(TicketStatus::class)
                    ->multiple(),
                SelectFilter::make('priority')
                    ->label(__('Priority'))
                    ->options(TicketPriority::class)
                    ->multiple(),
                SelectFilter::make('category')
                    ->label(__('Category'))
                    ->options(TicketCategory::class)
                    ->multiple(),
                SelectFilter::make('admin')
                    ->label(__('Assigned admin'))
                    ->relationship(
                        name: 'admin',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->whereIn('role', [UserRole::Admin, UserRole::Superadmin]),
                    )
                    ->searchable()
                    ->preload()
                    ->visible($isSuperadmin),
                Filter::make('unassigned')
                    ->label(__('Unassigned only'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('admin_id'))
                    ->toggle()
                    ->visible($isSuperadmin),
                TrashedFilter::make()
                    ->visible($canManageTickets),
            ])
            ->recordActions([
                AssignToMeAction::make(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Sort by priority weight (most urgent first when descending), then oldest tickets first.
     */
    protected static function orderByPriority(Builder $query, string $direction): Builder
    {
        $direction = $direction === 'desc' ? 'desc' : 'asc';

        $cases = collect(TicketPriority::cases())
            ->map(fn (TicketPriority $priority): string => "when '{$priority->value}' then {$priority->weight()}")
            ->implode(' ');

        return $query
            ->orderByRaw("case priority {$cases} end {$direction}")
            ->orderBy('created_at');
    }
}
