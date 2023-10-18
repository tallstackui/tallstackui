import plugin from 'tailwindcss/plugin';

module.exports = plugin(function({addUtilities}) {
  const utility = {
    '.custom-scrollbar': {
      'scrollbar-width': 'auto',
    },
    '.custom-scrollbar::-webkit-scrollbar': {
      'width': '8px',
    },
    '.custom-scrollbar::-webkit-scrollbar:horizontal': {
      'height': '8px',
    },
    '.custom-scrollbar::-webkit-scrollbar-track': {
      'background': 'transparent',
    },
    '.custom-scrollbar::-webkit-scrollbar-thumb': {
      'background-color': '#c9c9c9',
      'border-radius': '10px',
      'border': 'transparent',
    },
    '.dark .custom-scrollbar::-webkit-scrollbar-track': {
      'background-color': '#475569',
      'cursor': 'pointer',
    },
    '.dark .custom-scrollbar::-webkit-scrollbar-thumb': {
      'cursor': 'pointer',
      'background-color': '#94a3b8',
    },
  };

  addUtilities(utility, ['dark', 'responsive']);
});
