/**
 * Created by smz on 17/6/13.
 */
/* 引入操作路径模块和webpack */
var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
	/* 输入文件 */
	entry: './src/main.js',
	output: {
		/* 输出目录，没有则新建 */
		path: './dist',
		/* 静态目录，可以直接从这里取文件 */
		publicPath: '/dist/',
		/* 文件名 */
		filename: 'build.js'
	},
	module: {
		rules: [
			{
				test: /\.css$/,
				use: ExtractTextPlugin.extract({
					fallback: "style-loader",
					use: ["css-loader","less-loader"]
				})
			},
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			}
		]
	},
	plugins: [
		new ExtractTextPlugin("styles.css"),
	]
}