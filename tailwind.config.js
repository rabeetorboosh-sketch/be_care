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
            colors: {
                primary: '#0A558C',
                secondary: '#2B8BCD',
                accent: '#E9B000',
                danger: '#F44336',
                warning: '#FFC107',
                success: '#4CAF50',
            }
        }
    },
    plugins: [forms],
};
