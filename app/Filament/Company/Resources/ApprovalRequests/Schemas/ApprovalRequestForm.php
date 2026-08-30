<?php

namespace App\Filament\Company\Resources\ApprovalRequests\Schemas;


use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApprovalRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Request Details')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending'               => 'Pending',
                            'approved_by_company'   => 'Approved by Company',
                            'approved_by_battalion' => 'Approved by Battalion',
                            'rejected'              => 'Rejected',
                            'executed'              => 'Executed',
                        ])
                        ->disabled(),

                    Textarea::make('reason')
                        ->label('Reason')
                        ->rows(3)
                        ->disabled(),

                    Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->rows(3)
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }
}
