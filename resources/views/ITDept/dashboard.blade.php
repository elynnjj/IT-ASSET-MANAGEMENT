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
				<div class="kpi-card flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0 transition-all duration-300 ease-out cursor-pointer group">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400 transition-colors duration-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">Total Active Assets</p>
							<p class="kpi-number text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 transition-all duration-300 group-hover:scale-110 group-hover:text-blue-600 dark:group-hover:text-blue-400 inline-block" data-target="{{ $totalActiveAssets }}">0</p>
						</div>
						<div class="kpi-icon w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-6" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6 transition-colors duration-300" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
							</svg>
						</div>
					</div>
				</div>

				{{-- Asset Checked-Out --}}
				<div class="kpi-card flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0 transition-all duration-300 ease-out cursor-pointer group">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400 transition-colors duration-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">Asset Checked-Out</p>
							<p class="kpi-number text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 transition-all duration-300 group-hover:scale-110 group-hover:text-blue-600 dark:group-hover:text-blue-400 inline-block" data-target="{{ $assetCheckedOut }}">0</p>
						</div>
						<div class="kpi-icon w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-6" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6 transition-colors duration-300" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
							</svg>
						</div>
					</div>
				</div>

				{{-- Asset Disposed --}}
				<div class="kpi-card flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0 transition-all duration-300 ease-out cursor-pointer group">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400 transition-colors duration-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">Asset Disposed</p>
							<p class="kpi-number text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 transition-all duration-300 group-hover:scale-110 group-hover:text-blue-600 dark:group-hover:text-blue-400 inline-block" data-target="{{ $assetDisposed }}">0</p>
						</div>
						<div class="kpi-icon w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-6" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6 transition-colors duration-300" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
							</svg>
						</div>
					</div>
				</div>

				{{-- Total Active Users --}}
				<div class="kpi-card flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 min-w-0 transition-all duration-300 ease-out cursor-pointer group">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-400 transition-colors duration-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">Total Active Users</p>
							<p class="kpi-number text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 transition-all duration-300 group-hover:scale-110 group-hover:text-blue-600 dark:group-hover:text-blue-400 inline-block" data-target="{{ $totalActiveUsers }}">0</p>
						</div>
						<div class="kpi-icon w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-6" style="background-color: #E3F2FD;">
							<svg class="w-6 h-6 transition-colors duration-300" style="color: #4BA9C2;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
							</svg>
						</div>
					</div>
				</div>
			</div>

			{{-- Main Content Grid --}}
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
				{{-- Left Column --}}
				<div class="flex flex-col gap-6">
					{{-- Asset Status Pie Chart --}}
					<div class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 ease-out" style="flex: 0 0 auto;">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Asset Status</h3>
						<div class="flex justify-center items-center" style="height: 180px;">
							<canvas id="assetStatusChart"></canvas>
						</div>
						<div class="mt-4 flex justify-center gap-4 flex-wrap">
							@foreach($statusData as $status => $count)
								<div class="flex items-center gap-2">
									@if($status === 'Available')
										<div class="w-4 h-4 rounded-full" style="background-color: #90CAF9;"></div>
									@elseif($status === 'Checked Out')
										<div class="w-4 h-4 rounded-full" style="background-color: #1976D2;"></div>
									@elseif($status === 'Pending Dispose')
										<div class="w-4 h-4 rounded-full" style="background-color: #FFB3BA;"></div>
									@elseif($status === 'Disposed')
										<div class="w-4 h-4 rounded-full" style="background-color: #D32F2F;"></div>
									@endif
									<span class="text-sm text-gray-600 dark:text-gray-400">{{ $status }}: {{ $count }}</span>
								</div>
							@endforeach
						</div>
					</div>

					{{-- IT Requests Section --}}
					<div class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 ease-out" style="flex: 1;">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pending IT Requests</h3>
							<a href="{{ route('itdept.it-requests') }}" class="text-sm font-medium" style="color: #4BA9C2;">Show all</a>
						</div>
						
						@if($pendingITRequests->count() > 0)
							<div class="overflow-x-auto flex-1 min-h-0">
								<table class="table-auto w-full border border-gray-300 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
									<thead class="bg-gray-100 dark:bg-gray-700">
										<tr>
											<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Request Date') }}</th>
											<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Asset ID') }}</th>
											<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Requester Name') }}</th>
											<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">{{ __('Action') }}</th>
										</tr>
									</thead>
									<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
										@foreach($pendingITRequests as $request)
											<tr class="{{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-800' }}">
												<td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($request->requestDate)->format('d/m/y') }}</td>
												<td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $request->asset ? $request->asset->assetID : 'N/A' }}</td>
												<td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $request->requester ? $request->requester->fullName : 'N/A' }}</td>
												<td class="px-6 py-3">
													<a href="{{ route('itdept.it-requests.show', $request->requestID) }}" 
														class="view-details-link inline-flex items-center justify-center px-3 py-1 rounded-md border transition-all duration-200 text-xs"
														title="{{ __('View Details') }}">
														{{ __('View Details') }}
													</a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						@else
							<div class="text-center py-8 text-gray-500 dark:text-gray-400">
								<p>No pending IT requests</p>
							</div>
						@endif
					</div>
				</div>

				{{-- Right Column - Calendar --}}
				<div class="dashboard-card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 relative flex flex-col transition-all duration-300 ease-out">
					<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Calendar</h3>
					<div id="calendarHeader" class="mb-3 flex items-center justify-between">
						<button id="prevMonthBtn" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-110 active:scale-95" title="Previous Month">
							<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
							</svg>
						</button>
						<div id="monthYearDisplay" class="flex-1 text-center">
							<button id="monthYearText" class="text-base font-semibold text-gray-800 dark:text-gray-200 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:shadow-sm"></button>
							<div id="datePickerContainer" class="hidden mt-2 flex justify-center">
								<input type="month" id="monthYearPicker" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-all duration-200" />
							</div>
						</div>
						<button id="nextMonthBtn" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-110 active:scale-95" title="Next Month">
							<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
							</svg>
						</button>
					</div>
					<div id="calendar" class="relative"></div>
					
					{{-- Activity Popup Card --}}
					<div id="activityPopupCard" class="hidden absolute inset-0 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-2 border-gray-200 dark:border-gray-700 z-50 overflow-hidden transform transition-all duration-300 ease-out backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95 cursor-pointer" onclick="if(event.target === this) window.closeActivityPopup();">
						<div class="h-full flex flex-col pointer-events-none">
							{{-- Header --}}
							<div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 px-6 py-4 flex items-center justify-between pointer-events-auto">
								<div>
									<h3 id="popupDate" class="text-2xl font-bold text-white"></h3>
									<p id="popupDateShort" class="text-sm text-blue-100 mt-1"></p>
				</div>
								<button id="closePopupBtn" onclick="window.closeActivityPopup(); event.stopPropagation();" class="p-2 rounded-lg hover:bg-blue-800 dark:hover:bg-blue-900 transition-all duration-200 hover:scale-110 active:scale-95 cursor-pointer">
									<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						</svg>
					</button>
				</div>
							
							{{-- Activities List --}}
							<div id="popupActivities" class="flex-1 overflow-y-auto px-6 py-4 space-y-3 pointer-events-auto">
					<!-- Activities will be inserted here -->
				</div>
			</div>
		</div>
	</div>
			</div>
		</div>
	</div>

	<style>
		/* Ensure left column matches calendar height */
		@media (min-width: 1024px) {
			.grid.grid-cols-1.lg\\:grid-cols-2 > div:first-child {
				display: flex;
				flex-direction: column;
			}
			
			.grid.grid-cols-1.lg\\:grid-cols-2 > div:last-child {
				display: flex;
				flex-direction: column;
			}
		}
		
		/* KPI Cards Hover Effects */
		.kpi-card {
			position: relative;
			overflow: hidden;
		}
		
		.kpi-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(75, 169, 194, 0.1), transparent);
			transition: left 0.5s ease;
		}
		
		.kpi-card:hover::before {
			left: 100%;
		}
		
		.kpi-card:hover {
			transform: translateY(-4px) scale(1.02);
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(75, 169, 194, 0.2);
			border-color: rgba(75, 169, 194, 0.3);
		}
		
		.kpi-card:active {
			transform: translateY(-2px) scale(1.01);
		}
		
		.kpi-icon {
			position: relative;
		}
		
		.kpi-card:hover .kpi-icon {
			background: linear-gradient(135deg, #4BA9C2, #3a8ba5) !important;
		}
		
		.kpi-card:hover .kpi-icon svg {
			color: white !important;
		}
		
		/* Dashboard Cards Hover Effects */
		.dashboard-card {
			position: relative;
		}
		
		.dashboard-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(75, 169, 194, 0.15);
			border-color: rgba(75, 169, 194, 0.25);
		}
		
		.dashboard-card::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 3px;
			background: linear-gradient(90deg, #4BA9C2, #3a8ba5, #4BA9C2);
			background-size: 200% 100%;
			opacity: 0;
			transition: opacity 0.3s ease;
			border-radius: 8px 8px 0 0;
		}
		
		.dashboard-card:hover::after {
			opacity: 1;
			animation: shimmer 2s infinite;
		}
		
		@keyframes shimmer {
			0% {
				background-position: -200% 0;
			}
			100% {
				background-position: 200% 0;
			}
		}
		
		/* Pie Chart Container Hover */
		#assetStatusChart {
			transition: transform 0.3s ease;
			cursor: pointer;
		}
		
		.dashboard-card:hover #assetStatusChart {
			transform: scale(1.05) rotate(5deg);
		}
		
		.dashboard-card:hover #assetStatusChart:hover {
			transform: scale(1.1) rotate(-5deg);
		}
		
		/* KPI Number Animation */
		.kpi-number {
			font-variant-numeric: tabular-nums;
		}
		
		/* View Details Link Styling */
		.view-details-link {
			border-color: #4BA9C2;
			color: #4BA9C2;
			background-color: white;
		}
		
		.view-details-link:hover {
			background-color: #f0f9ff;
			color: #3a8ba5;
			border-color: #3a8ba5;
		}
		
		.dark .view-details-link {
			background-color: rgb(55, 65, 81);
			color: #60a5fa;
			border-color: #60a5fa;
		}
		
		.dark .view-details-link:hover {
			background-color: rgba(30, 58, 138, 0.3);
			color: #93c5fd;
			border-color: #93c5fd;
		}
		
		@keyframes slideDown {
			from {
				opacity: 0;
				transform: translateY(-10px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
		
		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(20px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
		
		.activity-item {
			animation-fill-mode: both;
		}
		
		#calendar td[data-date] {
			transition: all 0.2s ease;
		}
		
		#calendar td[data-date]:hover {
			transform: scale(1.05);
			z-index: 10;
			position: relative;
		}
		
		#calendar td[data-date] {
			position: relative;
		}
		
		#calendar td[data-date]::before {
			content: '';
			position: absolute;
			inset: 0;
			border-radius: 4px;
			background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.1));
			opacity: 0;
			transition: opacity 0.2s ease;
			pointer-events: none;
		}
		
		#calendar td[data-date]:hover::before {
			opacity: 1;
		}
		
		#prevMonthBtn, #nextMonthBtn {
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
		}
		
		#prevMonthBtn:hover, #nextMonthBtn:hover {
			background-color: rgba(59, 130, 246, 0.1);
		}
		
		#monthYearText {
			position: relative;
		}
		
		#monthYearText::after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 0;
			height: 2px;
			background: linear-gradient(to right, #3b82f6, #2563eb);
			transition: width 0.3s ease;
		}
		
		#monthYearText:hover::after {
			width: 80%;
		}
	</style>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Count-up animation for KPI numbers
			function animateValue(element, start, end, duration) {
				let startTimestamp = null;
				const step = (timestamp) => {
					if (!startTimestamp) startTimestamp = timestamp;
					const progress = Math.min((timestamp - startTimestamp) / duration, 1);
					const easeOutQuart = 1 - Math.pow(1 - progress, 4);
					const current = Math.floor(easeOutQuart * (end - start) + start);
					element.textContent = current;
					if (progress < 1) {
						window.requestAnimationFrame(step);
					} else {
						element.textContent = end;
					}
				};
				window.requestAnimationFrame(step);
			}
			
			// Initialize count-up animations for all KPI numbers
			const kpiNumbers = document.querySelectorAll('.kpi-number');
			kpiNumbers.forEach((element, index) => {
				const target = parseInt(element.getAttribute('data-target')) || 0;
				// Stagger the animations slightly
				setTimeout(() => {
					animateValue(element, 0, target, 1500);
				}, index * 100);
			});
			
			// Asset Status Pie Chart
			const ctx = document.getElementById('assetStatusChart');
			if (ctx) {
				const assetStatusChart = new Chart(ctx.getContext('2d'), {
					type: 'pie',
					data: {
						labels: ['Available', 'Checked Out', 'Pending Dispose', 'Disposed'],
						datasets: [{
							data: [
								{{ $statusData['Available'] ?? 0 }},
								{{ $statusData['Checked Out'] ?? 0 }},
								{{ $statusData['Pending Dispose'] ?? 0 }},
								{{ $statusData['Disposed'] ?? 0 }}
							],
							backgroundColor: [
								'#90CAF9', // Light blue - Available
								'#1976D2', // Dark blue - Checked Out
								'#FFB3BA', // Light red - Pending Dispose
								'#D32F2F'  // Darker red - Disposed
							],
							borderWidth: 0
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: true,
						aspectRatio: 1.2,
						plugins: {
							legend: {
								display: false
							},
							tooltip: {
								enabled: true,
								backgroundColor: 'rgba(0, 0, 0, 0.8)',
								padding: 12,
								titleFont: {
									size: 14,
									weight: 'bold'
								},
								bodyFont: {
									size: 13
								},
								callbacks: {
									label: function(context) {
										const label = context.label || '';
										const value = context.parsed || 0;
										const total = context.dataset.data.reduce((a, b) => a + b, 0);
										const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
										return label + ': ' + value + ' (' + percentage + '%)';
									}
								}
							}
						},
						onHover: (event, activeElements) => {
							event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
						},
						animation: {
							animateRotate: true,
							animateScale: true,
							duration: 1000,
							easing: 'easeOutQuart'
						},
						interaction: {
							intersect: false,
							mode: 'nearest'
						}
					},
					onClick: (event, activeElements) => {
						if (activeElements.length > 0) {
							const chart = activeElements[0].chart;
							const index = activeElements[0].index;
							const dataset = chart.data.datasets[0];
							
							// Add visual feedback with border
							const originalBorderWidth = dataset.borderWidth || 0;
							dataset.borderWidth = 4;
							dataset.borderColor = '#4BA9C2';
							chart.update('active');
							
							setTimeout(() => {
								dataset.borderWidth = originalBorderWidth;
								chart.update('active');
							}, 500);
						}
					}
				});
				
				// Add hover scale effect to chart canvas
				if (ctx) {
					const chartContainer = ctx.closest('.dashboard-card');
					if (chartContainer) {
						ctx.addEventListener('mouseenter', function() {
							this.style.transform = 'scale(1.08)';
							this.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
						});
						
						ctx.addEventListener('mouseleave', function() {
							this.style.transform = 'scale(1)';
						});
					}
				}
			}

			// Calendar state
			let currentMonth = {{ $selectedMonth ?? now()->month }} - 1; // JavaScript months are 0-indexed
			let currentYear = {{ $selectedYear ?? now()->year }};
			let calendarEvents = @json($calendarEvents ?? []);

			// Calendar with events
			function renderCalendar() {
				const calendarEl = document.getElementById('calendar');
				const monthYearText = document.getElementById('monthYearText');
				if (!calendarEl) return;
				
				const today = new Date();
				const todayDate = today.getDate();
				const todayMonth = today.getMonth();
				const todayYear = today.getFullYear();
				
				// Month names
				const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December'];
				
				// Display month and year
				if (monthYearText) {
					monthYearText.textContent = monthNames[currentMonth] + ' ' + currentYear;
				}
				
				// Update month picker value
				const monthPicker = document.getElementById('monthYearPicker');
				if (monthPicker) {
					const monthStr = String(currentMonth + 1).padStart(2, '0');
					monthPicker.value = currentYear + '-' + monthStr;
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
							const isToday = currentDay === todayDate && currentMonth === todayMonth && currentYear === todayYear;
							
							// Check for events on this date
							const dateKey = formatDate(currentYear, currentMonth, currentDay);
							const dayEvents = calendarEvents[dateKey] || [];
							const hasEvent = dayEvents.length > 0;
							
							// Determine cell classes with enhanced styling
							let cellClasses = 'border border-gray-300 dark:border-gray-600 p-2 h-20 w-20 align-top relative transition-all duration-200 ease-out';
							if (isToday) {
								cellClasses += ' bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/50 dark:to-blue-800/30 border-blue-400 dark:border-blue-500 shadow-sm';
							} else {
								cellClasses += ' bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700';
							}
							
							// Highlight cells with events and make them clickable
							if (hasEvent && !isToday) {
								cellClasses += ' bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 cursor-pointer hover:shadow-md hover:border-blue-400 dark:hover:border-blue-500 hover:scale-105';
							} else if (hasEvent && isToday) {
								cellClasses += ' cursor-pointer hover:shadow-lg hover:scale-105';
							} else if (!hasEvent) {
								cellClasses += ' hover:bg-gray-50 dark:hover:bg-gray-700';
							}
							
							// Add data attribute for date key if there are events
							const dataAttr = hasEvent ? `data-date="${dateKey}"` : '';
							
							calendarHTML += '<td class="' + cellClasses + '" ' + dataAttr + '>';
							
							// Day number with enhanced styling
							let dayClasses = 'text-sm font-medium mb-1 transition-colors duration-200';
							if (isToday) {
								dayClasses += ' text-blue-700 dark:text-blue-300 font-bold';
							} else if (hasEvent) {
								dayClasses += ' text-gray-800 dark:text-gray-200 font-semibold';
							} else {
								dayClasses += ' text-gray-700 dark:text-gray-300';
							}
							calendarHTML += '<div class="' + dayClasses + '">' + currentDay + '</div>';
							
							// Add event indicators with enhanced styling
							if (hasEvent) {
								let eventHTML = '<div class="absolute bottom-2 left-1 right-1 flex gap-1 flex-wrap">';
								dayEvents.forEach(function(event, index) {
									let color = '#90CAF9'; // Default blue
									const eventType = typeof event === 'object' ? event.type : event;
									if (eventType === 'checkout') {
										color = '#4BA9C2'; // Blue for checkout
									} else if (eventType === 'checkin') {
										color = '#10B981'; // Green for checkin
									} else if (eventType === 'disposal') {
										color = '#EF4444'; // Red for disposal
									}
									eventHTML += '<div class="h-2.5 flex-1 rounded-full shadow-sm transition-all duration-200 hover:scale-110" style="background: linear-gradient(90deg, ' + color + ', ' + color + 'dd); min-width: 20px;" title="' + eventType + '"></div>';
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
				
				// Add click event listeners to cells with events
				const cellsWithEvents = calendarEl.querySelectorAll('[data-date]');
				cellsWithEvents.forEach(function(cell) {
					cell.addEventListener('click', function() {
						const dateKey = this.getAttribute('data-date');
						const dayEvents = calendarEvents[dateKey] || [];
						showActivityDetails(dateKey, dayEvents);
					});
				});
			}
			
			// Navigation handlers
			function navigateMonth(direction) {
				currentMonth += direction;
				if (currentMonth < 0) {
					currentMonth = 11;
					currentYear--;
				} else if (currentMonth > 11) {
					currentMonth = 0;
					currentYear++;
				}
				loadCalendarEvents();
			}
			
			function loadCalendarEvents() {
				// Show loading state
				const calendarEl = document.getElementById('calendar');
				if (calendarEl) {
					calendarEl.innerHTML = '<div class="text-center py-8 text-gray-500 dark:text-gray-400">Loading...</div>';
				}
				
				// Build URL with month and year parameters
				const url = new URL(window.location.href);
				url.searchParams.set('month', currentMonth + 1);
				url.searchParams.set('year', currentYear);
				
				// Fetch calendar events via AJAX
				fetch(url.toString(), {
					method: 'GET',
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json',
					}
				})
				.then(response => response.json())
				.then(data => {
					calendarEvents = data.calendarEvents || {};
					renderCalendar();
				})
				.catch(error => {
					console.error('Error loading calendar events:', error);
					// Fallback: reload the page
					window.location.href = url.toString();
				});
			}
			
			// Event listeners for navigation
			const prevMonthBtn = document.getElementById('prevMonthBtn');
			const nextMonthBtn = document.getElementById('nextMonthBtn');
			const monthYearText = document.getElementById('monthYearText');
			const monthYearPicker = document.getElementById('monthYearPicker');
			
			if (prevMonthBtn) {
				prevMonthBtn.addEventListener('click', function() {
					navigateMonth(-1);
				});
			}
			
			if (nextMonthBtn) {
				nextMonthBtn.addEventListener('click', function() {
					navigateMonth(1);
				});
			}
			
			if (monthYearText && monthYearPicker) {
				const datePickerContainer = document.getElementById('datePickerContainer');
				
				monthYearText.addEventListener('click', function(e) {
					e.stopPropagation();
					if (datePickerContainer) {
						const isHidden = datePickerContainer.classList.contains('hidden');
						if (isHidden) {
							datePickerContainer.classList.remove('hidden');
							datePickerContainer.style.animation = 'slideDown 0.3s ease-out';
							monthYearPicker.focus();
						} else {
							datePickerContainer.classList.add('hidden');
						}
					}
				});
				
				monthYearPicker.addEventListener('change', function() {
					const selectedDate = new Date(this.value + '-01');
					currentYear = selectedDate.getFullYear();
					currentMonth = selectedDate.getMonth();
					if (datePickerContainer) {
						datePickerContainer.classList.add('hidden');
					}
					loadCalendarEvents();
				});
				
				// Close date picker when clicking outside
				document.addEventListener('click', function(e) {
					if (datePickerContainer && !datePickerContainer.contains(e.target) && !monthYearText.contains(e.target)) {
						datePickerContainer.classList.add('hidden');
					}
				});
			}
			
			renderCalendar();
		});

		// Function to show activity details popup card
		function showActivityDetails(date, events) {
			const popupCard = document.getElementById('activityPopupCard');
			const popupDate = document.getElementById('popupDate');
			const popupDateShort = document.getElementById('popupDateShort');
			const popupActivities = document.getElementById('popupActivities');
			
			if (!popupCard) return;
			
			// Format date for display
			const dateObj = new Date(date + 'T00:00:00');
			const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
			const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
				'July', 'August', 'September', 'October', 'November', 'December'];
			
			const dayName = dayNames[dateObj.getDay()];
			const day = dateObj.getDate();
			const month = monthNames[dateObj.getMonth()];
			const year = dateObj.getFullYear();
			
			popupDate.textContent = day + ' ' + dayName;
			popupDateShort.textContent = day + ' ' + month.substring(0, 3) + ' ' + year;
			
			// Clear previous activities
			popupActivities.innerHTML = '';
			
			// Add each activity with attractive styling
			if (events && events.length > 0) {
				events.forEach(function(event, index) {
					const activityDiv = document.createElement('div');
					activityDiv.className = 'activity-item p-4 rounded-xl border-2 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer';
					activityDiv.style.animation = `fadeInUp 0.4s ease-out ${index * 0.1}s both`;
					
					let activityText = '';
					let activitySubtext = '';
					let activityColor = '';
					let activityIcon = '';
					
					if (typeof event === 'object' && event.type) {
						const eventType = event.type;
						const assetID = event.assetID || 'N/A';
						
						if (eventType === 'checkout') {
							const userName = event.userName || 'N/A';
							activityText = 'Asset Checked Out';
							activitySubtext = assetID + ' → ' + userName;
							activityColor = '#4BA9C2';
							activityIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
							activityDiv.style.borderColor = activityColor;
							activityDiv.style.backgroundColor = 'rgba(75, 169, 194, 0.1)';
						} else if (eventType === 'checkin') {
							activityText = 'Asset Checked In';
							activitySubtext = assetID + ' returned to IT';
							activityColor = '#10B981';
							activityIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
							activityDiv.style.borderColor = activityColor;
							activityDiv.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
						} else if (eventType === 'disposal') {
							activityText = 'Asset Disposed';
							activitySubtext = assetID + ' has been disposed';
							activityColor = '#EF4444';
							activityIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
							activityDiv.style.borderColor = activityColor;
							activityDiv.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
						}
					} else {
						activityText = 'Activity';
						activitySubtext = typeof event === 'object' ? JSON.stringify(event) : event;
						activityColor = '#6B7280';
					}
					
					activityDiv.innerHTML = `
						<div class="flex items-start gap-4">
							<div class="flex-shrink-0 p-2 rounded-lg" style="background-color: ${activityColor}; color: white;">
								${activityIcon}
							</div>
							<div class="flex-1">
								<h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">${activityText}</h4>
								<p class="text-sm text-gray-600 dark:text-gray-400">${activitySubtext}</p>
							</div>
						</div>
					`;
					popupActivities.appendChild(activityDiv);
				});
			} else {
				popupActivities.innerHTML = `
					<div class="text-center py-12">
						<svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
						</svg>
						<p class="text-gray-500 dark:text-gray-400 font-medium">No activities on this date</p>
						<p class="text-sm text-gray-400 dark:text-gray-500 mt-2">All assets are accounted for</p>
					</div>
				`;
			}
			
			// Show popup with animation
			popupCard.classList.remove('hidden');
			popupCard.style.opacity = '0';
			popupCard.style.transform = 'scale(0.9) translateY(20px)';
			
			setTimeout(() => {
				popupCard.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
				popupCard.style.opacity = '1';
				popupCard.style.transform = 'scale(1) translateY(0)';
			}, 10);
			
			// Ensure close button is set up
			const closeBtn = document.getElementById('closePopupBtn');
			if (closeBtn) {
				closeBtn.onclick = function(e) {
					e.stopPropagation();
					window.closeActivityPopup();
				};
			}
		}
		
		// Function to close activity popup card (make it globally accessible)
		window.closeActivityPopup = function() {
			const popupCard = document.getElementById('activityPopupCard');
			if (popupCard) {
				popupCard.style.transition = 'all 0.2s ease-in';
				popupCard.style.opacity = '0';
				popupCard.style.transform = 'scale(0.95) translateY(10px)';
				setTimeout(() => {
					popupCard.classList.add('hidden');
				}, 200);
			}
		}
		
		// Also create a local function for use within the script
		function closeActivityPopup() {
			window.closeActivityPopup();
		}
		
		// Close popup handlers - set up on DOMContentLoaded
		document.addEventListener('DOMContentLoaded', function() {
			// Close popup on Escape key
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape') {
					const popupCard = document.getElementById('activityPopupCard');
					if (popupCard && !popupCard.classList.contains('hidden')) {
						closeActivityPopup();
			}
				}
			});
		});
	</script>
</x-app-layout>
