import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                pikr: {
                    primary: '#10B981', // Emerald 500
                    secondary: '#059669', // Emerald 600
                    accent: '#34D399', // Emerald 400
                    dark: '#0F172A', // Slate 900
                    darkgreen: '#1E293B', // Slate 800
                    emerald: '#334155', // Slate 700
                    light: '#F8FAFC',
                    verylight: '#F1F5F9',
                    background: '#0B1120', // Very dark slate
                    text: '#F8FAFC',
                    muted: '#94A3B8',
                }
            }
        },
    },

    plugins: [forms],
};
