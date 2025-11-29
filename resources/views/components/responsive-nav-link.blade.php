@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out';
$activeStyle = ($active ?? false)
            ? 'color: #4BA9C2; border-left-color: #4BA9C2; background-color: rgba(75, 169, 194, 0.1);'
            : 'color: #6B7280;';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'style' => $activeStyle]) }}>
    {{ $slot }}
</a>
