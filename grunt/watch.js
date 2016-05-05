module.exports = {
  sass: {
    files: ['dev/scss/**'],
    tasks: ['postcss:lint', 'sass', 'postcss:build'],
    options: {
      spawn: false,
      livereload: true,
    }
  }
}
