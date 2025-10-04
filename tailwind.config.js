import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        // Dynamic gradient classes
        'from-blue-400', 'to-blue-600',
        'from-green-400', 'to-green-600',
        'from-purple-400', 'to-purple-600',
        'from-red-400', 'to-red-600',
        'from-yellow-400', 'to-yellow-600',
        'from-pink-400', 'to-pink-600',
        'from-indigo-400', 'to-indigo-600',
        'from-orange-400', 'to-orange-600',
        'from-teal-400', 'to-teal-600',
        'from-cyan-400', 'to-cyan-600',
        // Background colors
        'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-red-500', 'bg-yellow-500',
        'bg-pink-500', 'bg-indigo-500', 'bg-orange-500', 'bg-teal-500', 'bg-cyan-500',
        // Border colors
        'border-blue-200', 'border-green-200', 'border-purple-200', 'border-red-200',
        'border-yellow-200', 'border-pink-200', 'border-indigo-200', 'border-orange-200',
        'border-teal-200', 'border-cyan-200',
        // Text colors
        'text-blue-600', 'text-green-600', 'text-purple-600', 'text-red-600',
        'text-yellow-600', 'text-pink-600', 'text-indigo-600', 'text-orange-600',
        'text-teal-600', 'text-cyan-600',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
