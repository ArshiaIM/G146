<?php

namespace App\Filament\Company\Auth;

use Filament\Auth\Pages\Login as PagesLogin;
use Filament\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CompanyLogin extends PagesLogin
{
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['email'], // فیلد email رو به username مپ میکنیم
            'password' => $data['password'],
        ];
    }

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label('نام کاربری')
            ->placeholder('مثلاً: 46ark')
            ->required()
            ->autocomplete('username');
    }
}
