<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-line-strong text-leather font-display font-semibold text-[13px] uppercase tracking-[0.06em] shadow-stamp hover:border-leather/60 hover:text-leather-deep hover:bg-leather/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-leather/50 focus-visible:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
