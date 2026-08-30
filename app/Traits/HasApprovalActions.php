<?php

namespace App\Traits;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

trait HasApprovalActions
{
    public static function getApprovalDeleteActions(): array
    {
        return [
            // فرمانده گروهان و گردان مستقیم حذف میکنن
            DeleteAction::make()
                ->visible(
                    fn($record) =>
                    auth()->user()->role === 'battalion_admin'
                ),

            // operator درخواست حذف میفرسته
            Action::make('request_delete')
                ->label('Request Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->visible(fn($record) => in_array(auth()->user()->role , ['company_admin','operator']))
                ->form([
                    Textarea::make('reason')
                        ->label('Reason for deletion')
                        ->required()
                        ->rows(3),
                ])
                ->action(function ($record, array $data) {
                    $record->submitDeleteRequest($data['reason']);

                    Notification::make()
                        ->title('Delete request submitted for approval')
                        ->warning()
                        ->send();
                }),
        ];
    }
}
