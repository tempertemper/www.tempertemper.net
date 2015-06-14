module.exports = {
  sass: {
    files: ['assets/scss/**'],
    tasks: ['sass', 'postcss'],
    options: {
      spawn: false,
      livereload: true,
    }
  }
}