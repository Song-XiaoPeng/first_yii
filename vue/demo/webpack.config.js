/**
 * Created by smz on 17/6/13.
 */
/* 引入操作路径模块和webpack */
var path = require('path');
var CommonsChunkPlugin = new require("./node_modules/webpack/lib/optimize/CommonsChunkPlugin");
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var UglifyJSPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
	entry: {
		'login': path.resolve(__dirname, './src/pages/login/index.js'),
		'user': path.resolve(__dirname, './src/pages/user/index.js')
	},
	output: {
		path: path.resolve(__dirname, './dist/js'),
		filename: '[name].js'
	},
	plugins: [
		new CommonsChunkPlugin({
			name: 'common',
			minChunks: 2
		}),
		new ExtractTextPlugin('[name].css'),
		new UglifyJSPlugin()
	],
	module: {
		rules: [{
			test: /\.(less|css)$/,
			use: ExtractTextPlugin.extract({
				fallback: 'style-loader',
				use: ['css-loader', 'less-loader']
			})
		}],
		loaders: [
			{
				test: /\.(less|css)$/,
				loader: ExtractTextPlugin.extract({ fallback: 'style-loader', use: ['css-loader', 'less-loader'] })
			},
			{
				test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
				loader: ExtractTextPlugin.extract('url-loader?limit=10000&mimetype=application/font-woff')
			},
			{
				test: /\.(ttf|eot|svg)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
				loader: ExtractTextPlugin.extract('file-loader')
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i,
				loader: ExtractTextPlugin.extract('file-loader')
			}
		]
	}
};