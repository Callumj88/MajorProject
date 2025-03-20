@props(['active' => false])

<?php
    $classes = $active ? 'header-menu-item-active' : 'header-menu-item';
?>

<a {{ $attributes->merge(['class' => $classes]) }}> {{ $slot }} </a>
