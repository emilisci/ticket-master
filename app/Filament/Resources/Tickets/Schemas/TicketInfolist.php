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
                Section::make(__('Ticket'))
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('Title')),
                        TextEntry::make('description')
                            ->label(__('Description'))
                            ->prose(),
                    ])
                    ->columnSpanFull(),
                Section::make(__('Details'))
                    ->schema([
                        TextEntry::make('category')
                            ->label(__('Category')),
                        TextEntry::make('status')
                            ->label(__('Status'))
                            ->badge(),
                        TextEntry::make('priority')
                            ->label(__('Priority'))
                            ->badge(),
                        TextEntry::make('user.name')
                            ->label(__('Submitted by')),
                        TextEntry::make('admin.name')
                            ->label(__('Assigned admin'))
                            ->placeholder(__('Unassigned')),
                        TextEntry::make('created_at')
                            ->label(__('Created at'))
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label(__('Updated at'))
                            ->dateTime(),
                        TextEntry::make('deleted_at')
                            ->label(__('Deleted at'))
                            ->dateTime()
                            ->visible(fn (Ticket $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
