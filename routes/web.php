<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ITDept\ManageUserController;

Route::redirect('/', '/login');

Route::get('dashboard', function () {
    $user = request()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'ITDept' => view('ITDept.dashboard'),
        'Employee' => view('Employee.dashboard'),
        'HOD' => view('HOD.dashboard'),
        default => view('dashboard'),
    };
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'itdept'])->group(function () {
    Route::prefix('itdept/manage-users')->name('itdept.manage-users.')->group(function () {
		Route::get('/', [ManageUserController::class, 'index'])->name('index');
		Route::get('/create', [ManageUserController::class, 'create'])->name('create');
		Route::post('/', [ManageUserController::class, 'store'])->name('store');
        Route::get('/template', [ManageUserController::class, 'downloadTemplate'])->name('template');
        Route::post('/import', [ManageUserController::class, 'importCsv'])->name('import');
		Route::get('/{userID}/edit', [ManageUserController::class, 'edit'])->name('edit');
		Route::put('/{userID}', [ManageUserController::class, 'update'])->name('update');
		Route::delete('/{userID}', [ManageUserController::class, 'destroy'])->name('destroy');
		Route::patch('/{userID}/deactivate', [ManageUserController::class, 'deactivate'])->name('deactivate');
	});
});
