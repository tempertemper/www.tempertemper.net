module.exports = {
  options: {
    map: true,
    processors: [
      require('autoprefixer')({
        browsers: ['> 1%', 'last 2 version', 'ie 8', 'ie 9', 'Firefox >= 19'],
      })
    ]
  },
  dist: {
    src: 'web/cms/addons/feathers/tempertemper/css/style.css'
  }
}