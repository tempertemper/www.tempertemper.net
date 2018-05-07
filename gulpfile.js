const gulp = require('gulp');
const fractal = require('./fractal.js');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const notify = require('gulp-notify');
const browserSync = require('browser-sync').create();
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const logger = fractal.cli.console;

const paths = {
  src: {
    styles: 'src/scss/**/*.scss',
    scripts: 'src/js/**/*',
    images: 'src/img/**/*',
    fonts: 'src/fonts/**/*'
  },
  dist: {
    styles: 'web/cms/addons/feathers/tempertemper/css',
    scripts: 'web/cms/addons/feathers/tempertemper/js',
    images: 'web/cms/addons/feathers/tempertemper/img',
    fonts: 'web/cms/addons/feathers/tempertemper/fonts'
  }
};

gulp.task('copy', function() {
  gulp.src('./node_modules/html5shiv/dist/html5shiv.min.js')
  .pipe(gulp.dest(paths.dist.scripts));
  gulp.src('./node_modules/responsive-nav/responsive-nav.min.js')
  .pipe(gulp.dest(paths.dist.scripts));
  gulp.src(paths.src.fonts)
  .pipe(gulp.dest(paths.dist.fonts));
});

//
// Compile SCSS and autoprefix styles.
//
gulp.task('styles', function () {
  return gulp.src(paths.src.styles)
    .pipe(sourcemaps.init())
    .pipe(
      sass({
        outputStyle: 'compressed'
      })
      .on('error', sass.logError)
      .on('error', notify.onError(function (error) {
        return {
          title: 'SASS error',
          message: error.message
        }
      }))
    )
    .pipe(autoprefixer({
      browsers: [
        '> 1%',
        'last 2 version',
        'ie 8',
        'ie 9',
      ],
      flexbox: false
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.dist.styles))
    .pipe(browserSync.stream());
});

//
// Concatenate and uglify JavaScript
//
gulp.task('scripts', function() {
  return gulp.src([
      './src/js/**/*.js'
    ])
    .pipe(concat('production.js'))
    .pipe(uglify())
    .pipe(gulp.dest(paths.dist.scripts));
});

gulp.task('build', ['scripts', 'styles']);

gulp.task('watch', ['build'], function () {
  gulp.watch(paths.src.styles, ['styles']);
  gulp.watch(paths.src.scripts, ['scripts']);
});

gulp.task('serve', ['watch'], function(){
  const server = fractal.web.server({
    sync: true
  });
  server.on('error', err => logger.error(err.message));
  return server.start().then(() => {
    logger.success(`Fractal server is now running at ${server.url}`);
  });
});

gulp.task('default', ['build']);
