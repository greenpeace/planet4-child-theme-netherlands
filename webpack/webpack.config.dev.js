const path = require('path');
const ReactRefreshPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const entries = require('./entries');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const commonConfig = require('./webpack.common')
const {merge} = require('webpack-merge');

const devUrl = 'http://www.planet4.test/'; // This must be the same as your site's URL for development.
const proxyPort = 3003; // Any available port will do. Must match port used in PHP to load assets.

module.exports = () => {
  const config = {
    mode: 'development',
    output: {
      path: path.resolve(__dirname, "../public/build/"),
      filename: '[name].js',
      publicPath: 'http://localhost:3003/public/build/',
    },
    devtool: 'eval',
    optimization: {
      runtimeChunk: "single"
    },
    plugins: [
      new ReactRefreshPlugin(),
    ],
    devServer: {
      port: proxyPort,
      firewall: false,
      headers: {"Access-Control-Allow-Origin": "*"},
      static: [path.resolve(__dirname, "/public/build/")],
      proxy: {
        "/": {
          target: devUrl,
          secure: false,
          changeOrigin: true,
          autoRewrite: true,
          headers: {
            Connection: 'keep-alive'
          }
        }
      }
    }
  }
  return merge(commonConfig, config)
};
