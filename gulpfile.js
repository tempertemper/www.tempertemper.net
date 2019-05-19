const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const notify = require('gulp-notify');
const browserSync = require('browser-sync').create();
const sourcemaps = require('gulp-sourcemaps');
const bump = require('gulp-bump');
const concat = require('gulp-concat');
const shell = require('gulp-shell');
const uglify = require('gulp-uglify');
const del = require('del');
// const fractal = require('./fractal.js');
// const logger = fractal.cli.console;

// Define paths
const paths = {
  src: {
    styles: 'src/scss/**/*.scss',
    scripts: 'src/js/**/*',
    images: 'src/img/**/*',
    fonts: 'src/fonts/**/*',
    modules: 'node_modules/html5shiv/dist/html5shiv.min.js',
    site: 'src/site/**/*'
  },
  patterns: {
    styles: 'patterns/assets/css',
    scripts: 'patterns/assets/js',
    images: 'patterns/assets/img',
    fonts: 'patterns/assets/fonts',
    all: 'patterns'
  },
  dist: {
    styles: 'dist/assets/css',
    scripts: 'dist/assets/js',
    images: 'dist/assets/img',
    fonts: 'dist/assets/fonts',
    all: 'dist'
  }
};

// Bump
gulp.task('bump:major', () => {
  return gulp.src(['./*.json', './src/site/_data/site.json'], {base: './'})
    .pipe(bump({type: 'major'}))
    .pipe(gulp.dest('./'));
});

gulp.task('bump:minor', () => {
  return gulp.src(['./*.json', './src/site/_data/site.json'], {base: './'})
    .pipe(bump({type: 'minor'}))
    .pipe(gulp.dest('./'));
});

gulp.task('bump:patch', () => {
  return gulp.src(['./*.json', './src/site/_data/site.json'], {base: './'})
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

// Clean patterns folder
gulp.task('cleanPatterns', () => {
  return del([paths.patterns.all]);
});

// Clean website build folder
gulp.task('cleanSite', () => {
  return del(paths.dist.all);
});

// Clean assets build folder
gulp.task('cleanAssets', () => {
  return del('dist/assets');
});

// Copy JavaScript files
gulp.task('jsFiles', () => {
  return gulp.src(paths.src.modules)
    .pipe(gulp.dest(paths.dist.scripts));
});

// Copy fonts
gulp.task('fonts', () => {
  return gulp.src(paths.src.fonts)
    .pipe(gulp.dest(paths.dist.fonts));
});

// Images
gulp.task('images', () => {
  return gulp.src(paths.src.images)
    .pipe(gulp.dest(paths.dist.images));
});

// Compile SCSS and autoprefix styles.
gulp.task('styles', () => {
  return gulp.src(paths.src.styles)
    .pipe(sourcemaps.init())
    .pipe(scssConfig())
    .pipe(autoprefixerConfig())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.dist.styles))
    .pipe(browserSync.stream());
});

// Concatenate and uglify JavaScript
gulp.task('scripts', () => {
  return gulp.src(paths.src.scripts)
    .pipe(concat('production.js'))
    .pipe(uglify())
    .pipe(gulp.dest(paths.dist.scripts));
});

// Build website assets
gulp.task('buildAssets', gulp.parallel(
  'jsFiles',
  'fonts',
  'images',
  'scripts',
  'styles'
));

gulp.task('generate', shell.task('eleventy --quiet'));

gulp.task('buildAll', gulp.parallel(
  'buildAssets',
  'generate'
));

gulp.task('serve', () => {
  browserSync.init( {
    server: {
      baseDir: "./dist/",
      serveStaticOptions: {
        extensions: ['html']
      }
    },
    open: true,
    browser: 'google chrome',
    notify: false,
    injectChanges: true
  });
  gulp.watch(paths.src.site, gulp.parallel('generate'));
  gulp.watch(paths.src.styles, gulp.parallel('styles'));
  gulp.watch(paths.src.modules, gulp.parallel('jsFiles'));
  gulp.watch(paths.src.fonts, gulp.parallel('fonts'));
  gulp.watch(paths.src.images, gulp.parallel('images'));
  gulp.watch(paths.src.scripts, gulp.parallel('scripts'));
  gulp.watch(paths.dist.all).on('change', browserSync.reload);
});

// // Build static pattern library
// gulp.task('patterns', ['cleanPatterns', 'build'], function() {
//   const builder = fractal.web.builder();
//   return builder.build().then(function(){
//     console.log(`Pattern library static build complete!`);
//   });
// });

// // Build patten library and assets for UI dev
// gulp.task('assets', ['build', 'patterns', 'watch'], function(){
//   const server = fractal.web.server({
//     sync: true
//   });
//   server.on('error', err => logger.error(err.message));
//   return server.start().then(() => {
//     logger.success(`Fractal server is now running at ${server.url}`);
//   });
// });


// gulp.task('serveAssets', gulp.series(
//   'cleanAssets',
//   'buildAssets',
//   'serve'
// ));

// gulp.task('default', ['build']);
