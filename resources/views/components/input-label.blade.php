@props(['value'])

<label {{ $attributes->merge(['class' => 'block fonts-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
