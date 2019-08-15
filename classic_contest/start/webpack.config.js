const path = require('path');
const entryPath = 'src/js/';
module.exports = {
  mode: 'development',
  entry: {
    // '[output-path]' : path.resolve(__dirname, 'themes/default/js/[input-file-path]'),
    'index/main': path.resolve(__dirname, entryPath + 'index/MainComponent.js'),
  },
  output: {
    path: path.resolve(__dirname, 'public/js/'),
    filename: '[name].js'
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  watch: true
};