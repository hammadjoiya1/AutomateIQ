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
                display: ['Outfit', ...defaultTheme.fontFamily.sans],
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

                input: 'var(--input-bg)',
                'input-text': 'var(--input-text)',
                ring: 'var(--focus-ring)',

                // Keep accents for backward compat if needed, or map to primary
                accent: 'var(--primary)',
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
                }
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.8s ease-out forwards',
                'fade-in': 'fade-in 1s ease-out forwards',
                'float': 'float 6s ease-in-out infinite',
                'scroll': 'scroll 30s linear infinite',
            },
        },
    },

    plugins: [forms, require('@tailwindcss/typography')],
};
