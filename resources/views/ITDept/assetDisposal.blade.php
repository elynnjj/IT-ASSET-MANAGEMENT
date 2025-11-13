<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Asset Disposal') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					{{ __("Asset Disposal Management") }}
					<p class="mt-4 text-gray-600 dark:text-gray-400">
						Manage the disposal of obsolete or damaged IT assets with proper documentation and approval processes.
					</p>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
