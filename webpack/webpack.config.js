const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env) => {
	const devMode = env.build !== 'production';
	console.log('production: ' + env.build);
	
	return {
		mode: devMode ? 'development' : 'production',
		entry: {
			custom_fields: '../src/document-type-custom-fields.js',
		},
		output: {
			filename: '[name].document-type-custom-fields.js',
			path: path.resolve(__dirname, '../backend/web/js'),
		},
		module: {
			rules: [
				{
					test: /\.(c|ca|cs)ss$/i,
					use: [
						devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
						'css-loader',
						//'postcss-loader',
						//'sass-loader,'
					],
				},
			],
		},
		plugins: [].concat(devMode ? [] : [new MiniCssExtractPlugin()]),
		watch: devMode,
		watchOptions: {
			ignored: ['vendor/**', 'frontend/**', 'node_modules/**'],
		},
		devtool: devMode ? 'source-map' : false,
	}
};
