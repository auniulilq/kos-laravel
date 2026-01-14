@props(['value'])

<label {{ $attributes->merge(['class' => 'brutalist-label']) }} style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; text-transform: uppercase; font-family: 'Courier New', monospace;">
    {{ $value ?? $slot }}
</label>
