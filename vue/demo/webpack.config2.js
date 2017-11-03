var path = require('path');
var autoprefixer = require('autoprefixer');
var precss = require('precss');
var webpack = require('webpack');
//plugin
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var CleanWebpackPlugin = require('clean-webpack-plugin');
var AssetsPlugin = require('assets-webpack-plugin');
var assetsPluginInstance = new AssetsPlugin({
	filename: path.join(__dirname, '..', 'web', 'mobile', 'dist', 'manifest.json')
});
var plugins = [];
var entry = {
	app: './web/mobile/src/index.jsx'
};
if (process.env.NODE_ENV === 'production') {
	plugins = [
		new CleanWebpackPlugin(['dist'], {
			verbose: true,
			root: path.join(__dirname, '..', 'web', 'mobile')
		}),
		new webpack.optimize.CommonsChunkPlugin({
			name: "vendor",
			minChunks: Infinity
		}),
		new webpack.optimize.UglifyJsPlugin({
			compress: {
				warnings: false
			}
		}),
		new webpack.DefinePlugin({
			"process.env": {
				NODE_ENV: JSON.stringify("production")
			}
		}),
		new ExtractTextPlugin("[name].[hash].css"),
		assetsPluginInstance
	];
	entry.vendor = ['react', 'react-router', 'react-dom', 'moment', 'amazeui-touch', 'underscore', 'whatwg-fetch'];
} else {
	plugins = [
		new ExtractTextPlugin("style.css")
	];
}
module.exports = {
	entry: entry,
	output: {
		publicPath: '/dist/',
		filename: process.env.NODE_ENV === 'production' ? '[name].[hash].js' : 'mobile.bundle.js',
		path: './dist'
	},
	postcss: function () {
		return {
			defaults: [precss, autoprefixer],
			cleaner: [autoprefixer({browsers: ["ios >= 7", "android >= 4.0"]})]
		};
	},
	module: {
		loaders: [
			{test: /\.js$/, exclude: /node_modules/, use: 'babel'},
			{
				test: /.jsx?$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
				query: {
					presets: ['es2015', 'react']
				}
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i,
				loaders: [
					"file?name=img/[hash:16].[ext]",
					"image-webpack?bypassOnDebug&optimizationLevel=9&interlaced=false"
				]
			},
			//{test: /\.css$/, loader: ExtractTextPlugin.extract('style', 'css!postcss')},
			{test: /\.css$/, use: ExtractTextPlugin.extract({fallback:'style-loader', use:'css-loader!postcss-loader'})},
			{test: /\.scss$/, use: ExtractTextPlugin.extract({fallback:'style-loader', use:'css-loader!postcss!sass-loader'})},
			{test: /\.(eot|svg|ttf|woff)/, use: 'file?name=fonts/[hash].[ext]'},
		]
	},
	resolve: {
		extensions: ['', '.js', '.jsx']
	},
	plugins: plugins
};