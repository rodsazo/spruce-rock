module.exports = {
    js : {
        sources : [
            './src/js/*.js'
        ],
        name : 'main.js',
        destination : './dist/js'
    },

    css : {
        sources: './src/scss/**/*.scss',
        destination: './dist/css',
        modules_dir : './src/scss/modules/',
        modules_index : './src/scss/_modules-index.scss'
    }
}