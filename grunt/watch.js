module.exports = {
  sass: {
    files: ['src/scss/**'],
    // tasks: ['postcss:lint', 'sass', 'postcss:build'],
    tasks: ['sass', 'postcss:build'],
    options: {
      spawn: false,
      livereload: true,
    }
  }
}
