@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
$activeStyle = ($active ?? false)
            ? 'color: #4BA9C2; border-bottom-color: #4BA9C2;'
            : 'color: #6B7280;';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'style' => $activeStyle]) }}>
    {{ $slot }}
</a>
