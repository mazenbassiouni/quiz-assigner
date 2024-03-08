<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Library\LibraryList;
use App\Livewire\Assignments\AssignmentsIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('library', LibraryList::class)
    ->middleware(['auth'])
    ->name('library');

Route::get('assignments', AssignmentsIndex::class)
    ->middleware(['auth'])
    ->name('assignments');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
