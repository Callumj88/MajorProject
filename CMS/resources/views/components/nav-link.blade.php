@props(['active' => false]) {{-- to check if this is the   curtent page --}}

@php
    $classes = $active
        ? 'header-menu-item-active'
        : 'header-menu-item';
@endphp

<!-- Reusable nav link component -->
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }} 
</a>
