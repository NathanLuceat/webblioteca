@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-line bg-paper-light text-ink shadow-stamp placeholder:text-ink-faint focus:border-brass focus:ring-brass/20 font-sans rounded-[3px] text-sm min-h-[44px]']) }}>
