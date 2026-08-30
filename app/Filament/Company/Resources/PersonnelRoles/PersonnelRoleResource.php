<?php

namespace App\Filament\Company\Resources\PersonnelRoles;

use App\Filament\Company\Resources\PersonnelRoles\Pages\CreatePersonnelRole;
use App\Filament\Company\Resources\PersonnelRoles\Pages\EditPersonnelRole;
use App\Filament\Company\Resources\PersonnelRoles\Pages\ListPersonnelRoles;
use App\Filament\Company\Resources\PersonnelRoles\Schemas\PersonnelRoleForm;
use App\Filament\Company\Resources\PersonnelRoles\Tables\PersonnelRolesTable;
use App\Models\PersonnelRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PersonnelRoleResource extends Resource
{
    protected static ?string $model = PersonnelRole::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PersonnelRoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonnelRolesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPersonnelRoles::route('/'),
            'create' => CreatePersonnelRole::route('/create'),
            'edit'   => EditPersonnelRole::route('/{record}/edit'),
        ];
    }
}
