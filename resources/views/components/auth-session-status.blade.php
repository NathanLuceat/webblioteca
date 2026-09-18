@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-folio']) }}>
        {{ $status }}
    </div>
@endif
