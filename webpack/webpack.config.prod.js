const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const commonConfig = require('./webpack.common')
const {merge} = require('webpack-merge');

module.exports = () => {
  const config = {
    output: {
      filename: '[name].[contenthash].js',
      publicPath: ""
    },
    mode: 'production',
    watch: true,
    devtool: 'cheap-source-map',
    optimization: {
      minimize: true
    },
    module: {
      rules: [
        {
          test: /\.s?css$/,
          use: [MiniCssExtractPlugin.loader, "css-loader", 'postcss-loader', "sass-loader",]
        },
      ]
    },
    plugins: [
      new MiniCssExtractPlugin({filename: '[name].[contenthash].css'}),
      new CssMinimizerPlugin()
    ],
  }
  return merge(commonConfig, config)
};
