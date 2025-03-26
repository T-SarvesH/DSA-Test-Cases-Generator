<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestCaseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

Route::get('/leetcode-form', [GeminiController::class, 'showForm'])->name('gemini.form');
Route::post('/generate-descTitle', [GeminiController::class, 'scrapDesctitle'])->name('generate.descTitle');
Route::get('/codeforces-form', [GeminiController::class, 'codeforcedForm'])->name('codeforces.form');
#For Description generation


// Route::get('/test-cases', [TestCaseController::class, 'index'])->name('test_cases.index');
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', [TestCaseController::class, 'index'])->name('test_cases.index');
    Route::delete('/', [TestCaseController::class, 'destroy'])->name('test_cases.destroy');
});

require __DIR__.'/auth.php';
