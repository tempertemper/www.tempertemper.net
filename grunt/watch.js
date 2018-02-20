module.exports = {
  sass: {
    files: ['dev/scss/**'],
    // tasks: ['postcss:lint', 'sass', 'postcss:build'],
    tasks: ['sass', 'postcss:build'],
    options: {
      spawn: false,
      livereload: true,
    }
  }
}
