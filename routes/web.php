<?php

use App\Livewire\Dashboard\Index;
use App\Livewire\Employee\Create;
use App\Livewire\Employee\LeaveForm;
use App\Models\Employee\Employee;
// use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome');

Route::get('/dashboard', function () {

    $users = Employee::all();

    return view('dashboard', compact('users'));
})->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route::get('/home', Dashboard::class)->name('dashboard');
Route::prefix('employees')->group(function () {
    Route::get('/', Index::class)->name('employee.index');
    Route::get('/create', Create::class)->name('employee.create');
    Route::get('/edit/{employeeId}', Create::class)->name('employees.edit');
    Route::get('/leaves', LeaveForm::class)->name('employee.leaves');
});


require __DIR__ . '/auth.php';
