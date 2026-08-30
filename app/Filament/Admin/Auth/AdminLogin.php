<?php

namespace App\Filament\Admin\Auth;

use BackedEnum;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;

class AdminLogin extends BaseLogin
{
    public function form(Schema $form): Schema
    {
        return $form->schema([
            TextInput::make('email')
                ->label('نام کاربری')
                ->placeholder('admin')
                ->required()
                ->autocomplete('username'),

            TextInput::make('password')
                ->label('رمز عبور')
                ->password()
                ->revealable()
                ->required(),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (!Auth::attempt([
            'username' => $data['email'],
            'password' => $data['password'],
        ])) {
            Notification::make()
                ->title('نام کاربری یا رمز عبور اشتباه است')
                ->danger()
                ->send();

            return null;
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
