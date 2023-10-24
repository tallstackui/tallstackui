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
    },
  },
  safelist: [
    '!overflow-hidden',
  ],
  plugins: [
    forms,
    require('./js/plugins/customScrollbar'),
    require('./js/plugins/softScrollbar'),
  ],
};
