<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Repairs & Maintenance') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					{{ __("Repairs & Maintenance System") }}
					<p class="mt-4 text-gray-600 dark:text-gray-400">
						Track repair requests, schedule maintenance, and manage equipment servicing.
					</p>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
