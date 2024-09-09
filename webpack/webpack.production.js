const { merge } = require('webpack-merge');
const path = require('path');
const miniCssExtractPlugin = require('mini-css-extract-plugin');
const common = require('./webpack.common.js');

module.exports = merge(common, {
	mode: 'production',
	entry: {
		site: {import: '../src/site.css', filename: 'css/[name].css'},
	},
	module: {
		rules: [
			{
				test: /\.(c|ca|cs)ss$/i,
				use: [
					miniCssExtractPlugin.loader,
					'css-loader',
					//'postcss-loader',
					//'sass-loader,'
				],
				include: path.resolve(__dirname, '../src/'),
				sideEffects: true,
			},
		],
	},
	plugins: [new miniCssExtractPlugin()],
});
