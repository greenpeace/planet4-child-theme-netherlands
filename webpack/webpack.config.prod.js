const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const entries = require('./entries');
const path = require('path');

module.exports = {
  entry: entries,
  output: {
    path: path.resolve(__dirname, '../public/build/'),
    filename: '[name].[contenthash].js',
  },
  resolve: {extensions: ['.js', '.jsx']},
  mode: 'production',
  watch: false,
  devtool: 'cheap-source-map',
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            cacheDirectory: true,
            presets: [
              ['@babel/preset-env'],
              ['@babel/preset-react'],
            ]
          }
        }
      },
      {
        test: /\.s?css$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader',]
      },
    ]
  },
  optimization: {
    minimize: true
  },
  plugins: [
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['**/*', '!.gitignore'] // Prevent '.gitignore' to be removed.
    }),
    new MiniCssExtractPlugin({filename: '[name].[contenthash].css'}),
    new CssMinimizerPlugin()
  ],
};
