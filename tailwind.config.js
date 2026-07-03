import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, daisyui],

    daisyui: {
        themes: [
            {
                light: {
                    "primary": "#4f46e5",
                    "secondary": "#7c3aed",
                    "accent": "#06b6d4",
                    "neutral": "#374151",
                    "base-100": "#ffffff",
                    "base-200": "#f3f4f6",
                    "base-300": "#e5e7eb",
                    "base-content": "#111827",
                    "info": "#3b82f6",
                    "success": "#10b981",
                    "warning": "#f59e0b",
                    "error": "#ef4444",
                },
            },
            {
                dark: {
                    "primary": "#818cf8",
                    "secondary": "#a78bfa",
                    "accent": "#22d3ee",
                    "neutral": "#1e293b",
                    "base-100": "#0f172a",
                    "base-200": "#1e293b",
                    "base-300": "#334155",
                    "base-content": "#e2e8f0",
                    "info": "#60a5fa",
                    "success": "#34d399",
                    "warning": "#fbbf24",
                    "error": "#f87171",
                },
            },
        ],
        darkTheme: "dark",
    },
};

