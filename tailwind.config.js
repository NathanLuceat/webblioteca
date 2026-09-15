import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', 'serif'],
            },
            colors: {
                paper: {
                    DEFAULT: '#F2EBDA',
                    alt: '#ECE3CD',
                    card: '#FBF6E9',
                    light: '#FDFAF2',
                },
                ink: {
                    DEFAULT: '#2B2419',
                    soft: '#5C5243',
                    faint: '#85785F',
                },
                leather: {
                    DEFAULT: '#7E2E1E',
                    deep: '#5E1F13',
                    light: '#9A4634',
                },
                brass: {
                    DEFAULT: '#A97F2F',
                    deep: '#82601F',
                    light: '#C9A45C',
                },
                folio: {
                    DEFAULT: '#2F4535',
                    deep: '#223325',
                    light: '#5C7854',
                },
                line: {
                    DEFAULT: '#D9CBAD',
                    soft: '#E5DAC2',
                    strong: '#C4B18C',
                },
            },
            boxShadow: {
                folio: '0 1px 0 rgba(43,36,25,.04), 0 6px 18px -8px rgba(43,36,25,.22)',
                'folio-sm': '0 1px 0 rgba(43,36,25,.05), 0 3px 8px -4px rgba(43,36,25,.18)',
                stamp: '0 1px 0 rgba(43,36,25,.06)',
                press: 'inset 0 1px 2px rgba(94,31,19,.35), 0 1px 0 rgba(255,255,255,.12)',
            },
        },
    },

    plugins: [forms],
};
