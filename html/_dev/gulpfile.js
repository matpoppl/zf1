import { readFileSync as fread } from 'fs';
import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import livereload from 'gulp-livereload';
import postcss from 'gulp-postcss';
import babel from 'gulp-babel';
import * as sassDart from 'sass';

const sass = gulpSass(sassDart);

console.log(gulp.parallel);

function devSCSS()
{
	return gulp.src('scss/*.scss')
	.pipe(sass().on('error', sass.logError))
	.pipe(postcss())
	.pipe(gulp.dest('../static/css'))
	.pipe(livereload());
}

function devJS()
{
	return gulp.src('js/**/*.js')
	.pipe(babel())
	.pipe(gulp.dest('../static/js'))
	.pipe(livereload());
}

function devFont()
{
	return gulp.src('font/*.{ttf,woff,woff2,eot,svg,otf}')
	.pipe(gulp.dest('../static/font'));
}

function livereloadListen(cb)
{
	livereload.listen({
		port: 35729,
		host: 'zf1.pop-pc.lan',
		// cert: fread(`${process.env.PATH_CERTS}/zf1-cert.pem`),
		// key: fread(`${process.env.PATH_CERTS}/zf1-key.pem`),
	});
	cb();
}

function watch(cb)
{
	gulp.watch('scss/**/*.scss', gulp.series(devSCSS));
	gulp.watch('js/**/*.js', gulp.series(devJS));
	cb();
}

const dev = gulp.parallel(devSCSS, devFont, devJS);

export default gulp.series(dev, livereloadListen, watch);
