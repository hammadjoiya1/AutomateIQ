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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                display: ['Space Grotesk', ...defaultTheme.fontFamily.sans],
                mono: ['Space Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                bg: 'var(--color-bg)',
                surface: 'var(--color-surface)',
                'surface-raised': 'var(--color-surface-raised)',
                border: 'var(--color-border)',
                text: 'var(--color-text)',
                'text-muted': 'var(--color-text-muted)',
                accent: 'var(--color-accent)',
                'accent-hover': 'var(--primary-hover)',
                'accent-tint': 'var(--color-accent-dim)',
                success: 'var(--color-signal)',
                warning: 'var(--color-warn)',
                danger: 'var(--danger)',

                // Legacy aliases for compatibility
                background: 'var(--bg)',
                card: 'var(--card)',
                primary: {
                    DEFAULT: 'var(--primary)',
                    hover: 'var(--primary-hover)',
                },
                secondary: {
                    DEFAULT: 'var(--secondary)',
                    hover: 'var(--secondary-hover)',
                },
                signal: 'var(--color-signal)',
                'signal-dim': 'var(--color-signal-dim)',
                'accent-dim': 'var(--color-accent-dim)',
                input: 'var(--input-bg)',
                'input-text': 'var(--input-text)',
                ring: 'var(--focus-ring)',
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
