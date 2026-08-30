<?php

namespace App\Filament\Company\Resources\Letters;

use App\Filament\Company\Resources\Letters\Pages\CreateLetter;
use App\Filament\Company\Resources\Letters\Pages\EditLetter;
use App\Filament\Company\Resources\Letters\Pages\ListLetters;
use App\Filament\Company\Resources\Letters\Schemas\LetterForm;
use App\Filament\Company\Resources\Letters\Tables\LettersTable;
use App\Models\Letter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LetterResource extends Resource
{
    protected static ?string $model = Letter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'LetterResource';

    public static function form(Schema $schema): Schema
    {
        return LetterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LettersTable::configure($table);
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
            'index' => ListLetters::route('/'),
            'create' => CreateLetter::route('/create'),
            'edit' => EditLetter::route('/{record}/edit'),
        ];
    }
}
