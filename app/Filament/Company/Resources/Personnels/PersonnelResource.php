<?php

namespace App\Filament\Company\Resources\Personnels;

use App\Filament\Company\Resources\Personnels\Pages\CreatePersonnel;
use App\Filament\Company\Resources\Personnels\Pages\EditPersonnel;
use App\Filament\Company\Resources\Personnels\Pages\ListPersonnels;
use App\Filament\Company\Resources\Personnels\Schemas\PersonnelForm;
use App\Filament\Company\Resources\Personnels\Tables\PersonnelsTable;
use App\Models\Personnel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'PersonnelRecource';

    public static function form(Schema $schema): Schema
    {
        return PersonnelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonnelsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPersonnels::route('/'),
            'create' => CreatePersonnel::route('/create'),
            'edit' => EditPersonnel::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
