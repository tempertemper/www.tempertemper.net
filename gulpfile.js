const gulp = require('gulp');
const fractal = require('./fractal.js');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const notify = require('gulp-notify');
const browserSync = require('browser-sync').create();
const sourcemaps = require('gulp-sourcemaps');
const bump = require('gulp-bump');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const logger = fractal.cli.console;
const del = require('del');

const paths = {
  src: {
    styles: 'src/scss/**/*.scss',
    scripts: 'src/js/**/*',
    images: 'src/img/**/*',
    fonts: 'src/fonts/**/*'
  },
  tmp: {
    styles: 'tmp/assets/css',
    scripts: 'tmp/assets/js',
    images: 'tmp/assets/img',
    fonts: 'tmp/assets/fonts'
  },
  build: {
    styles: 'patterns/assets/css',
    scripts: 'patterns/assets/js',
    images: 'patterns/assets/img',
    fonts: 'patterns/assets/fonts',
    all: 'patterns'
  },
  dist: {
    styles: 'web/cms/addons/feathers/tempertemper/css',
    scripts: 'web/cms/addons/feathers/tempertemper/js',
    images: 'web/cms/addons/feathers/tempertemper/img',
    fonts: 'web/cms/addons/feathers/tempertemper/fonts'
  }
};

// Bump
gulp.task('bump:major', function() {
  return gulp.src(['./*.json'], {base: './'})
    .pipe(bump({type: 'major'}))
    .pipe(gulp.dest('./'));
});

gulp.task('bump:minor', function() {
  return gulp.src(['./*.json'], {base: './'})
    .pipe(bump({type: 'minor'}))
    .pipe(gulp.dest('./'));
});

gulp.task('bump:patch', function() {
  return gulp.src(['./*.json'], {base: './'})
    .pipe(bump({type: 'patch'}))
    .pipe(gulp.dest('./'));
});

// Sass shared config
const scssConfig = function() {
  return sass({
    outputStyle: 'compressed'
  })
  .on('error', sass.logError)
  .on('error', notify.onError(function (error) {
    return {
      title: 'SASS error',
      message: error.message
    }
  }))
};

const autoprefixerConfig = function() {
  return autoprefixer({
    browsers: [
      '> 1%',
      'last 2 version',
      'ie 8',
      'ie 9'
    ],
    flexbox: false
  })
};

// Clean web folder
gulp.task('clean-tmp', function () {
  return del(['tmp']);
});

// Clean build folder
gulp.task('clean-build', function () {
  return del([paths.build.all]);
});

// Copy files
gulp.task('files', function() {
  gulp.src('./node_modules/html5shiv/dist/html5shiv.min.js')
    .pipe(gulp.dest(paths.tmp.scripts))
    .pipe(gulp.dest(paths.dist.scripts));
  gulp.src('./node_modules/responsive-nav/responsive-nav.min.js')
    .pipe(gulp.dest(paths.tmp.scripts))
    .pipe(gulp.dest(paths.dist.scripts));
  gulp.src(paths.src.fonts)
    .pipe(gulp.dest(paths.tmp.fonts))
    .pipe(gulp.dest(paths.dist.fonts));
});

// Compile SCSS and autoprefix styles.
gulp.task('styles', function () {
  return gulp.src(paths.src.styles)
    .pipe(sourcemaps.init())
    .pipe(scssConfig())
    .pipe(autoprefixerConfig())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.dist.styles))
    .pipe(gulp.dest(paths.tmp.styles))
    .pipe(browserSync.stream());
});

// Concatenate and uglify JavaScript
gulp.task('scripts', function() {
  return gulp.src([
      './src/js/**/*.js'
    ])
    .pipe(concat('production.js'))
    .pipe(uglify())
    .pipe(gulp.dest(paths.dist.scripts))
    .pipe(gulp.dest(paths.tmp.scripts));
});

gulp.task('compile', [
  'files',
  'scripts',
  'styles'
]);

// Build static pattern library
gulp.task('build', ['clean-build', 'compile'], function() {
  const builder = fractal.web.builder();
  return builder.build().then(function(){
    console.log(`Pattern library static build complete!`);
  });
});

gulp.task('watch', ['compile'], function () {
  gulp.watch(paths.src.styles, ['styles']);
  gulp.watch(paths.src.scripts, ['scripts']);
});

gulp.task('serve', ['compile', 'build', 'watch'], function(){
  const server = fractal.web.server({
    sync: true
  });
  server.on('error', err => logger.error(err.message));
  return server.start().then(() => {
    logger.success(`Fractal server is now running at ${server.url}`);
  });
});

gulp.task('default', ['compile']);
