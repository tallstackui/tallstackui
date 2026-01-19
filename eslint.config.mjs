import globals from 'globals'
import js from '@eslint/js';

export default [
  js.configs.recommended,
  {
    rules: {
      'no-unused-vars': 'warn',
      'no-undef': 'off',
      'quotes': ['warn', 'single'],
      'max-len': ['warn', {'code': 130}],
      'valid-jsdoc': 'off',
      'linebreak-style': 'off',
    },
    languageOptions: {
      parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module',
      },
      globals: {
        ...globals.browser,
        ...globals.es2021
      }
    },
  }
];
