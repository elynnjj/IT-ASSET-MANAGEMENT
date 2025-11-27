<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageAssetController;
use App\Http\Controllers\DashboardController;

Route::redirect('/', '/login');

Route::get('dashboard', function () {
    $user = request()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'ITDept' => app(DashboardController::class)->index(),
        'Employee' => view('Employee.dashboard'),
        'HOD' => view('HOD.dashboard'),
        default => view('dashboard'),
    };
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes - role-based
Route::get('profile', function () {
    $user = request()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'ITDept' => view('ITDept.manageUser.profile'),
        'Employee' => view('Employee.manageUser.profile'),
        'HOD' => view('HOD.manageUser.profile'),
        default => view('profile'), // Backup profile page
    };
})
    ->middleware(['auth'])
    ->name('profile');

// Backup profile route
Route::view('profile-backup', 'profile')
    ->middleware(['auth'])
    ->name('profile.backup');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'itdept'])->group(function () {
    // Manage Users routes
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

    // Manage Assets routes
    Route::prefix('itdept/manage-assets')->name('itdept.manage-assets.')->group(function () {
		Route::get('/', [ManageAssetController::class, 'index'])->name('index');
		Route::get('/create', [ManageAssetController::class, 'create'])->name('create');
		Route::post('/', [ManageAssetController::class, 'store'])->name('store');
        Route::get('/template', [ManageAssetController::class, 'downloadTemplate'])->name('template');
        Route::post('/import', [ManageAssetController::class, 'importCsv'])->name('import');
		Route::get('/upload-invoice', [ManageAssetController::class, 'uploadInvoiceForm'])->name('upload-invoice');
		Route::post('/upload-invoice', [ManageAssetController::class, 'storeInvoice'])->name('store-invoice');
		Route::get('/api/assets-by-type', [ManageAssetController::class, 'getAssetsByType'])->name('api.assets-by-type');
		Route::get('/invoice/{invoiceID}/download', [ManageAssetController::class, 'downloadInvoice'])->name('invoice.download');
		Route::get('/{assetID}/checkout', [ManageAssetController::class, 'checkoutForm'])->name('checkout');
		Route::post('/{assetID}/checkout', [ManageAssetController::class, 'checkout'])->name('checkout.store');
		Route::patch('/{assetID}/checkin', [ManageAssetController::class, 'checkin'])->name('checkin');
		Route::get('/{assetID}/installed-software', [ManageAssetController::class, 'installedSoftwareForm'])->name('installed-software');
		Route::post('/{assetID}/installed-software', [ManageAssetController::class, 'storeInstalledSoftware'])->name('installed-software.store');
		Route::get('/{assetID}', [ManageAssetController::class, 'show'])->name('show');
		Route::get('/{assetID}/edit', [ManageAssetController::class, 'edit'])->name('edit');
		Route::put('/{assetID}', [ManageAssetController::class, 'update'])->name('update');
		Route::delete('/{assetID}', [ManageAssetController::class, 'destroy'])->name('destroy');
	});

    Route::get('/itdept/repairs-maintenance', function () {
        return view('ITDept.repairsAndMaintenance');
    })->name('itdept.repairs-maintenance');

    Route::get('/itdept/it-requests', function () {
        return view('ITDept.ITRequests');
    })->name('itdept.it-requests');

    Route::get('/itdept/asset-disposal', function () {
        return view('ITDept.manageDisposal.assetDisposal');
    })->name('itdept.asset-disposal');

    Route::get('/itdept/reports', function () {
        return view('ITDept.reports');
    })->name('itdept.reports');
});

// Employee routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/employee/submit-it-request', function () {
        return view('Employee.submitITRequests');
    })->name('employee.submit-it-request');

    Route::get('/employee/my-requests', function () {
        return view('Employee.myRequest');
    })->name('employee.my-requests');
});

// HOD routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/hod/approval-request', function () {
        return view('HOD.approvalRequest');
    })->name('hod.approval-request');

    Route::get('/hod/submit-it-request', function () {
        return view('HOD.submitITRequests');
    })->name('hod.submit-it-request');

    Route::get('/hod/my-requests', function () {
        return view('HOD.myRequest');
    })->name('hod.my-requests');
});
