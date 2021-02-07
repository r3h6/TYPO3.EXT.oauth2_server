const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    watch: true,
    entry: {
        'main': './src/Sass/main.scss'
    },
    output: {
        path: path.resolve(__dirname, '../Resources/Public')
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "Css/[name].css",
            chunkFilename: "[id].css"
        })
    ],
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [MiniCssExtractPlugin.loader, "css-loader", "sass-loader"]
            }
        ]
    }
};
