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
        black: {
          DEFAULT: colors.black,
          '50': '#f6f6f6',
          '100': '#e7e7e7',
          '200': '#d1d1d1',
          '300': '#b0b0b0',
          '400': '#888888',
          '500': '#6d6d6d',
          '600': '#5d5d5d',
          '700': '#4f4f4f',
          '800': '#454545',
          '900': '#3d3d3d',
          '950': '#000000',
        }
      },
    },
    keyframes: {
      progress: {
        '0%': { width: '0' },
        '100%': { width: '100%' },
      }
    },
    animation: {
      progress: 'progress 2s ease-in-out infinite',
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
