const path = require('path');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

const entries = require('./entries');

module.exports = {
  entry: entries,
  resolve: {
    extensions: ['.js', '.jsx'],
    alias: {
      main: path.resolve(__dirname, '../assets/main'),
    },
  },
  output: {
    path: path.resolve(__dirname, '../public/build/'),
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
      {
        test: /\.(png|jpe?g|gif)$/i,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]',
          outputPath: 'images'
        }
      },
      {
        test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new DependencyExtractionWebpackPlugin(),
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['**/*', '!.gitignore'] // Prevent '.gitignore' to be removed.
    })
  ],
};
