<?php

use App\Livewire\Pages\Admin\AdminDashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\User\UserDashboard;
use App\Livewire\Pages\Patrol;
use App\Livewire\Pages\Admin\Employee;

Route::view('/', 'welcome');


Route::get('dashboard', UserDashboard::class)
    ->middleware(['auth', 'verified'])->name('dashboard');
Route::get('patrols', Patrol::class)
    ->middleware(['auth', 'verified'])->name('patrols');
Route::get('employees', Employee::class)
    ->middleware(['auth', 'verified'])->name('employees');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
});

// Route::get('admin/dashboard', AdminDashboard::class)
//     ->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route::get('test', \App\Livewire\Pages\Dashboard::class)->middleware(['auth']);

require __DIR__.'/auth.php';
