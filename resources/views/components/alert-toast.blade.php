{{-- Reusable Alert/Toast Notification Component --}}
<div id="alertToast" class="fixed top-4 right-4 z-50 hidden">
	<div id="alertToastContainer" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg p-4 min-w-[300px] max-w-md">
		<div class="flex items-start">
			<div id="alertToastIcon" class="flex-shrink-0 mr-3">
				<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
				</svg>
			</div>
			<div class="flex-1">
				<p id="alertToastMessage" class="text-sm font-medium text-gray-900 dark:text-gray-100"></p>
			</div>
			<button id="alertToastClose" type="button" class="ml-3 flex-shrink-0 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
				<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
		</div>
	</div>
</div>

<script>
	// Global alert/toast handler
	window.showAlert = function(message, type) {
		const toast = document.getElementById('alertToast');
		const toastMessage = document.getElementById('alertToastMessage');
		const toastIcon = document.getElementById('alertToastIcon');
		const toastClose = document.getElementById('alertToastClose');

		if (!toast || !toastMessage) {
			// Fallback to browser alert if toast elements not found
			alert(message);
			return;
		}

		// Set message
		toastMessage.textContent = message;

		// Set icon and color based on type
		const types = {
			info: { 
				color: 'text-blue-600 dark:text-blue-400', 
				bg: 'bg-blue-100 dark:bg-blue-900',
				icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
			},
			success: { 
				color: 'text-green-600 dark:text-green-400', 
				bg: 'bg-green-100 dark:bg-green-900',
				icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
			},
			warning: { 
				color: 'text-yellow-600 dark:text-yellow-400', 
				bg: 'bg-yellow-100 dark:bg-yellow-900',
				icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'
			},
			error: { 
				color: 'text-red-600 dark:text-red-400', 
				bg: 'bg-red-100 dark:bg-red-900',
				icon: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
			}
		};

		const toastType = types[type] || types.info;
		const toastContainer = document.getElementById('alertToastContainer');
		toastIcon.className = `flex-shrink-0 mr-3 ${toastType.color}`;
		toastIcon.innerHTML = toastType.icon;
		if (toastContainer) {
			toastContainer.className = `bg-white dark:bg-gray-800 border ${toastType.bg} border-gray-300 dark:border-gray-700 rounded-lg shadow-lg p-4 min-w-[300px] max-w-md`;
		}

		// Show toast
		toast.classList.remove('hidden');

		// Auto-hide after 5 seconds
		const autoHide = setTimeout(() => {
			toast.classList.add('hidden');
		}, 5000);

		// Close button handler
		if (toastClose) {
			const handleClose = () => {
				clearTimeout(autoHide);
				toast.classList.add('hidden');
				toastClose.removeEventListener('click', handleClose);
			};
			toastClose.addEventListener('click', handleClose);
		}
	};
</script>

