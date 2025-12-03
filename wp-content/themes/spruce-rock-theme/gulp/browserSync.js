const browserSync   = require('browser-sync').create();
const gulp = require('gulp');

const project_url = 'http://localhost';

module.exports = {

    browserSyncInit(done) {
        browserSync.init({
            open: false,
            proxy: project_url,
            notify: false
        });
        done();
    },

    browserWatch() {

        // Php and JS files (need reload)
        gulp.watch([
            './**/*.php',
            './*.php',
            './dist/js/main.js'
        ]).on('change', browserSync.reload);

        // CSS Files (no reload needed)
        gulp.watch([
            './dist/css/app.css'
        ]).on('change', function () {
            gulp.src('./dist/css/app.css')
                .pipe(browserSync.stream());
        });

    }

}
