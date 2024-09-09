const { merge } = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');

module.exports = merge(common, {
	mode: 'development',
	module: {
		rules: [
			{
				test: /\.(c|ca|cs)ss$/i,
				use: [
					'style-loader',
					'css-loader',
					//'postcss-loader',
					//'sass-loader,'
				],
				include: path.resolve(__dirname, '../src/'),
				sideEffects: true,
			},
		],
	},
	watch: true,
	watchOptions: {
		ignored: ['./vendor/**', './frontend/**', './node_modules/**'],
	},
	devtool: 'source-map',
})
