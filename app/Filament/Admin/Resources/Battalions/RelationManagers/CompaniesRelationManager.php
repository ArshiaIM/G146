<?php

namespace App\Filament\Admin\Resources\Battalions\RelationManagers;

use App\Models\Company;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class CompaniesRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';
    protected static ?string $title = 'گروهان‌های این گردان';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('نام گروهان')
                ->required(),

            TextInput::make('commander')
                ->label('فرمانده')
                ->nullable(),

            TextInput::make('code')
                ->label('کد لاگین')
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('گروهان'),

                TextColumn::make('commander')
                    ->label('فرمانده')
                    ->placeholder('—'),

                TextColumn::make('code')
                    ->label('کد لاگین')
                    ->placeholder('—'),

                TextColumn::make('total_personnel')
                    ->label('پرسنل')
                    ->getStateUsing(fn($record) => $record->personnel()->count()),

                TextColumn::make('active_personnel')
                    ->label('فعال')
                    ->getStateUsing(fn($record) => $record->personnel()->where('status', 'active')->count())
                    ->color('success'),
            ])
            ->headerActions([
                CreateAction::make()->label('+ گروهان جدید'),
            ])
            ->recordActions([
                EditAction::make()->label('ویرایش'),
                DeleteAction::make()->label('حذف'),
            ]);
    }
}
