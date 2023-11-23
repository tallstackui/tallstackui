import plugin from 'tailwindcss/plugin';

module.exports = plugin(function({addComponents, addUtilities}) {
  addUtilities({
    '.animate-progress': {
      animation: 'progress 2s ease-in-out infinite',
    },
  });

  addComponents({
    '@layer utilities': {
      '@keyframes progress': {
        '0%': {
          width: '0',
        },
        '100%': {
          width: '100%',
        },
      },
    },
  });
});
