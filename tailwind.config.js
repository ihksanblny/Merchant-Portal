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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                surface: {
                    DEFAULT: '#f8f9fa',
                    low: '#f3f4f5',
                    lowest: '#ffffff',
                },
                brand: {
                    DEFAULT: '#3525cd',
                    container: '#4f46e5',
                }
            },
            boxShadow: {
                'ambient': '0 20px 40px rgba(70, 69, 85, 0.06)',
            }
        },
    },

    plugins: [forms],
};
