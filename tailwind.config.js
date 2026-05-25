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
            },
            colors: {
                'trim-blue': '#2563EB',
                'trim-blue-dark': '#1E40AF',
                'trim-blue-light': '#DBEAFE',
                'trim-blue-border': '#93C5FD',
                'trim-white': '#FFFFFF',
                'trim-bg': '#F8FAFC',
            },
        },
    },

    plugins: [forms],
};
