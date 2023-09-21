module.exports = {
  'env': {
    'browser': true,
    'es2021': true,
  },
  'extends': 'google',
  'overrides': [
  ],
  'parserOptions': {
    'ecmaVersion': 'latest',
    'sourceType': 'module',
  },
  'rules': {
    "max-len": ["error", { "code": 130 }],
    "valid-jsdoc": ["off"],
  },
};
