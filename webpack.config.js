const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const outputDir = "./www/assets"

module.exports = (env) => {
  return [{
    entry: {
        main: ['./www/js/app.js', './www/scss/app.scss'],
    },
    output: {
      path: path.join(__dirname, outputDir),
      filename: 'js/[name].js',
    },
    module: {
      rules: [
        {
          test: /\.js$/,
            exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
          }
        },
        {
          test: /\.scss$/i,
          use: [
            MiniCssExtractPlugin.loader,
            'css-loader',
            'sass-loader',
            'postcss-loader'
          ],
          
        },
      ],
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: 'css/main.css',
      }),
    ],
  }]
}
