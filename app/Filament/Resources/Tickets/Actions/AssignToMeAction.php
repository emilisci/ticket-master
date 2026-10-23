<?php

namespace App\Filament\Resources\Tickets\Actions;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class AssignToMeAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'assignToMe';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('Assign to me'));

        $this->icon(Heroicon::OutlinedUserPlus);

        $this->color('gray');

        $this->authorize('assign');

        $this->hidden(fn (Ticket $record): bool => $record->admin()->is(auth()->user()));

        $this->successNotificationTitle(__('Ticket assigned to you'));

        $this->action(function (Ticket $record): void {
            $record->admin()->associate(auth()->user())->save();

            $this->success();
        });
    }
}
