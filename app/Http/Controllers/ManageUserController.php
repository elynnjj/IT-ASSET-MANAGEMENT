<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewUserWelcomeNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ManageUserController
{

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

		// Generate temporary password
		$temporaryPassword = Str::random(12);

		$payload = $validated;
		$payload['password'] = $temporaryPassword;
		$payload['accStat'] = 'active';
		$payload['firstLogin'] = true;

		$user = User::create($payload);

		// Send welcome email with temporary password
		$user->notify(new NewUserWelcomeNotification($temporaryPassword));

		return redirect()->route('itdept.manage-users.index', ['role' => $validated['role']])
			->with('status', 'User created successfully. A welcome email with temporary password has been sent.');
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
			'password' => ['nullable', 'string', 'min:8'],
			'department' => ['required', 'string', 'max:255'],
			'role' => ['required', 'in:Employee,HOD'],
		]);

		if (!empty($validated['password'])) {
			$user->password = $validated['password'];
		}

		$user->fullName = $validated['fullName'];
		$user->email = $validated['email'];
		$user->department = $validated['department'] ?? null;
		$user->role = $validated['role'];
		$user->save();

		return redirect()->route('itdept.manage-users.index', ['role' => $validated['role']])
			->with('status', 'User updated');
	}

	public function destroy(string $userID): RedirectResponse
	{
		$user = User::where('userID', $userID)->firstOrFail();
		$user->delete();
		return back()->with('status', 'User deleted');
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

				// Generate temporary password
				$temporaryPassword = Str::random(12);

				$user = new User();
				$user->userID = $data['userID'];
				$user->fullName = $data['fullName'];
				$user->email = $data['email'];
				$user->password = $temporaryPassword;
				$user->department = $data['department'];
				$user->role = $data['role'];
				$user->accStat = 'active';
				$user->firstLogin = true;
				$user->save();

				// Send welcome email with temporary password
				$user->notify(new NewUserWelcomeNotification($temporaryPassword));

				$created++;
			} catch (\Throwable $e) {
				$errors++;
			}
		}

		fclose($handle);

		return back()->with('status', "Imported: $created created, $skipped skipped (existing), $errors errors. Welcome emails sent to new users.");
	}
}

