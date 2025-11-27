<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
			'password' => ['required', 'string', 'min:8'],
			'department' => ['required', 'string', 'max:255'],
			'role' => ['required', 'in:Employee,HOD'],
		]);

		$payload = $validated;
		$payload['accStat'] = 'active';

		User::create($payload);

		return redirect()->route('itdept.manage-users.index', ['role' => $validated['role']])
			->with('status', 'User created');
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

	public function downloadTemplate(): StreamedResponse
	{
		$headers = ['userID','fullName','email','password','department','role'];
		$filename = 'user_import_template.csv';

		return response()->streamDownload(function () use ($headers) {
			$output = fopen('php://output', 'w');
			fputcsv($output, $headers);
			// Example row
			fputcsv($output, ['jdoe','John Doe','john@example.com','Password123','IT','Employee']);
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

		$created = 0; $updated = 0; $skipped = 0; $errors = 0;

		while (($row = fgetcsv($handle)) !== false) {
			$data = array_combine($header, $row);
			if (!$data) { $skipped++; continue; }

			// Basic per-row validation
			if (!isset($data['userID'],$data['fullName'],$data['email'],$data['password'],$data['department'],$data['role'])) {
				$skipped++; continue;
			}
			if (!in_array($data['role'], ['Employee','HOD'], true)) { $skipped++; continue; }

			try {
				// Skip existing users entirely (do not update)
				if (User::where('userID', $data['userID'])->exists()) {
					$skipped++;
					continue;
				}

				$user = new User();
				$user->userID = $data['userID'];
				$user->fullName = $data['fullName'];
				$user->email = $data['email'];
				$user->password = $data['password'];
				$user->department = $data['department'];
				$user->role = $data['role'];
				$user->accStat = 'active';
				$user->save();
				$created++;
			} catch (\Throwable $e) {
				$errors++;
			}
		}

		fclose($handle);

		return back()->with('status', "Imported: $created created, $skipped skipped (existing), $errors errors");
	}
}

