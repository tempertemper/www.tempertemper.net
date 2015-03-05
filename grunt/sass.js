module.exports = {
  dist: {
    options: {
      style: 'compressed',
      require: 'susy',
      outFile: 'cms/addons/feathers/emspublishing/css/style.css',
      sourceMap: true,
    },
    files: {
      'cms/addons/feathers/tempertemper/css/style.css': 'assets/scss/style.scss',
    }
  }
}