<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Asset Inventory Report</title>
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
		.total-assets {
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
		.status-available {
			color: #10b981;
			font-weight: bold;
		}
		.status-checked-out {
			color: #3b82f6;
			font-weight: bold;
		}
		.status-disposed {
			color: #ef4444;
			font-weight: bold;
		}
		.footer {
			margin-top: 20px;
			padding-top: 10px;
			border-top: 1px solid #ddd;
			text-align: center;
			font-size: 8px;
			color: #666;
		}
		.page-break {
			page-break-after: always;
		}
	</style>
</head>
<body>
	<div class="header">
		<div class="header-logo">
			<img src="{{ public_path('images/exact2.png') }}" alt="Logo" style="max-height: 60px;">
		</div>
		<div class="header-title">
			<h1>ExactAsset - Asset Inventory Report</h1>
		</div>
		<div class="header-right">
			<p class="total-assets">Total Assets: {{ $assets->count() }}</p>
			<p>Generated on: {{ $generatedAt }}</p>
		</div>
	</div>

	<table>
		<thead>
			<tr>
				<th style="width: 10%;">Asset ID</th>
				<th style="width: 12%;">Serial Number</th>
				<th style="width: 12%;">Model</th>
				<th style="width: 10%;">RAM</th>
				<th style="width: 10%;">Storage</th>
				<th style="width: 10%;">Processor</th>
				<th style="width: 10%;">OS Version</th>
				<th style="width: 10%;">Purchase Date</th>
				<th style="width: 10%;">Status</th>
				<th style="width: 12%;">Current User</th>
			</tr>
		</thead>
		<tbody>
			@forelse($assets as $asset)
				@php
					$currentAssignment = $asset->currentAssignment();
					$currentUser = $currentAssignment ? $currentAssignment->user->fullName : 'With IT';
					
					// Determine status display
					$hasPendingDisposal = $asset->disposals()->where('dispStatus', 'Pending')->exists();
					$hasDisposed = $asset->disposals()->where('dispStatus', 'Disposed')->exists();
					
					if ($hasDisposed || $asset->status === 'Disposed') {
						$statusDisplay = 'Disposed';
						$statusClass = 'status-disposed';
					} elseif ($hasPendingDisposal) {
						$statusDisplay = 'Pending Dispose';
						$statusClass = 'status-disposed';
					} elseif ($asset->status === 'Checked Out' || $currentAssignment) {
						$statusDisplay = 'Checked Out';
						$statusClass = 'status-checked-out';
					} else {
						$statusDisplay = 'Available';
						$statusClass = 'status-available';
					}
				@endphp
				<tr>
					<td>{{ $asset->assetID }}</td>
					<td>{{ $asset->serialNum ?? '-' }}</td>
					<td>{{ $asset->model ?? '-' }}</td>
					<td>{{ $asset->ram ?? '-' }}</td>
					<td>{{ $asset->storage ?? '-' }}</td>
					<td>{{ $asset->processor ?? '-' }}</td>
					<td>{{ $asset->osVer ?? '-' }}</td>
					<td>{{ $asset->purchaseDate ? $asset->purchaseDate->format('d/m/Y') : '-' }}</td>
					<td class="{{ $statusClass }}">{{ $statusDisplay }}</td>
					<td>{{ $currentUser }}</td>
				</tr>
			@empty
				<tr>
					<td colspan="10" style="text-align: center; padding: 20px;">No assets found matching the selected filters.</td>
				</tr>
			@endforelse
		</tbody>
	</table>

	<div class="footer">
		<p>Page 1</p>
	</div>
</body>
</html>
