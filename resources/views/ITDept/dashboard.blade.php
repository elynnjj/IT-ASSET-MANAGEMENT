<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Dashboard') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- KPI Cards Row --}}
			<div class="flex flex-nowrap gap-4 mb-6">
				{{-- Total Active Assets --}}
				<div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Active Assets</p>
							<p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $totalActiveAssets }}</p>
						</div>
						<div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
							</svg>
						</div>
					</div>
				</div>

				{{-- Asset Checked-Out --}}
				<div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400">Asset Checked-Out</p>
							<p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $assetCheckedOut }}</p>
						</div>
						<div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
							</svg>
						</div>
					</div>
				</div>

				{{-- Asset Disposed --}}
				<div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400">Asset Disposed</p>
							<p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $assetDisposed }}</p>
						</div>
						<div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
							</svg>
						</div>
					</div>
				</div>

				{{-- Total Active Users --}}
				<div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Active Users</p>
							<p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $totalActiveUsers }}</p>
						</div>
						<div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
							</svg>
						</div>
					</div>
				</div>
			</div>

			{{-- Main Content Grid --}}
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
				{{-- Left Column --}}
				<div class="space-y-6">
					{{-- Asset Status Pie Chart --}}
					<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Asset Status</h3>
						<div class="flex justify-center items-center" style="height: 300px;">
							<canvas id="assetStatusChart"></canvas>
						</div>
						<div class="mt-4 flex justify-center gap-4 flex-wrap">
							@foreach($statusData as $status => $count)
								<div class="flex items-center gap-2">
									@if($status === 'Available')
										<div class="w-4 h-4 rounded-full" style="background-color: #90CAF9;"></div>
									@elseif($status === 'Checked Out')
										<div class="w-4 h-4 rounded-full" style="background-color: #1976D2;"></div>
									@else
										<div class="w-4 h-4 rounded-full" style="background-color: #FF9800;"></div>
									@endif
									<span class="text-sm text-gray-600 dark:text-gray-400">{{ $status }}: {{ $count }}</span>
								</div>
							@endforeach
						</div>
					</div>

					{{-- IT Requests Section --}}
					<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">IT Requests</h3>
							<a href="#" class="text-sm font-medium" style="color: #4BA9C2;">Show all</a>
						</div>
						<div class="text-center py-8 text-gray-500 dark:text-gray-400">
							<p>No IT requests available</p>
						</div>
					</div>
				</div>

				{{-- Right Column - Calendar --}}
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Calendar</h3>
					<div id="calendarHeader" class="mb-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300"></div>
					<div id="calendar" class="relative"></div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Asset Status Pie Chart
			const ctx = document.getElementById('assetStatusChart');
			if (ctx) {
				const assetStatusChart = new Chart(ctx.getContext('2d'), {
					type: 'pie',
					data: {
						labels: ['Available', 'Checked Out', 'Disposed'],
						datasets: [{
							data: [
								{{ $statusData['Available'] ?? 0 }},
								{{ $statusData['Checked Out'] ?? 0 }},
								{{ $statusData['Disposed'] ?? 0 }}
							],
							backgroundColor: [
								'#90CAF9', // Light blue
								'#1976D2', // Dark blue
								'#FF9800'  // Orange
							],
							borderWidth: 0
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: {
							legend: {
								display: false
							}
						}
					}
				});
			}

			// Calendar with events
			function renderCalendar() {
				const calendarEl = document.getElementById('calendar');
				const calendarHeader = document.getElementById('calendarHeader');
				if (!calendarEl) return;
				
				const today = new Date();
				const currentMonth = today.getMonth();
				const currentYear = today.getFullYear();
				const todayDate = today.getDate();
				
				// Get calendar events from backend
				const calendarEvents = @json($calendarEvents ?? []);
				
				// Month names
				const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December'];
				
				// Display month and year
				if (calendarHeader) {
					calendarHeader.textContent = monthNames[currentMonth] + ' ' + currentYear;
				}
				
				const firstDay = new Date(currentYear, currentMonth, 1);
				const lastDay = new Date(currentYear, currentMonth + 1, 0);
				const daysInMonth = lastDay.getDate();
				const startingDayOfWeek = firstDay.getDay();
				
				// Helper function to format date as YYYY-MM-DD
				function formatDate(year, month, day) {
					const monthStr = String(month + 1).padStart(2, '0');
					const dayStr = String(day).padStart(2, '0');
					return year + '-' + monthStr + '-' + dayStr;
				}
				
				let calendarHTML = '<table class="w-full border-collapse">';
				
				// Day headers
				calendarHTML += '<thead><tr>';
				const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
				dayHeaders.forEach(day => {
					calendarHTML += '<th class="border border-gray-300 dark:border-gray-600 p-2 text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 text-center">' + day + '</th>';
				});
				calendarHTML += '</tr></thead><tbody>';
				
				// Calculate how many weeks we need
				const totalCells = startingDayOfWeek + daysInMonth;
				const weeksNeeded = Math.ceil(totalCells / 7);
				
				// Calendar rows
				let currentDay = 1;
				for (let week = 0; week < weeksNeeded; week++) {
					calendarHTML += '<tr>';
					for (let dayOfWeek = 0; dayOfWeek < 7; dayOfWeek++) {
						if ((week === 0 && dayOfWeek < startingDayOfWeek) || currentDay > daysInMonth) {
							// Empty cell
							calendarHTML += '<td class="border border-gray-300 dark:border-gray-600 p-2 h-20 w-20 bg-gray-50 dark:bg-gray-900"></td>';
						} else {
							const isToday = currentDay === todayDate && currentMonth === today.getMonth() && currentYear === today.getFullYear();
							
							// Check for events on this date
							const dateKey = formatDate(currentYear, currentMonth, currentDay);
							const dayEvents = calendarEvents[dateKey] || [];
							const hasEvent = dayEvents.length > 0;
							
							// Determine cell classes
							let cellClasses = 'border border-gray-300 dark:border-gray-600 p-2 h-20 w-20 align-top relative';
							if (isToday) {
								cellClasses += ' bg-gray-200 dark:bg-gray-600 border-gray-400 dark:border-gray-500';
							} else {
								cellClasses += ' bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700';
							}
							
							// Highlight cells with events
							if (hasEvent && !isToday) {
								cellClasses += ' bg-blue-50 dark:bg-blue-900/30';
							}
							
							calendarHTML += '<td class="' + cellClasses + '">';
							
							// Day number
							let dayClasses = 'text-sm font-medium mb-1';
							if (isToday) {
								dayClasses += ' text-gray-900 dark:text-gray-100 font-bold';
							} else {
								dayClasses += ' text-gray-700 dark:text-gray-300';
							}
							calendarHTML += '<div class="' + dayClasses + '">' + currentDay + '</div>';
							
							// Add event indicators
							if (hasEvent) {
								let eventHTML = '<div class="absolute bottom-2 left-1 right-1 flex gap-1 flex-wrap">';
								dayEvents.forEach(function(eventType) {
									let color = '#90CAF9'; // Default blue
									if (eventType === 'checkout') {
										color = '#4BA9C2'; // Blue for checkout
									} else if (eventType === 'checkin') {
										color = '#10B981'; // Green for checkin
									} else if (eventType === 'disposal') {
										color = '#EF4444'; // Red for disposal
									}
									eventHTML += '<div class="h-2 flex-1 rounded" style="background-color: ' + color + '; min-width: 20px;"></div>';
								});
								eventHTML += '</div>';
								calendarHTML += eventHTML;
							}
							
							calendarHTML += '</td>';
							currentDay++;
						}
					}
					calendarHTML += '</tr>';
				}
				
				calendarHTML += '</tbody></table>';
				calendarEl.innerHTML = calendarHTML;
			}
			
			renderCalendar();
		});
	</script>
</x-app-layout>
