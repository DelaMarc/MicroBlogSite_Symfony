let tailwindcss = require("tailwindcss")

module.exports = {
    plugins: [
        tailwindcss('./tailwinf.config.js'),
        require('postcss-import'),
        require('autoprefixer')
    ]
}