<?php

namespace App\Filament\Company\Resources\Warehouses\Schemas;

use App\Models\Personnel;
use Filament\Forms\Components\Hidden;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Hidden::make('company_id')
                ->dehydrateStateUsing(fn() => auth()->user()?->company_id)
                ->dehydrated(true),

            Section::make('Item Information')
                ->schema([
                    TextInput::make('item_name')
                        ->label('Item Name')
                        ->required(),

                    TextInput::make('item_code')
                        ->label('Item Code')
                        ->nullable(),

                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    TextInput::make('unit')
                        ->label('Unit')
                        ->placeholder('e.g: pcs, kg, m')
                        ->nullable(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'available' => 'Available',
                            'low'       => 'Low Stock',
                            'out'       => 'Out of Stock',
                        ])
                        ->default('available')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Responsible')
                ->schema([
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
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }
}
