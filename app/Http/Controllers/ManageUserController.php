<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewUserWelcomeNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

		$created = 0; $skipped = 0; $errors = 0;
		$importedRoles = []; // Track which roles were imported
		$usersToNotify = []; // Store users and passwords for notification after transaction

		// Use database transaction for better performance and data integrity
		DB::beginTransaction();
		
		try {
			while (($row = fgetcsv($handle)) !== false) {
				$data = array_combine($header, $row);
				if (!$data) { $skipped++; continue; }

				// Trim all values
				$data = array_map('trim', $data);

				// Basic per-row validation (password no longer required)
				if (!isset($data['userID'],$data['fullName'],$data['email'],$data['department'],$data['role'])) {
					$skipped++; continue;
				}
				if (!in_array($data['role'], ['Employee','HOD'], true)) { $skipped++; continue; }

				try {
					// Skip existing users entirely (do not update)
					if (User::where('userID', $data['userID'])->exists()) {
						$skipped++;
						continue;
					}

					// Generate secure temporary password
					$temporaryPassword = $this->generateSecureTemporaryPassword();

					$user = new User();
					$user->userID = $data['userID'];
					$user->fullName = $data['fullName'];
					$user->email = $data['email'];
					$user->password = Hash::make($temporaryPassword);
					$user->department = $data['department'];
					$user->role = $data['role'];
					$user->accStat = 'active';
					$user->firstLogin = true;
					$user->save();

					// Store user and password for notification after transaction
					$usersToNotify[] = ['user' => $user, 'password' => $temporaryPassword];

					$created++;
					
					// Track role for redirect
					if (!in_array($data['role'], $importedRoles)) {
						$importedRoles[] = $data['role'];
					}
				} catch (\Throwable $e) {
					$errors++;
				}
			}

			// Commit transaction if all users were created successfully
			DB::commit();

			// Send email notifications after transaction is committed
			// Emails are sent synchronously to ensure users receive their temporary passwords
			foreach ($usersToNotify as $notificationData) {
				try {
					$notificationData['user']->notify(new NewUserWelcomeNotification($notificationData['password']));
				} catch (\Exception $e) {
					// Log email error but don't fail user creation
					Log::error('Failed to send welcome email to user: ' . $notificationData['user']->email, [
						'error' => $e->getMessage(),
						'userID' => $notificationData['user']->userID
					]);
				}
			}

		} catch (\Throwable $e) {
			// Rollback transaction on any error
			DB::rollBack();
			fclose($handle);
			return back()->withErrors(['file' => 'An error occurred during import. No users were added. Please try again.']);
		}

		fclose($handle);

		// Build status message similar to asset import
		if ($created > 0) {
			$message = "$created user(s) successfully added";
			
			$unsuccessfulMessages = [];
			if ($skipped > 0) {
				$unsuccessfulMessages[] = "$skipped already exist";
			}
			if ($errors > 0) {
				$unsuccessfulMessages[] = "$errors unsuccessful";
			}
			
			if (!empty($unsuccessfulMessages)) {
				$message .= ". " . implode(", ", $unsuccessfulMessages);
			}
			
			// Determine which role to show (prefer first imported role, or default to Employee)
			$redirectRole = !empty($importedRoles) ? $importedRoles[0] : 'Employee';
			
			return redirect()->route('itdept.manage-users.index', ['role' => $redirectRole])
				->with('status', $message);
		} else {
			// No users were created, show error message on add user page
			return back()->withErrors(['file' => "No users were added. $skipped already exist, $errors errors."]);
		}
	}
}

