const path = require('path');
const webpack = require('webpack');

module.exports = {
  entry: {
    index: './src/index.js',
    data: './src/data.js',
    login: './src/login.js'
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist/js/'),
    publicPath: '/dist/js/'
  }
};