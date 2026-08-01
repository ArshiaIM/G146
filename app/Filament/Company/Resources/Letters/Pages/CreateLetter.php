<?php

namespace App\Filament\Company\Resources\Letters\Pages;

use App\Filament\Company\Resources\Letters\LetterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLetter extends CreateRecord
{
    protected static string $resource = LetterResource::class;
}
