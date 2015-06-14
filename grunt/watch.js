module.exports = {
  sass: {
    files: ['dev/scss/**'],
    tasks: ['sass', 'postcss'],
    options: {
      spawn: false,
      livereload: true,
    }
  }
}