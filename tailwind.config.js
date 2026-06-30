import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Archivo', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                background: 'var(--bg)',
                surface: 'var(--bg-2)',
                card: 'var(--card)',

                text: 'var(--text)',
                'text-muted': 'var(--muted-text)',

                border: 'var(--border)',

                primary: {
                    DEFAULT: 'var(--primary)',
                    hover: 'var(--primary-hover)',
                },
                secondary: {
                    DEFAULT: 'var(--secondary)',
                    hover: 'var(--secondary-hover)',
                },

                danger: 'var(--danger)',
                success: 'var(--success)',
                warning: 'var(--warning)',

                signal: 'var(--color-signal)',
                'signal-dim': 'var(--color-signal-dim)',
                'accent-dim': 'var(--color-accent-dim)',

                input: 'var(--input-bg)',
                'input-text': 'var(--input-text)',
                ring: 'var(--focus-ring)',

                accent: 'var(--color-accent)',
            },
            keyframes: {
                'fade-in-up': {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(20px)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)'
                    },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                scroll: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                marquee: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-100%)' },
                },
                marquee2: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(0%)' },
                }
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.8s ease-out forwards',
                'fade-in': 'fade-in 1s ease-out forwards',
                'float': 'float 6s ease-in-out infinite',
                'scroll': 'scroll 30s linear infinite',
                'marquee': 'marquee 25s linear infinite',
                'marquee2': 'marquee2 25s linear infinite',
            },
            borderRadius: {
                'control-sm': 'var(--radius-sm)',
                'control-md': 'var(--radius-md)',
                'control-lg': 'var(--radius-lg)',
            },
        },
    },

    plugins: [forms, require('@tailwindcss/typography')],
};
