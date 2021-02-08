const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const commonConfig = require('./webpack.common');
const {merge} = require('webpack-merge');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');

module.exports = () => {
  const config = {
    output: {
      filename: '[name].js',
      publicPath: '/nl/wp-content/themes/planet4-child-theme-netherlands/public/build/'
    },
    mode: 'production',
    devtool: 'cheap-source-map',
    optimization: {
      minimize: true
    },
    module: {
      rules: [
        {
          test: /\.s?css$/,
          use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader',]
        },
      ]
    },
    plugins: [
      new MiniCssExtractPlugin({filename: '[name].css'}),
      new CssMinimizerPlugin(),
      new WebpackManifestPlugin({generate: generateManifest}),
    ],
  };

  return merge(commonConfig, config);
};

//(seed: Object, files: FileDescriptor[], entries: string[]) => Object
function generateManifest(seed, files){
  let assets ={};
  files.forEach(function(file) {
    if (file.isChunk === false) {
      return;
    }
    let name = file.name;
    let contentHash = file.chunk.contentHash;
    assets[name] = (name.includes('.js')? contentHash.javascript : contentHash['css/mini-extract']);

  });
  return assets;
}
