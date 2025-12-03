const minifyCSS     = require('gulp-minify-css');
const sass          = require('gulp-sass')(require('sass'));
const sources  = require( "./sources.js");
const sourcemaps = require( "gulp-sourcemaps");
const postcss = require( "gulp-postcss");
const autoprefixer = require( "autoprefixer");
const gulp = require( "gulp");

const browserCacheBust = require( "./browserCacheTask");

// sass.compiler       = require('dart-sass');

module.exports = function scssTask() {

    browserCacheBust();

    return gulp.src(sources.css.sources, {sourcemaps: true})
        .pipe(sass({
            outputStyle: 'compressed',
            quiet: true,
            silenceDeprecations: ['legacy-js-api'] // todo: cómo se resuelve realmente? https://sass-lang.com/documentation/breaking-changes/legacy-js-api/
        }).on('error', sass.logError))
        .pipe(postcss([autoprefixer()]))
        .pipe(gulp.dest(sources.css.destination));

}