@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'brutalist-input']) !!} style="width: 100%; padding: 12px; border: 4px solid #000000; font-family: 'Courier New', monospace; font-size: 16px; background: #FFFFFF;">
