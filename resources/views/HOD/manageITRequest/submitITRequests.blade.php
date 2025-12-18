<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('New IT Request') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- Breadcrumb --}}
			<div class="mb-6">
				<a href="{{ route('hod.my-requests') }}" 
				   style="color: #4BA9C2;"
				   class="hover:opacity-80">
					← My Requests
				</a>
				<span class="text-gray-600 dark:text-gray-400"> > </span>
				<span class="text-gray-600 dark:text-gray-400">{{ __('New IT Request') }}</span>
			</div>

			{{-- Main Content Card --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					{{-- Title --}}
					<div class="mb-6">
						<h1 class="text-xl font-semibold">{{ __('New IT Request') }}</h1>
					</div>

					{{-- Session Status --}}
					@if (session('status'))
						<div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 rounded-md">
							{{ session('status') }}
						</div>
					@endif

					{{-- Asset Details Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Your Assigned Asset') }}</h3>
						@if($assignedAsset)
							<div class="grid grid-cols-2 gap-4">
								<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
									<label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Asset ID') }}</label>
									<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $assignedAsset->asset->assetID }}</p>
								</div>
								<div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 bg-white dark:bg-gray-800">
									<label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Model') }}</label>
									<p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $assignedAsset->asset->model ?? 'N/A' }}</p>
								</div>
							</div>
						@else
							<p class="text-sm text-gray-600 dark:text-gray-400 italic">No asset currently assigned to you.</p>
						@endif
					</div>

					{{-- New IT Request Form Section --}}
					<div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
						<h3 class="text-lg font-semibold mb-4">{{ __('Request Details') }}</h3>
						<form action="{{ route('hod.it-requests.store') }}" method="POST">
							@csrf

							<div class="space-y-4">
								{{-- Request Date --}}
								<div class="input-container">
									<x-input-label for="requestDate" :value="__('Request Date')" class="text-[15px]" />
									<x-text-input id="requestDate" name="requestDate" type="date" 
										class="mt-1 block w-full interactive-input" 
										value="{{ old('requestDate', date('Y-m-d')) }}"
										required />
									<x-input-error :messages="$errors->get('requestDate')" class="mt-2" />
								</div>

								{{-- Request Title --}}
								<div class="input-container">
									<x-input-label for="title" :value="__('Request Title')" class="text-[15px]" />
									<x-text-input id="title" name="title" type="text" 
										class="mt-1 block w-full interactive-input" 
										placeholder="Enter a brief title for your request"
										value="{{ old('title') }}"
										required />
									<x-input-error :messages="$errors->get('title')" class="mt-2" />
								</div>

								{{-- Request Description --}}
								<div class="input-container">
									<x-input-label for="requestDesc" :value="__('Request Description')" class="text-[15px]" />
									<textarea id="requestDesc" name="requestDesc" 
										rows="5"
										class="mt-1 block w-full interactive-textarea"
										placeholder="Describe your IT request in detail..."
										required>{{ old('requestDesc') }}</textarea>
									<x-input-error :messages="$errors->get('requestDesc')" class="mt-2" />
								</div>
							</div>

							{{-- Buttons --}}
							<div class="flex items-center justify-end space-x-6 mt-6">
								<a href="{{ route('hod.my-requests') }}" 
								   class="interactive-button interactive-button-secondary"
								   style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										{{ __('Cancel') }}
									</span>
								</a>
								<button type="submit" 
									class="interactive-button interactive-button-primary"
									style="padding: 10px 16px; font-size: 11px;">
									<span class="button-content">
										<span class="button-text">{{ __('Submit Request') }}</span>
										<span class="button-spinner"></span>
									</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<style>
		/* Input container with hover effects */
		.input-container {
			position: relative;
			transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		}

		.input-container:hover {
			transform: translateY(-1px);
		}

		.input-container:has(.interactive-input:focus),
		.input-container:has(.interactive-textarea:focus) {
			transform: translateY(-2px);
		}

		/* Interactive input styling */
		.interactive-input,
		.interactive-textarea {
			width: 100%;
			padding: 8px 12px;
			border: 2px solid #9CA3AF;
			border-radius: 8px;
			font-size: 15px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #FFFFFF;
			position: relative;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input,
			.interactive-textarea {
				background-color: #111827;
				border-color: #6B7280;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input,
		.dark .interactive-textarea {
			background-color: #111827;
			border-color: #6B7280;
			color: #D1D5DB;
		}

		.interactive-input:hover,
		.interactive-textarea:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.15);
			transform: translateY(-1px);
			background-color: #FAFAFA;
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:hover,
			.interactive-textarea:hover {
				border-color: #4BA9C2;
				box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
				background-color: #1F2937;
			}
		}

		.dark .interactive-input:hover,
		.dark .interactive-textarea:hover {
			border-color: #4BA9C2;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.2);
			background-color: #1F2937;
		}

		.interactive-input:focus,
		.interactive-textarea:focus {
			outline: none;
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.15), 0 6px 16px rgba(75, 169, 194, 0.2);
			background-color: #FFFFFF;
			transform: translateY(-2px);
		}

		@media (prefers-color-scheme: dark) {
			.interactive-input:focus,
			.interactive-textarea:focus {
				border-color: #4BA9C2;
				box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
				background-color: #111827;
				color: #D1D5DB;
			}
		}

		.dark .interactive-input:focus,
		.dark .interactive-textarea:focus {
			border-color: #4BA9C2;
			box-shadow: 0 0 0 4px rgba(75, 169, 194, 0.2), 0 6px 16px rgba(75, 169, 194, 0.3);
			background-color: #111827;
			color: #D1D5DB;
		}

		.interactive-textarea {
			resize: vertical;
			min-height: 120px;
		}

		/* Interactive button styling */
		.interactive-button {
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

		.interactive-button-primary {
			background: linear-gradient(135deg, #4BA9C2 0%, #3a8ba5 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.3);
		}

		.interactive-button-primary::before {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.3);
			transform: translate(-50%, -50%);
			transition: width 0.6s, height 0.6s;
		}

		.interactive-button-primary:hover {
			background: linear-gradient(135deg, #3a8ba5 0%, #2d6b82 100%);
			box-shadow: 0 8px 20px rgba(75, 169, 194, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-primary:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-primary:active {
			background: linear-gradient(135deg, #2d6b82 0%, #1f5a6f 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(75, 169, 194, 0.3);
		}

		.interactive-button-secondary {
			background: linear-gradient(135deg, #797979 0%, #666666 100%);
			color: white;
			box-shadow: 0 4px 12px rgba(121, 121, 121, 0.3);
		}

		.interactive-button-secondary::before {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.3);
			transform: translate(-50%, -50%);
			transition: width 0.6s, height 0.6s;
		}

		.interactive-button-secondary:hover {
			background: linear-gradient(135deg, #666666 0%, #555555 100%);
			box-shadow: 0 8px 20px rgba(121, 121, 121, 0.5);
			transform: translateY(-2px) scale(1.02);
		}

		.interactive-button-secondary:active::before {
			width: 300px;
			height: 300px;
		}

		.interactive-button-secondary:active {
			background: linear-gradient(135deg, #555555 0%, #444444 100%);
			transform: translateY(0) scale(0.98);
			box-shadow: 0 2px 8px rgba(121, 121, 121, 0.3);
		}

		.button-content {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			position: relative;
			z-index: 1;
		}

		.button-spinner {
			display: none;
			width: 18px;
			height: 18px;
			border: 2px solid rgba(255, 255, 255, 0.3);
			border-top-color: white;
			border-radius: 50%;
			animation: spin 0.8s linear infinite;
		}

		.interactive-button.loading .button-spinner {
			display: block;
		}

		.interactive-button.loading .button-text {
			opacity: 0.7;
		}

		@keyframes spin {
			to { transform: rotate(360deg); }
		}

		/* Dark mode support for buttons */
		.dark .interactive-button-primary {
			box-shadow: 0 4px 12px rgba(75, 169, 194, 0.4);
		}

		.dark .interactive-button-primary:hover {
			box-shadow: 0 8px 20px rgba(75, 169, 194, 0.6);
		}

		.dark .interactive-button-secondary {
			box-shadow: 0 4px 12px rgba(121, 121, 121, 0.4);
		}

		.dark .interactive-button-secondary:hover {
			box-shadow: 0 8px 20px rgba(121, 121, 121, 0.6);
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Add loading state to submit button on form submission
			const form = document.querySelector('form');
			const submitButton = form?.querySelector('button[type="submit"]');
			
			if (form && submitButton) {
				form.addEventListener('submit', function() {
					submitButton.classList.add('loading');
					submitButton.disabled = true;
				});
			}
		});
	</script>
</x-app-layout>
