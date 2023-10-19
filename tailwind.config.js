import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './src/**/*.php',
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
