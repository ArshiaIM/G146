<?php

use App\Livewire\Dashboard\Index;
use App\Livewire\Employee\Create;
use App\Livewire\Employee\LeaveForm;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route::get('/home', Dashboard::class)->name('dashboard');
Route::get('/employee', Index::class)->name('employee');
Route::get('employee/create',Create::class)->name('employee.store');
Route::get('/employee/leaves', LeaveForm::class)->name('employee.leaves');

require __DIR__.'/auth.php';
