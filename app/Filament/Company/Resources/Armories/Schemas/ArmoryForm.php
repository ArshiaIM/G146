<?php

namespace App\Filament\Company\Resources\Armories\Schemas;

use App\Models\Personnel;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArmoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->dehydrateStateUsing(fn() => auth()->user()?->company_id)
                    ->dehydrated(true),

                Section::make('اطلاعات سلاح')
                    ->schema([
                        TextInput::make('weapon_type')
                            ->label('نوع سلاح')
                            ->required()
                            ->placeholder('مثلاً: کلاشینکف، آرپی‌جی، تیربار'),

                        TextInput::make('serial_number')
                            ->label('شماره سریال')
                            ->nullable(),

                        TextInput::make('quantity')
                            ->label('تعداد')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'active' => '✅ فعال',
                                'repair' => '🔧 در تعمیر',
                                'lost'   => '❌ مفقود',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('مسئول')
                    ->schema([
                        Select::make('responsible_personnel_id')
                            ->label('مسئول سلاح')
                            ->options(
                                fn() =>
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
                            ->label('یادداشت')
                            ->rows(3)
                            ->nullable(),
                    ])
            ]);
    }
}
