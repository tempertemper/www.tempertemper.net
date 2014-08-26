module.exports = {
  dist: {
    options: {
      style: 'expanded',
      sourcemap: true,
      // debugInfo: true,
      require: 'susy'
    },
    files: {
      'cms/addons/feathers/tempertemper/css/style.css': 'assets/scss/style.scss',
    }
  }
}