/* global require, exports */

const gulp = require('gulp');
const stylelint = require('gulp-stylelint');
const eslint = require('gulp-eslint');
const js = require('gulp-uglify-es').default;
const concat = require('gulp-concat');
const scss = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const cleancss = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const notify = require('gulp-notify');
const plumber = require('gulp-plumber');
const livereload = require('gulp-livereload');

const path_js = 'assets/js/src/**/*.js';
const path_scss = 'assets/scss/**/*.scss';
const path_style = 'assets/scss/style.scss';
const path_dest = 'assets/';


let error_handler = {
	errorHandler: notify.onError({
		title: 'Gulp',
		message: 'Error: <%= error.message %>'
	})
};

function lint_css() {
	return gulp.src(path_scss)
		.pipe(plumber(error_handler))
		.pipe(stylelint({
			reporters: [{ formatter: 'string', console: true}]
		}))
		.pipe(livereload());
}

function fix_css() {
  return gulp.src(path_scss)
    .pipe(plumber(error_handler))
    .pipe(stylelint({
      reporters: [{ formatter: 'string', console: true}],
      fix: true
    }))
    .pipe(gulp.dest('assets/scss'))
//  This outputs to 'assets/scss/**/*.scss', manual copy to right directory for now..
}

function lint_js() {
	return gulp.src(path_js)
		.pipe(plumber(error_handler))
		.pipe(eslint())
		.pipe(eslint.format())
		.pipe(eslint.failAfterError())
		.pipe(livereload());
}

// TODO configure gulp-sass-glob to auto include all .scss files
function sass() {
	return gulp.src(path_style)
		.pipe(plumber(error_handler))
		.pipe(sourcemaps.init())
		.pipe(scss().on('error', scss.logError))
    .pipe(autoprefixer())
		.pipe(cleancss({rebase: false, level :2}))
		.pipe(sourcemaps.write('/maps/'))
		.pipe(gulp.dest(path_dest + '/css'))
		.pipe(livereload());
}

function uglify() {
	return gulp.src(path_js)
		.pipe(plumber(error_handler))
		.pipe(sourcemaps.init())
		.pipe(concat('main.js'))
		.pipe(js())
		.pipe(sourcemaps.write('/maps/'))
		.pipe(gulp.dest(path_dest + '/js'))
		.pipe(livereload());
}

function watch() {
	livereload.listen({'port': 35729});
	gulp.watch(path_scss, gulp.series(lint_css, sass));
	// gulp.watch(path_js, gulp.series(lint_js, uglify));
}

exports.fix = fix_css;
exports.sass = sass;
exports.uglify = uglify;
exports.watch = watch;
exports.test = gulp.parallel(lint_css);
// exports.test = gulp.parallel(lint_css, lint_js);
// exports.default = gulp.series(lint_css, lint_js, sass, uglify);
exports.default = gulp.series(lint_css, sass);
