@props(['height'])

<div style="height: {{ $height }};">
    {{ $slot }}
</div>