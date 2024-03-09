@props(['active', 'notifications'])

@php
$classes = ($active ?? false)
            ? 'inline-flex relative items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex relative items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
$notifications_count = $notifications ?? 0;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    @if ($notifications_count)
        <div class="absolute top-4 -right-3 rounded-full bg-gray-700 w-4 h-4 flex justify-center items-center text-white text-xs">
            {{$notifications_count}}
        </div>
    @endif
</a>
