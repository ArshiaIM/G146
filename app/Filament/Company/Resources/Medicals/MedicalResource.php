<?php

namespace App\Filament\Company\Resources\Medicals;

use App\Filament\Company\Resources\Medicals\Pages\CreateMedical;
use App\Filament\Company\Resources\Medicals\Pages\EditMedical;
use App\Filament\Company\Resources\Medicals\Pages\ListMedicals;
use App\Filament\Company\Resources\Medicals\Schemas\MedicalForm;
use App\Filament\Company\Resources\Medicals\Tables\MedicalsTable;
use App\Models\Medical;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalResource extends Resource
{
    protected static ?string $model = Medical::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MedicalResource';

    public static function form(Schema $schema): Schema
    {
        return MedicalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalsTable::configure($table);
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
            'index' => ListMedicals::route('/'),
            'create' => CreateMedical::route('/create'),
            'edit' => EditMedical::route('/{record}/edit'),
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
