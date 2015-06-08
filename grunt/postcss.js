module.exports = {
  options: {
    map: true,
    processors: [
      require('autoprefixer-core')({
        browsers: ['> 1%', 'last 2 version', 'ie 8', 'ie 9'],
      })
    ]
  },
  dist: {
    src: 'cms/addons/feathers/tempertemper/css/style.css'
  }
};