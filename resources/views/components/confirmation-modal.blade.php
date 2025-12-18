{{-- Reusable Confirmation Modal Component --}}
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
	<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
		<div class="mt-3 text-center">
			<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900 mb-4">
				<svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
				</svg>
			</div>
			<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4" id="confirmationModalTitle">
				{{ __('Confirm Action') }}
			</h3>
			<p class="text-sm text-gray-600 dark:text-gray-400 mb-6" id="confirmationModalMessage">
				{{ __('Are you sure you want to proceed?') }}
			</p>
			<div class="flex justify-center gap-4">
				<button id="confirmationModalCancel" type="button" 
					class="interactive-button interactive-button-secondary"
					style="padding: 10px 16px; font-size: 11px;">
					<span class="button-content">
						{{ __('Cancel') }}
					</span>
				</button>
				<button id="confirmationModalConfirm" type="button" 
					class="interactive-button interactive-button-delete"
					style="padding: 10px 16px; font-size: 11px;">
					<span class="button-content">
						{{ __('Confirm') }}
					</span>
				</button>
			</div>
		</div>
	</div>
</div>

<style>
	/* Interactive button styling for confirmation modal */
	#confirmationModal .interactive-button {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		border: none;
		border-radius: 8px;
		cursor: pointer;
		transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		position: relative;
		overflow: hidden;
		text-decoration: none;
	}

	#confirmationModal .interactive-button-secondary {
		background: linear-gradient(135deg, #797979 0%, #666666 100%);
		color: white;
		box-shadow: 0 4px 12px rgba(121, 121, 121, 0.3);
	}

	#confirmationModal .interactive-button-secondary:hover {
		background: linear-gradient(135deg, #666666 0%, #555555 100%);
		box-shadow: 0 8px 20px rgba(121, 121, 121, 0.5);
		transform: translateY(-2px) scale(1.02);
	}

	#confirmationModal .interactive-button-delete {
		background: linear-gradient(135deg, #B40814 0%, #A10712 100%);
		color: white;
		box-shadow: 0 4px 12px rgba(180, 8, 20, 0.3);
	}

	#confirmationModal .interactive-button-delete:hover {
		background: linear-gradient(135deg, #A10712 0%, #990610 100%);
		box-shadow: 0 8px 20px rgba(180, 8, 20, 0.5);
		transform: translateY(-2px) scale(1.02);
	}

	#confirmationModal .button-content {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		position: relative;
		z-index: 1;
	}
</style>

<script>
	// Global confirmation modal handler
	window.showConfirmation = function(message, title, onConfirm, onCancel) {
		const modal = document.getElementById('confirmationModal');
		const modalTitle = document.getElementById('confirmationModalTitle');
		const modalMessage = document.getElementById('confirmationModalMessage');
		const confirmBtn = document.getElementById('confirmationModalConfirm');
		const cancelBtn = document.getElementById('confirmationModalCancel');

		if (!modal || !modalTitle || !modalMessage || !confirmBtn || !cancelBtn) {
			// Fallback to browser confirm if modal elements not found
			return window.confirm(message || title || 'Are you sure?');
		}

		// Set modal content
		modalTitle.textContent = title || 'Confirm Action';
		modalMessage.textContent = message || 'Are you sure you want to proceed?';

		// Show modal
		modal.classList.remove('hidden');

		// Return a promise that resolves when user confirms or cancels
		return new Promise((resolve) => {
			const handleConfirm = () => {
				modal.classList.add('hidden');
				confirmBtn.removeEventListener('click', handleConfirm);
				cancelBtn.removeEventListener('click', handleCancel);
				modal.removeEventListener('click', handleBackdrop);
				if (onConfirm) onConfirm();
				resolve(true);
			};

			const handleCancel = () => {
				modal.classList.add('hidden');
				confirmBtn.removeEventListener('click', handleConfirm);
				cancelBtn.removeEventListener('click', handleCancel);
				modal.removeEventListener('click', handleBackdrop);
				if (onCancel) onCancel();
				resolve(false);
			};

			const handleBackdrop = (e) => {
				if (e.target === modal) {
					handleCancel();
				}
			};

			confirmBtn.addEventListener('click', handleConfirm);
			cancelBtn.addEventListener('click', handleCancel);
			modal.addEventListener('click', handleBackdrop);
		});
	};

	// Close modal on Escape key
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') {
			const modal = document.getElementById('confirmationModal');
			if (modal && !modal.classList.contains('hidden')) {
				modal.classList.add('hidden');
			}
		}
	});
</script>

