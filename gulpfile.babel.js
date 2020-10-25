import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import del from 'del';
import merge from 'merge-stream';
import webpack from 'webpack-stream';
import named from 'vinyl-named';
import browserSync from 'browser-sync';

const PRODUCTION = yargs.argv.prod;

export const styles = () => {
    const folders = ['admin', 'public'];

    let tasks = folders.map(function(directory) {

        const autoprefixer = require('autoprefixer');
        const sourcemaps = require('gulp-sourcemaps');
        const postcss = require('gulp-postcss');
        return src('src/' + directory + '/scss/**/*.scss')
            .pipe(sass().on('error', sass.logError))
            .pipe(gulpif(PRODUCTION, postcss([autoprefixer({
                cascade: false
            })])))
            .pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'})))
            .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
            .pipe(dest('dist/' + directory + '/css'))
            .pipe(server.stream());
    });
    return merge(tasks);
}

export const scripts = () => {

    const folders = ['admin', 'public'];

    let tasks = folders.map(function(directory) {
        return src('src/' + directory + '/js/*.js')
            .pipe(named())
            .pipe(webpack({
                module: {
                    rules: [
                        {
                            test: /\.js$/,
                            use: {
                                loader: 'babel-loader',
                                options: {
                                    presets: []
                                }
                            }
                        }
                    ]
                },
                mode: PRODUCTION ? 'production' : 'development',
                devtool: !PRODUCTION ? 'inline-source-map' : false,
                output: {
                    filename: '[name].js'
                },
                externals: {
                    jquery: 'jQuery'
                },
            }))
            .pipe(dest('dist/' + directory + '/js'));
    });
    return merge(tasks);
}

const server = browserSync.create();
export const serve = done => {
    server.init({
        proxy: "http://wowpi.test"
    });
    done();
};

export const reload = done => {
    server.reload();
    done();
}

export const watchForChanges = () => {
    watch('src/{admin,public}/scss/**/*.scss', styles);
    watch('src/{admin,public}/js/**/*.js', series(scripts, reload));
    watch('**/*.php', reload);
}

export const clean = () => del(['dist']);

export const dev = series(clean, parallel(styles,scripts), serve, watchForChanges);
export const build = series(clean, parallel(styles,scripts))
export default dev;