<?php

namespace App\Filament\Admin\Resources\Battalions;

use App\Filament\Admin\Resources\Battalions\Pages\CreateBattalion;
use App\Filament\Admin\Resources\Battalions\Pages\CreateBattalions;
use App\Filament\Admin\Resources\Battalions\Pages\EditBattalion;
use App\Filament\Admin\Resources\Battalions\Pages\EditBattallions;
use App\Filament\Admin\Resources\Battalions\Pages\ListBattalions;
use App\Models\Battalion;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;

class BattalionResource extends Resource
{
    protected static ?string $model = Battalion::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;
    protected static ?string $navigationLabel = 'گردان‌ها';
    protected static ?string $modelLabel      = 'گردان';
    protected static ?int    $navigationSort  = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('نام گردان')
                ->required(),

            Select::make('commander_id')
                ->label('فرمانده')
                ->options(
                    \App\Models\Personnel::where('rank_type', 'officer_a')
                        ->get()
                        ->mapWithKeys(fn($p) => [
                            $p->id => $p->rank_label . ' ' . $p->full_name
                        ])
                )
                ->searchable()
                ->nullable(),

            TextInput::make('code')
                ->label('کد')
                ->unique(ignoreRecord: true)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام گردان')
                    ->sortable(),

                TextColumn::make('commanderPersonnel.full_name')
                    ->label('فرمانده')
                    ->placeholder('—'),

                TextColumn::make('code')
                    ->label('کد')
                    ->placeholder('—'),

                TextColumn::make('companies_count')
                    ->label('گروهان‌ها')
                    ->counts('companies')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('total_personnel')
                    ->label('کل پرسنل')
                    ->getStateUsing(fn($record) => $record->personnel()->count()),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('ویرایش'),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()->label('+ گردان جدید'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\Battalions\RelationManagers\CompaniesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBattalions::route('/'),
            'create' => CreateBattalions::route('/create'),
            'edit'   => EditBattallions::route('/{record}/edit'),
        ];
    }
}
