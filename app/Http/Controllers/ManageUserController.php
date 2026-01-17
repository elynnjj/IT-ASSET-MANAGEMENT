<?php

namespace App\Http\Controllers;

use App\Jobs\ImportUserJob;
use App\Models\User;
use App\Notifications\NewUserWelcomeNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ManageUserController
{
	/**
	 * Generate a secure temporary password that meets requirements:
	 * - Minimum 8 characters
	 * - At least one number or symbol
	 */
	private function generateSecureTemporaryPassword(): string
	{
		$uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$lowercase = 'abcdefghijklmnopqrstuvwxyz';
		$numbers = '0123456789';
		$symbols = '!@#$%^&*';
		
		// Ensure at least one number or symbol
		$password = '';
		$password .= $numbers[random_int(0, strlen($numbers) - 1)]; // At least one number
		$password .= $lowercase[random_int(0, strlen($lowercase) - 1)]; // Add lowercase for variety
		
		// Fill the rest randomly (minimum 8 chars total)
		$allChars = $uppercase . $lowercase . $numbers . $symbols;
		$remainingLength = 8 - strlen($password);
		
		for ($i = 0; $i < $remainingLength; $i++) {
			$password .= $allChars[random_int(0, strlen($allChars) - 1)];
		}
		
		// Shuffle to randomize position
		return str_shuffle($password);
	}

	public function index(Request $request): View
	{
		$role = $request->query('role');
		$search = $request->query('q');
		$department = $request->query('department');
		$status = $request->query('status');

		$allowedSorts = ['userID','fullName','email','department','accStat'];
		$sort = $request->query('sort');
		$dir = strtolower((string) $request->query('dir')) === 'desc' ? 'desc' : 'asc';
		if (!in_array($sort, $allowedSorts, true)) {
			$sort = 'userID';
		}

		$query = User::query()
			->when($role, function ($q) use ($role) {
				$q->where('role', $role);
			})
			->when($search, function ($q) use ($search) {
				$q->where(function ($qq) use ($search) {
					$qq->where('userID', 'like', "%{$search}%")
						->orWhere('fullName', 'like', "%{$search}%");
				});
			})
			->when($department, function ($q) use ($department) {
				$q->where('department', $department);
			})
			->when($status, function ($q) use ($status) {
				$q->where('accStat', $status);
			})
			->orderBy($sort, $dir);

		$users = $query->paginate(10)->withQueryString();

		return view('ITDept.manageUser.manageUser', [
			'users' => $users,
			'role' => $role,
			'sort' => $sort,
			'dir' => $dir,
			'q' => $search,
			'filterDepartment' => $department,
			'filterStatus' => $status,
		]);
	}

	public function create(): View
	{
		return view('ITDept.manageUser.addUser');
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'userID' => ['required', 'string', 'max:255', 'unique:users,userID'],
			'fullName' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			'department' => ['required', 'string', 'max:255'],
			'role' => ['required', 'in:Employee,HOD'],
		]);

		// Generate secure temporary password
		$temporaryPassword = $this->generateSecureTemporaryPassword();

		$payload = $validated;
		$payload['password'] = Hash::make($temporaryPassword);
		$payload['accStat'] = 'active';
		$payload['firstLogin'] = true;

		$user = User::create($payload);

		// Send welcome email with temporary password
		try {
		$user->notify(new NewUserWelcomeNotification($temporaryPassword));
		} catch (\Exception $e) {
			// Log email error but don't fail user creation
			Log::error('Failed to send welcome email to user: ' . $user->email, [
				'error' => $e->getMessage(),
				'userID' => $user->userID
			]);
		}

		return redirect()->route('itdept.manage-users.index', ['role' => $validated['role']])
			->with('status', '1 user successfully added');
	}

	public function edit(string $userID): View
	{
		$user = User::where('userID', $userID)->firstOrFail();
		return view('ITDept.manageUser.editUser', ['user' => $user]);
	}

	public function update(Request $request, string $userID): RedirectResponse
	{
		$user = User::where('userID', $userID)->firstOrFail();

		$validated = $request->validate([
			'fullName' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->userID.',userID'],
			'password' => [
				'nullable',
				'string',
				'min:8',
				'regex:/^(?=.*[0-9!@#$%^&*(),.?":{}|<>]).+$/',
			],
			'department' => ['required', 'string', 'max:255'],
			'role' => ['required', 'in:Employee,HOD'],
		], [
			'password.min' => 'The password must be at least 8 characters.',
			'password.regex' => 'The password must contain at least one number or symbol.',
		]);

		if (!empty($validated['password'])) {
			$user->password = Hash::make($validated['password']);
		}

		$user->fullName = $validated['fullName'];
		$user->email = $validated['email'];
		$user->department = $validated['department'] ?? null;
		$user->role = $validated['role'];
		$user->save();

		return redirect()->route('itdept.manage-users.index', ['role' => $validated['role']])
			->with('status', 'User details updated successfully');
	}

	public function destroy(string $userID): RedirectResponse
	{
		$user = User::where('userID', $userID)->firstOrFail();
		
		// Check if user has any currently assigned assets (not checked in)
		$hasAssignedAssets = $user->assignedAssets()->whereNull('checkinDate')->exists();
		
		if ($hasAssignedAssets) {
			return back()->withErrors(['delete' => 'Cannot delete user. There are still assets assigned to this user. Please check in all assets first.']);
		}
		
		$user->delete();
		return back()->with('status', 'User account deleted successfully');
	}

	public function deactivate(string $userID): RedirectResponse
	{
		$user = User::where('userID', $userID)->firstOrFail();
		$user->accStat = 'inactive';
		$user->save();
		return back()->with('status', 'User deactivated');
	}

	public function activate(string $userID): RedirectResponse
	{
		$user = User::where('userID', $userID)->firstOrFail();
		$user->accStat = 'active';
		$user->save();
		return back()->with('status', 'User activated');
	}

	public function downloadTemplate(): StreamedResponse
	{
		$headers = ['userID','fullName','email','department','role'];
		$filename = 'user_import_template.csv';

		return response()->streamDownload(function () use ($headers) {
			$output = fopen('php://output', 'w');
			fputcsv($output, $headers);
			// Example row (password will be auto-generated)
			fputcsv($output, ['jdoe','John Doe','john@example.com','IT','Employee']);
			fclose($output);
		}, $filename, [
			'Content-Type' => 'text/csv',
			'Content-Disposition' => 'attachment; filename="user_import_template.csv"',
		]);
	}

	public function importCsv(Request $request): RedirectResponse
	{
		$request->validate([
			'file' => ['required', 'file', 'mimes:csv,txt'],
		]);

		$path = $request->file('file')->getRealPath();
		$handle = fopen($path, 'r');
		if ($handle === false) {
			return back()->withErrors(['file' => 'Cannot open uploaded file']);
		}

		$header = fgetcsv($handle);
		if (!$header) {
			fclose($handle);
			return back()->withErrors(['file' => 'Empty CSV file']);
		}

		// Normalize headers (trim whitespace)
		$header = array_map('trim', $header);

		// Generate unique progress ID
		$progressId = uniqid('user_import_', true);

		// Read all rows and dispatch jobs
		$rows = [];
		while (($row = fgetcsv($handle)) !== false) {
			$rows[] = $row;
		}
		fclose($handle);

		$totalRows = count($rows);

		if ($totalRows === 0) {
			return back()->withErrors(['file' => 'No valid rows found in CSV file']);
		}

		// Initialize progress tracking
		Cache::put("import_progress_{$progressId}_total", $totalRows, 3600);
		Cache::put("import_progress_{$progressId}_processed", 0, 3600);
		Cache::put("import_progress_{$progressId}_created", 0, 3600);
		Cache::put("import_progress_{$progressId}_skipped", 0, 3600);
		Cache::put("import_progress_{$progressId}_errors", 0, 3600);
		Cache::put("import_progress_{$progressId}_emailsSent", 0, 3600);
		Cache::put("import_progress_{$progressId}_emailsFailed", 0, 3600);
		Cache::put("import_progress_{$progressId}_createdByRole_Employee", 0, 3600);
		Cache::put("import_progress_{$progressId}_createdByRole_HOD", 0, 3600);
		Cache::put("import_progress_{$progressId}_importedRoles", [], 3600);
		Cache::put("import_progress_{$progressId}_status", 'processing', 3600);

		// Dispatch jobs for each row - ensure they're queued, not processed synchronously
		foreach ($rows as $rowIndex => $row) {
			ImportUserJob::dispatch($progressId, $row, $header, $rowIndex, $totalRows)
				->onQueue('default');
		}

		// Ensure queue worker is running AFTER all jobs are dispatched
		\App\Helpers\QueueHelper::ensureQueueWorkerRunning();

		// Redirect with progress ID
		return redirect()->route('itdept.manage-users.index', ['progressId' => $progressId]);
	}

	public function checkImportProgress(Request $request): JsonResponse
	{
		$progressId = $request->query('progressId');

		if (!$progressId) {
			return response()->json(['error' => 'Progress ID required'], 400);
		}

		$total = Cache::get("import_progress_{$progressId}_total", 0);
		$processed = Cache::get("import_progress_{$progressId}_processed", 0);
		$created = Cache::get("import_progress_{$progressId}_created", 0);
		$skipped = Cache::get("import_progress_{$progressId}_skipped", 0);
		$errors = Cache::get("import_progress_{$progressId}_errors", 0);
		$emailsSent = Cache::get("import_progress_{$progressId}_emailsSent", 0);
		$emailsFailed = Cache::get("import_progress_{$progressId}_emailsFailed", 0);
		$createdByRoleEmployee = Cache::get("import_progress_{$progressId}_createdByRole_Employee", 0);
		$createdByRoleHOD = Cache::get("import_progress_{$progressId}_createdByRole_HOD", 0);
		$status = Cache::get("import_progress_{$progressId}_status", 'processing');
		$importedRoles = Cache::get("import_progress_{$progressId}_importedRoles", []);

		// Check queue status - count pending jobs for this import
		$pendingJobs = 0;
		$queueConnection = config('queue.default');
		try {
			if ($queueConnection === 'database') {
				$pendingJobs = DB::table('jobs')
					->where('payload', 'like', '%' . $progressId . '%')
					->count();
			}
		} catch (\Exception $e) {
			// Jobs table might not exist or queue might be sync
			Log::warning('Could not check pending jobs: ' . $e->getMessage());
		}

		// Check if all jobs are complete (processed equals total AND no pending jobs)
		// The last job increments 'processed' at the start, but 'created' at the end,
		// so we must ensure pendingJobs is 0 to wait for the last job to fully complete
		$isComplete = ($processed >= $total) && $total > 0 && $pendingJobs === 0;

		if ($isComplete && $status === 'processing') {
			// Re-read cache values one final time right before building the message
			// This ensures we capture the last job's 'created' counter updates
			$created = Cache::get("import_progress_{$progressId}_created", 0);
			$createdByRoleEmployee = Cache::get("import_progress_{$progressId}_createdByRole_Employee", 0);
			$createdByRoleHOD = Cache::get("import_progress_{$progressId}_createdByRole_HOD", 0);
			
			Log::info("Final cache read before completion - Employee: {$createdByRoleEmployee}, HOD: {$createdByRoleHOD}, Created: {$created}, Processed: {$processed}, Total: {$total}");
			// Build success message
			$message = '';
			$redirectRole = 'Employee';

			// Use the sum of role-specific counts to ensure accuracy
			$totalCreated = (int)$createdByRoleEmployee + (int)$createdByRoleHOD;
			
			// Use whichever is larger (role-specific sum or created counter) as fallback
			// This ensures we always show the correct count even if there's a slight timing issue
			$finalCount = max($totalCreated, (int)$created);
			$finalCount = $finalCount > 0 ? $finalCount : $totalCreated;

			if ($finalCount > 0) {
				// Combine all created users into one message
				$message = "$finalCount user(s) successfully added";
				
				// Log for debugging - show all cache values
				Log::info("User import completion - Employee: {$createdByRoleEmployee}, HOD: {$createdByRoleHOD}, Total created (cache): {$created}, Total created (sum): {$totalCreated}, Final count: {$finalCount}", [
					'progressId' => $progressId,
					'cacheKeys' => [
						'created' => Cache::get("import_progress_{$progressId}_created", 'not found'),
						'Employee' => Cache::get("import_progress_{$progressId}_createdByRole_Employee", 'not found'),
						'HOD' => Cache::get("import_progress_{$progressId}_createdByRole_HOD", 'not found'),
					]
				]);

				$unsuccessfulMessages = [];
				if ($skipped > 0) {
					$unsuccessfulMessages[] = "$skipped already exist";
				}
				if ($errors > 0) {
					$unsuccessfulMessages[] = "$errors unsuccessful";
				}
				if ($emailsFailed > 0) {
					$unsuccessfulMessages[] = "$emailsFailed email(s) failed to send";
				}

				if (!empty($unsuccessfulMessages)) {
					$message .= ". Unsuccessful: " . implode(", ", $unsuccessfulMessages);
				}

				$redirectRole = !empty($importedRoles) ? $importedRoles[0] : 'Employee';
			} else {
				$message = "No users were added. $skipped already exist, $errors errors.";
			}

			Cache::put("import_progress_{$progressId}_status", 'completed', 3600);
			Cache::put("import_progress_{$progressId}_message", $message, 3600);
			Cache::put("import_progress_{$progressId}_redirectRole", $redirectRole, 3600);
		}

		return response()->json([
			'total' => $total,
			'processed' => $processed,
			'created' => $created,
			'skipped' => $skipped,
			'errors' => $errors,
			'emailsSent' => $emailsSent,
			'emailsFailed' => $emailsFailed,
			'isComplete' => $isComplete,
			'status' => $status,
			'pendingJobs' => $pendingJobs,
			'message' => $isComplete ? Cache::get("import_progress_{$progressId}_message", '') : null,
			'redirectRole' => $isComplete ? Cache::get("import_progress_{$progressId}_redirectRole", 'Employee') : null,
		]);
	}
}

