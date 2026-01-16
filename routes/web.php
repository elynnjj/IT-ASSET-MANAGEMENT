<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageAssetController;
use App\Http\Controllers\DisposalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ITRequestsController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Mail;

Route::redirect('/', '/login');

Route::get('dashboard', function () {
    $user = request()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'ITDept' => app(DashboardController::class)->index(),
        'Employee' => app(DashboardController::class)->employeeDashboard(),
        'HOD' => app(DashboardController::class)->hodDashboard(),
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
		Route::patch('/{userID}/activate', [ManageUserController::class, 'activate'])->name('activate');
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
		Route::get('/api/next-asset-id', [ManageAssetController::class, 'getNextAssetID'])->name('api.next-asset-id');
		Route::get('/invoice/{invoiceID}/view', [ManageAssetController::class, 'viewInvoice'])->name('invoice.view');
		Route::get('/invoice/{invoiceID}/download', [ManageAssetController::class, 'downloadInvoice'])->name('invoice.download');
		Route::get('/{assetID}/checkout', [ManageAssetController::class, 'checkoutForm'])->name('checkout');
		Route::post('/{assetID}/checkout', [ManageAssetController::class, 'checkout'])->name('checkout.store');
		Route::patch('/{assetID}/checkin', [ManageAssetController::class, 'checkin'])->name('checkin');
		Route::get('/{assetID}/agreement/view', [ManageAssetController::class, 'viewAgreement'])->name('agreement.view');
		Route::get('/{assetID}/agreement', [ManageAssetController::class, 'downloadAgreement'])->name('agreement');
		Route::patch('/{assetID}/dispose', [DisposalController::class, 'dispose'])->name('dispose');
		Route::get('/{assetID}/installed-software', [ManageAssetController::class, 'installedSoftwareForm'])->name('installed-software');
		Route::post('/{assetID}/installed-software', [ManageAssetController::class, 'storeInstalledSoftware'])->name('installed-software.store');
		Route::get('/{assetID}', [ManageAssetController::class, 'show'])->name('show');
		Route::get('/{assetID}/edit', [ManageAssetController::class, 'edit'])->name('edit');
		Route::put('/{assetID}', [ManageAssetController::class, 'update'])->name('update');
		Route::delete('/{assetID}', [ManageAssetController::class, 'destroy'])->name('destroy');
	});

    Route::get('/itdept/repairs-maintenance', [ITRequestsController::class, 'repairsAndMaintenance'])->name('itdept.repairs-maintenance');
    Route::get('/itdept/new-maintenance', [ITRequestsController::class, 'createMaintenance'])->name('itdept.new-maintenance');
    Route::post('/itdept/store-maintenance', [ITRequestsController::class, 'storeMaintenanceWithoutRequest'])->name('itdept.store-maintenance');
    Route::get('/itdept/maintenance/assets-by-type', [ITRequestsController::class, 'getAssetsByTypeForMaintenance'])->name('itdept.maintenance.assets-by-type');

    Route::get('/itdept/it-requests', [ITRequestsController::class, 'indexForITDept'])->name('itdept.it-requests');
    Route::get('/itdept/it-requests/{requestID}', [ITRequestsController::class, 'showForITDept'])->name('itdept.it-requests.show');
    Route::post('/itdept/it-requests/{requestID}/maintenance', [ITRequestsController::class, 'storeMaintenance'])->name('itdept.it-requests.maintenance');

    Route::get('/itdept/asset-disposal', [DisposalController::class, 'index'])->name('itdept.asset-disposal');
    Route::post('/itdept/asset-disposal/bulk-dispose', [DisposalController::class, 'bulkDispose'])->name('itdept.asset-disposal.bulk-dispose');
    Route::get('/itdept/asset-disposal/{disposeID}/download-invoice', [DisposalController::class, 'downloadInvoice'])->name('itdept.asset-disposal.download-invoice');

    Route::get('/itdept/reports', [ReportController::class, 'index'])->name('itdept.reports');
    Route::post('/itdept/reports/generate', [ReportController::class, 'generateReport'])->name('itdept.reports.generate');
});

// Employee routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/employee/submit-it-request', [ITRequestsController::class, 'createForEmployee'])->name('employee.submit-it-request');
    Route::post('/employee/it-requests', [ITRequestsController::class, 'storeForEmployee'])->name('employee.it-requests.store');

    Route::get('/employee/my-requests', [ITRequestsController::class, 'myRequestsForEmployee'])->name('employee.my-requests');
    Route::delete('/employee/it-requests/{requestID}', [ITRequestsController::class, 'destroy'])->name('employee.it-requests.destroy');
});

// HOD routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/hod/approval-request', [ITRequestsController::class, 'approvalRequestForHOD'])->name('hod.approval-request');
    Route::post('/hod/it-requests/{requestID}/approve', [ITRequestsController::class, 'approve'])->name('hod.it-requests.approve');
    Route::post('/hod/it-requests/{requestID}/reject', [ITRequestsController::class, 'reject'])->name('hod.it-requests.reject');

    Route::get('/hod/submit-it-request', [ITRequestsController::class, 'createForHOD'])->name('hod.submit-it-request');
    Route::post('/hod/it-requests', [ITRequestsController::class, 'storeForHOD'])->name('hod.it-requests.store');

    Route::get('/hod/my-requests', [ITRequestsController::class, 'myRequestsForHOD'])->name('hod.my-requests');
    Route::delete('/hod/it-requests/{requestID}', [ITRequestsController::class, 'destroy'])->name('hod.it-requests.destroy');
});

Route::get('/test-email', function () {
    Mail::raw('This email is sent using Brevo SMTP from Railway.', function ($message) {
        $message->to('your_personal_email@gmail.com')
                ->subject('Brevo Email Test');
    });

    return 'Email sent successfully!';
});
