'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
 
gulp.task('sass', function () {
	gulp.src('./assets/sass/styles/**/*.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(gulp.dest('./assets/css/compiled'));
});

gulp.task('apf', function () {
	setTimeout(function () {
		gulp.src('./assets/css/compiled/**/*.css')
		.pipe(autoprefixer({
			browsers: ['last 10 versions'],
			cascade: false
		}))
		.pipe(gulp.dest('assets/css/prefixes'));
	}, 1000);
});

gulp.task('default', function () {
	gulp.watch('./assets/sass/**/*.scss', ['sass', 'apf']);
});
