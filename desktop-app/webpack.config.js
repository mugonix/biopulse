const isProduction = process.env["NODE_ENV"] === "production"


module.exports = {
    externals: {
        "agora-electron-sdk": "commonjs2 agora-electron-sdk",
        "serialport": 'commonjs2 serialport'
    },
    watch: isProduction ? false : true,

    target: 'electron-renderer',

    entry: './src/renderer/index.js',

    output: {
        path: __dirname + '/build',
        publicPath: 'build/',
        filename: 'bundle.js'
    },

    module: {
        rules: [{
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.(png|jpg|gif|svg)$/,
                loader: 'file-loader',
                options: {
                    esModule: false,
                    query: {
                        name: '[name].[ext]?[hash]'
                    },
                }
            }
        ]
    },

    resolve: {
        alias: { vue: 'vue/dist/vue.js' }
    },

}