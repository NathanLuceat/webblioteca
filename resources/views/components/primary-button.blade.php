<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-leather text-paper-light font-display font-semibold text-[13px] uppercase tracking-[0.06em] border border-leather-deep shadow-press focus:outline-none focus-visible:ring-2 focus-visible:ring-brass focus-visible:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
