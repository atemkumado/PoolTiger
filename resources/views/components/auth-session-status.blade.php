@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'fonts-medium text-sm text-green-600']) }}>
        {{ $status }}
    </div>
@endif
