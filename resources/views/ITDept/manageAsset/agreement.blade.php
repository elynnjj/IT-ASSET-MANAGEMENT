<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Asset Agreement</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body {
			font-family: Arial, sans-serif;
			font-size: 14px;
			color: #000;
			margin: 20mm 15mm 20mm 15mm;
			line-height: 1.8;
		}
		.header {
			text-align: center;
			margin-bottom: 30px;
		}
		.header-logo {
			margin-bottom: 20px;
		}
		.header-logo img {
			height: 90px;
			width: auto;
		}
		.header-title {
			font-size: 18px;
			font-weight: bold;
			margin-bottom: 30px;
			text-transform: uppercase;
		}
		.form-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 30px;
			border: 2px solid #000;
		}
		.form-table tr {
			border-bottom: 1px solid #ddd;
		}
		.form-table td {
			padding: 12px 10px;
			vertical-align: top;
			font-size: 14px;
		}
		.form-table td:first-child {
			font-weight: bold;
			width: 30%;
			padding-right: 15px;
		}
		.form-table td:last-child {
			width: 70%;
			padding-left: 15px;
			min-height: 25px;
		}
		.equipment-section {
			margin-top: 5px;
		}
		.equipment-item {
			display: block;
			margin-bottom: 8px;
		}
		.other-items {
			min-height: 50px;
		}
		.software-list {
			margin-top: 5px;
		}
		.signature-section {
			margin-top: 50px;
			display: table;
			width: 100%;
			table-layout: fixed;
		}
		.signature-box {
			display: table-cell;
			width: 50%;
			vertical-align: top;
		}
		.signature-box:first-child {
			padding-right: 20px;
		}
		.signature-box:last-child {
			padding-left: 20px;
		}
		.signature-line {
			border-top: 1px dashed #000;
			margin-top: 60px;
			padding-top: 5px;
			text-align: center;
			font-size: 12px;
			font-weight: bold;
			text-transform: uppercase;
		}
	</style>
</head>
<body>
	<div class="header">
		<div class="header-logo">
			<img src="{{ public_path('images/exact.jpg') }}" alt="Exact Logo">
		</div>
		<div class="header-title">
			ACKNOWLEDGEMENT RECEIPT OF COMPANY PROPERTIES & POLICY
		</div>
	</div>

	<table class="form-table">
		<tr>
			<td>Company:</td>
			<td>Exact Automation Sdn Bhd</td>
		</tr>
		<tr>
			<td>Date:</td>
			<td>{{ $checkoutDate ? \Carbon\Carbon::parse($checkoutDate)->format('d/m/Y') : '-' }}</td>
		</tr>
		<tr>
			<td>Received By Name:</td>
			<td>{{ $user->fullName ?? '-' }}</td>
		</tr>
		<tr>
			<td>Received From Name:</td>
			<td>IT Department Manager</td>
		</tr>
		<tr>
			<td>For Department:</td>
			<td>{{ $user->department ?? '-' }}</td>
		</tr>
		<tr>
			<td>Serial Number & Asset ID:</td>
			<td>{{ ($asset->serialNum ? $asset->serialNum . ' / ' : '') . $asset->assetID }}</td>
		</tr>
		<tr>
			<td>Make & Model:</td>
			<td>{{ ($asset->model ?? '-') . ($asset->processor ? ' / ' . $asset->processor : '') }}</td>
		</tr>
		<tr>
			<td>Equipment Included:</td>
			<td>
				<div class="equipment-section">
					<div class="equipment-item">Bag: Y / N</div>
					<div class="equipment-item">Power Supply: Y / N</div>
					<div class="equipment-item">Mouse: Y / N</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>Other Items:</td>
			<td>
				<div class="other-items">
					<br><br>
				</div>
			</td>
		</tr>
		<tr>
			<td>Installed Software:</td>
			<td>
				@if(count($softwareList) > 0)
					<div class="software-list">
						{{ implode(', ', $softwareList) }}
					</div>
				@else
					-
				@endif
			</td>
		</tr>
	</table>

	<div class="signature-section">
		<div class="signature-box">
			<div class="signature-line">
				EMPLOYEE SIGNATURE/DATE
			</div>
		</div>
		<div class="signature-box">
			<div class="signature-line">
				IT DEPT SIGNATURE/DATE
			</div>
		</div>
	</div>
</body>
</html>

