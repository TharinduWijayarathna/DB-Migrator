import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: {
                    background: '#0d1729cc', // Dark navy background for the body
                    navbar: '#0F172A', // Darker color for the navbar
                    text: '#E5E7EB',
                    secondary: '#334155', // Dark blue for secondary elements (e.g., shadows, borders)
                    hover: '#2563EB', // A blue accent color for hover states
                },
            },
        },
    },

    plugins: [forms],
};
