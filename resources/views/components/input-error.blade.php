@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'text-sm']) }} style="color: #FF0000; font-weight: bold; margin-top: 5px; font-family: 'Courier New', monospace;">
        @foreach ((array) $messages as $message)
            <div>✗ {{ $message }}</div>
        @endforeach
    </div>
@endif