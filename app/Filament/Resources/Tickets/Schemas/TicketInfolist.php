<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Models\Ticket;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket')
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('description')
                            ->prose(),
                    ])
                    ->columnSpanFull(),
                Section::make('Details')
                    ->schema([
                        TextEntry::make('category'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('priority')
                            ->badge(),
                        TextEntry::make('user.name')
                            ->label('User'),
                        TextEntry::make('admin.name')
                            ->label('Admin')
                            ->placeholder('Unassigned'),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (Ticket $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
