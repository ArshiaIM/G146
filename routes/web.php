<?php

use App\Livewire\Employee\LeaveForm;
use Illuminate\Support\Facades\Route;
use App\Livewire\Employee\Dashboard;
use App\Models\Employee\Employee;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route::get('/home', Dashboard::class)->name('dashboard');
// Route::get('/employee', Employee::class)->name('employee');
Route::get('/personnel/leaves', LeaveForm::class)->name('personnel.leaves');

require __DIR__.'/auth.php';
