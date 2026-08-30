<?php

namespace App\Filament\Company\Resources\MotorPools\Schemas;

use App\Models\Personnel;
use Filament\Forms\Components\Hidden;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MotorPoolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Hidden::make('company_id')
                ->dehydrateStateUsing(fn() => auth()->user()?->company_id)
                ->dehydrated(true),

            Section::make('Vehicle Information')
                ->schema([
                    TextInput::make('vehicle_type')
                        ->label('Vehicle Type')
                        ->required()
                        ->placeholder('e.g: Jeep, Truck, Motorcycle'),

                    TextInput::make('plate_number')
                        ->label('Plate Number')
                        ->nullable(),

                    TextInput::make('serial_number')
                        ->label('Serial Number')
                        ->nullable(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'active'   => 'Active',
                            'repair'   => 'Under Repair',
                            'inactive' => 'Out of Service',
                        ])
                        ->default('active')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Personnel')
                ->schema([
                    Select::make('driver_personnel_id')
                        ->label('Driver')
                        ->options(fn() =>
                            Personnel::where('company_id', auth()->user()?->company_id)
                                ->orderByRaw("FIELD(rank_type,'officer_a','officer_b','vazife_officer','nco','vazife_nco','soldier')")
                                ->get()
                                ->mapWithKeys(fn($p) => [
                                    $p->id => $p->rank_label . ' ' . $p->full_name
                                ])
                        )
                        ->searchable()
                        ->nullable(),

                    Select::make('responsible_personnel_id')
                        ->label('Responsible Personnel')
                        ->options(fn() =>
                            Personnel::where('company_id', auth()->user()?->company_id)
                                ->orderByRaw("FIELD(rank_type,'officer_a','officer_b','vazife_officer','nco','vazife_nco','soldier')")
                                ->get()
                                ->mapWithKeys(fn($p) => [
                                    $p->id => $p->rank_label . ' ' . $p->full_name
                                ])
                        )
                        ->searchable()
                        ->nullable(),

                    Textarea::make('notes')
                        ->label('Notes')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
