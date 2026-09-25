@props([
    'type' => 'link',
])

@if ($type === 'submit')
    <button type="submit" {{ $attributes->except('type')->merge(['class' => 'arx-btn']) }}>
        {{ $slot }}
    </button>
@else
    <a {{ $attributes->merge(['class' => 'arx-btn']) }}>
        {{ $slot }}
    </a>
@endif
