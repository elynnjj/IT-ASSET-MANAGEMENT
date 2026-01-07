<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>User Report</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body {
			font-family: Arial, sans-serif;
			font-size: 10px;
			color: #333;
			margin: 20mm 15mm 20mm 15mm;
		}
		.header {
			margin-bottom: 20px;
			padding-bottom: 15px;
			border-bottom: 2px solid #4BA9C2;
		}
		.header-logo {
			width: 100%;
			text-align: center;
			margin-bottom: 10px;
		}
		.header-logo img {
			height: 60px;
			width: auto;
		}
		.header-title {
			text-align: center;
			margin-bottom: 10px;
		}
		.header-title h1 {
			font-size: 18px;
			color: #000000;
			font-weight: bold;
			margin: 0;
		}
		.header-right {
			text-align: right;
			font-size: 9px;
			color: #333;
		}
		.header-right p {
			margin: 3px 0;
		}
		.total-users {
			font-weight: bold;
			font-size: 10px;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 10px;
		}
		th {
			background-color: #4BA9C2;
			color: white;
			padding: 8px 5px;
			text-align: left;
			font-size: 9px;
			font-weight: bold;
			border: 1px solid #3a8ba5;
		}
		td {
			padding: 6px 5px;
			border: 1px solid #ddd;
			font-size: 8px;
			vertical-align: top;
		}
		tr:nth-child(even) {
			background-color: #f9f9f9;
		}
		.role-hod {
			color: #8b5cf6;
			font-weight: bold;
		}
		.role-employee {
			color: #3b82f6;
			font-weight: bold;
		}
		.page-break {
			page-break-after: always;
		}
		@page {
			margin: 20mm 15mm;
		}
	</style>
</head>
<body>
	<div class="header">
		<div class="header-logo">
			<img src="{{ public_path('images/exact2.png') }}" alt="Logo" style="max-height: 60px;">
		</div>
		<div class="header-title">
			<h1>ExactAsset - User Report</h1>
		</div>
		<div class="header-right">
			<p class="total-users">Total Users: {{ $users->count() }}</p>
			<p>Generated on: {{ $generatedAt }}</p>
		</div>
	</div>

	<table>
		<thead>
			<tr>
				<th style="width: 14%;">User ID</th>
				<th style="width: 22%;">Full Name</th>
				<th style="width: 22%;">Email</th>
				<th style="width: 17%;">Department</th>
				<th style="width: 14%;">Role</th>
				<th style="width: 14%;">Account Status</th>
			</tr>
		</thead>
		<tbody>
			@forelse($users as $user)
				<tr>
					<td>{{ $user->userID }}</td>
					<td>{{ $user->fullName }}</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->department ?? '-' }}</td>
					<td class="role-{{ strtolower($user->role) }}">{{ $user->role }}</td>
					<td style="color: {{ $user->accStat === 'active' ? '#10b981' : '#ef4444' }}; font-weight: bold;">
						{{ ucfirst($user->accStat ?? 'Active') }}
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="6" style="text-align: center; padding: 20px;">No users found matching the selected filters.</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</body>
</html>
