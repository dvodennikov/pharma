const path = require('path');

module.exports = {
	entry: {
		custom_fields: {import: '../src/custom-fields.js', filename: 'js/custom-fields.js'},
	},
	output: {
		/*filename: '[name].document-type-custom-fields.js',*/
		path: path.resolve(__dirname, '../backend/web/'),
	},
};
