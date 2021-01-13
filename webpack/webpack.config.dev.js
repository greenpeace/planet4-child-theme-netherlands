const path = require('path');
const ReactRefreshPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const entries = require('./entries');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');

const devUrl = 'http://www.planet4.test/'; // This must be the same as your site's URL for development.
const proxyPort = 3003; // Any available port will do. Must match port used in PHP to load assets.

module.exports = {
  mode: 'development',
  entry: entries,
  output: {
    path: path.resolve(__dirname, '../public/build/'),
    filename: '[name].js',
    publicPath: 'http://localhost:3003/public/build/',
  },
  resolve: {extensions: ['.js', '.jsx']},
  devtool: 'eval',
  optimization: {
    runtimeChunk: 'single'
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              ['@babel/preset-env'],
              ['@babel/preset-react'],
            ]
          }
        }
      },
      {
        test: /\.s?css$/,
        use: [
          'style-loader',
          'css-loader',
          'sass-loader',
        ]
      },
    ]
  },
  plugins: [
    new ReactRefreshPlugin(),
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['**/*', '!.gitignore'] // Prevent '.gitignore' to be removed.
    })
  ],
  devServer: {
    port: proxyPort,
    headers: {'Access-Control-Allow-Origin': '*'},
    static: [path.resolve(__dirname, '/public/build/')],
    firewall: false,
    proxy: {
      '/': {
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
};
