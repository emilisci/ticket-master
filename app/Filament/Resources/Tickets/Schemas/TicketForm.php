<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Rules\MeaningfulText;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        $canManageTickets = fn (): bool => auth()->user()->canManageTickets();

        return $schema
            ->components([
                Section::make('Ticket')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(150)
                            ->rule(new MeaningfulText(minLength: 5))
                            ->dehydrateStateUsing(fn (?string $state): ?string => $state === null ? null : trim($state)),
                        Textarea::make('description')
                            ->required()
                            ->maxLength(5000)
                            ->rule(new MeaningfulText(minLength: 10))
                            ->dehydrateStateUsing(fn (?string $state): ?string => $state === null ? null : trim($state))
                            ->rows(6),
                    ])
                    ->columnSpanFull(),
                Section::make('Details')
                    ->schema([
                        Select::make('status')
                            ->options(TicketStatus::class)
                            ->default(TicketStatus::New)
                            ->required()
                            ->visible($canManageTickets),
                        Select::make('category')
                            ->options(TicketCategory::class)
                            ->required(),
                        Select::make('priority')
                            ->options(TicketPriority::class)
                            ->default(TicketPriority::Medium)
                            ->required(),
                        Select::make('user_id')
                            ->label('User')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible($canManageTickets),
                        Select::make('admin_id')
                            ->label('Admin')
                            ->relationship(
                                name: 'admin',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query): Builder => $query->whereIn('role', [UserRole::Admin, UserRole::Superadmin]),
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder('Unassigned')
                            ->visible($canManageTickets)
                            ->disabled(fn (): bool => ! auth()->user()->isSuperadmin()),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
