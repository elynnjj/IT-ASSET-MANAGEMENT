@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-gray-400 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-gray-500 dark:focus:border-gray-500 focus:ring-gray-500 dark:focus:ring-gray-500 rounded-md shadow-sm']) }}>
