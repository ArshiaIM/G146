<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\Battalion;
use App\Models\Company;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('اطلاعات کاربر')
                    ->schema([
                        TextInput::make('name')
                            ->label('نام کامل')
                            ->required(),

                        TextInput::make('username')
                            ->label('نام کاربری')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->label('رمز عبور')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context) => $context === 'create'),

                        Select::make('role')
                            ->label('نقش')
                            ->options([
                                'super_admin'     => '👑 فرمانده تیپ',
                                'battalion_admin' => '🎖️ فرمانده گردان',
                                'company_admin'   => '🏢 فرمانده گروهان',
                                'operator'        => '💻 اپراتور',
                            ])
                            ->required()
                            ->live(),
                    ])
                    ->columns(2),

                Section::make('دسترسی')
                    ->schema([
                        Select::make('battalion_id')
                            ->label('گردان')
                            ->options(Battalion::pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('company_id', null))
                            ->visible(
                                fn(Get $get) =>
                                in_array($get('role'), ['battalion_admin', 'company_admin', 'operator'])
                            ),

                        Select::make('company_id')
                            ->label('گروهان')
                            ->options(
                                fn(Get $get) =>
                                $get('battalion_id')
                                    ? Company::where('battalion_id', $get('battalion_id'))->pluck('name', 'id')
                                    : Company::pluck('name', 'id')
                            )
                            ->searchable()
                            ->nullable()
                            ->visible(
                                fn(Get $get) =>
                                in_array($get('role'), ['company_admin', 'operator'])
                            ),

                        Select::make('personnel_id')
                            ->label('پرسنل مرتبط')
                            ->options(
                                fn(Get $get) => \App\Models\Personnel::query()
                                    ->when(
                                        $get('role') !== 'operator',
                                        fn($q) => $q->when(
                                            $get('company_id'),
                                            fn($q, $id) => $q->where('company_id', $id)
                                        )
                                    )
                                    ->orderByRaw("FIELD(rank_type, 'officer_a', 'officer_b', 'vazife_officer', 'nco', 'vazife_nco', 'soldier')")
                                    ->get()
                                    ->mapWithKeys(fn($p) => [
                                        $p->id => $p->rank_label . ' ' . $p->full_name
                                    ])
                            )
                            ->searchable()
                            ->nullable()
                            ->visible(
                                fn(Get $get) =>
                                in_array($get('role'), ['company_admin', 'operator', 'battalion_admin'])
                            ),
                    ])
                    ->columns(2),
            ]);
    }
}
