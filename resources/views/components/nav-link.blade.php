@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 pt-1 border-b-2 border-leather text-sm font-display font-medium leading-5 text-ink focus:outline-none focus:border-leather-deep transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 pt-1 border-b-2 border-transparent text-sm font-display font-medium leading-5 text-ink-soft hover:text-ink hover:border-line-strong focus:outline-none focus:text-ink focus:border-brass/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
