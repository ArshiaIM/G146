<?php

namespace App\Filament\Company\Resources\PersonnelRoles\Schemas;

use App\Models\Personnel;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonnelRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->dehydrateStateUsing(fn() => auth()->user()?->company_id)
                    ->dehydrated(true),

                Section::make('Personnel')
                    ->schema([
                        Select::make('personnel_id')
                            ->label('Personnel')
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
                            ->required(),
                    ]),

                Section::make('Role Information')
                    ->schema([
                        TextInput::make('role_title')
                            ->label('Role Title')
                            ->required()
                            ->placeholder('e.g: Machine Gunner, RPG Operator'),

                        TextInput::make('position')
                            ->label('Position')
                            ->nullable()
                            ->placeholder('e.g: Team Leader, Deputy'),

                        Textarea::make('responsibilities')
                            ->label('Responsibilities')
                            ->rows(4)
                            ->nullable()
                            ->columnSpanFull(),

                        Toggle::make('is_primary')
                            ->label('Primary Role')
                            ->default(true),
                    ])
                    ->columns(2),

            ]);
    }
}
