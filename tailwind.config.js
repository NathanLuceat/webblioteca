import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', 'serif'],
            },
            colors: {
                paper: {
                    DEFAULT: 'rgb(var(--color-paper) / <alpha-value>)',
                    alt: 'rgb(var(--color-paper-alt) / <alpha-value>)',
                    card: 'rgb(var(--color-paper-card) / <alpha-value>)',
                    light: 'rgb(var(--color-paper-light) / <alpha-value>)',
                },
                ink: {
                    DEFAULT: 'rgb(var(--color-ink) / <alpha-value>)',
                    soft: 'rgb(var(--color-ink-soft) / <alpha-value>)',
                    faint: 'rgb(var(--color-ink-faint) / <alpha-value>)',
                },
                leather: {
                    DEFAULT: 'rgb(var(--color-leather) / <alpha-value>)',
                    deep: 'rgb(var(--color-leather-deep) / <alpha-value>)',
                    light: 'rgb(var(--color-leather-light) / <alpha-value>)',
                },
                brass: {
                    DEFAULT: 'rgb(var(--color-brass) / <alpha-value>)',
                    deep: 'rgb(var(--color-brass-deep) / <alpha-value>)',
                    light: 'rgb(var(--color-brass-light) / <alpha-value>)',
                },
                folio: {
                    DEFAULT: 'rgb(var(--color-folio) / <alpha-value>)',
                    deep: 'rgb(var(--color-folio-deep) / <alpha-value>)',
                    light: 'rgb(var(--color-folio-light) / <alpha-value>)',
                },
                line: {
                    DEFAULT: 'rgb(var(--color-line) / <alpha-value>)',
                    soft: 'rgb(var(--color-line-soft) / <alpha-value>)',
                    strong: 'rgb(var(--color-line-strong) / <alpha-value>)',
                },
            },
            boxShadow: {
                folio: 'var(--shadow-folio)',
                'folio-sm': 'var(--shadow-folio-sm)',
                stamp: 'var(--shadow-stamp)',
                press: 'inset 0 1px 2px rgba(94,31,19,.35), 0 1px 0 rgba(255,255,255,.12)',
            },
        },
    },

    plugins: [forms],
};
