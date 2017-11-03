/**
 * Created by smz on 17/6/12.
 */
var path = require('path');
module.exports = {
	entry: './src/main.js',
	//定义webpack输出的文件，我们在这里设置了
	//让打包后生成的文件放在dist文件夹下的build.js文件中
	output: {
		path: './dist',
			publicPath:'dist/',
			filename: 'build.js'
	},
	module:{
		rules:[
			{
				test: /\.vue$/,
				loader: 'vue',
				options: {
					loaders:{
						css: extractTextPlugin.extract({
							loader: 'css-loader',
							fallbackLoader: 'vue-style-loader'
						})
					}
				}
			}
		]
	},
	plugins: [
		new webpack.HotModuleReplacementPlugin(),
		new extractTextPlugin({
			filename:'/style.css',
			allChunks:true
		})
	],
}