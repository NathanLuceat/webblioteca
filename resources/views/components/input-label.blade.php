@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-display text-[13px] font-semibold text-ink-soft']) }}>
    {{ $value ?? $slot }}
</label>
