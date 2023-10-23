import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.php',
    './resources/js/**/*.js',
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
  plugins: [
    forms,
    require('./resources/js/plugins/customScrollbar'),
    require('./resources/js/plugins/softScrollbar'),
  ],
};
