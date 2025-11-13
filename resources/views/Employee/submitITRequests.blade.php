<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Submit IT Request') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					{{ __("Submit IT Request") }}
					<p class="mt-4 text-gray-600 dark:text-gray-400">
						Submit your IT support requests, software installation requests, or technical assistance needs.
					</p>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
