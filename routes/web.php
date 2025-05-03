<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestCaseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\LeetcodeSave;

Route::get('/leetcode-form', [GeminiController::class, 'showForm'])->name('gemini.form');
Route::post('/generate-descTitle', [GeminiController::class, 'scrapDesctitle'])->name('generate.descTitle');
Route::get('/codeforces-form', [GeminiController::class, 'codeforcedForm'])->name('codeforces.form');
Route::post('generate-test-cases', [GeminiController::class, 'generateTestCases'])->name('generate.test_cases');
Route::post('/save-LC-test-cases', [LeetcodeSave::class, 'storeTestCases'])->name('save.LC.test_cases');
Route::get('/display-LC-test-cases', [LeetcodeSave::class, 'displayTestCases'])->name('display.LC.test_cases');

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
