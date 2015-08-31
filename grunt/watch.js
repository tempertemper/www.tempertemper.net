module.exports = {
  sass: {
    files: ['dev/scss/**'],
    tasks: ['scsslint', 'sass', 'postcss'],
    options: {
      spawn: false,
      livereload: true,
    }
  }
}