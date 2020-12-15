const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require("path");

module.exports = {
  ...defaultConfig,
  entry: {
    app: "./assets/app/index.js", // The JS-index also contains scss files which will output with [name].css
    bootstrap: "./assets/bootstrap/index.js"
  },
  output: {
    path: path.resolve(__dirname, "public/build"),
    filename: '[name].min.[contenthash].js',
    publicPath: 'public/build'
  },
  watch: true,
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']}

        }
      },
      {
        test: /\.s?css$/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          'postcss-loader',
          "sass-loader"
        ]
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({
      filename: "[name].min.[contenthash].css"
    })
  ]
};
