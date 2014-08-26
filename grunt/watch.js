module.exports = {
  sass: {
    files: ['assets/scss/**'],
    tasks: ['sass', 'autoprefixer'],
    options: {
      spawn: false,
      livereload: true,
    }
  }
}