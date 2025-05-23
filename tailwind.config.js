import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './src/**/*.php',
        './js/**/*.js',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: colors.indigo,
                secondary: colors.slate,
                dark: colors.slate,
            },
            keyframes: {
                progress: {
                    '0%': {width: '0%'},
                    '100%': {width: '100%'},
                },
            },
            animation: {
                progress: 'progress 2s linear infinite',
            },
        },
    },
    safelist: [
        '!overflow-hidden',
        'z-0',
        'z-10',
        'z-20',
        'z-30',
        'z-40',
        'z-50',
        'z-auto',
    ],
    plugins: [
        forms,
        require('./js/plugins/customScrollbar'),
        require('./js/plugins/softScrollbar'),
        require('./js/plugins/numberAppearanceNone'),
    ],
};
