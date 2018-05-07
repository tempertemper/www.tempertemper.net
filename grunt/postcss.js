module.exports = {
  build: {
    options: {
      map: true,
      processors: [
        require('autoprefixer')({
          browsers: ['> 1%', 'last 2 version', 'ie 8', 'ie 9', 'Firefox >= 19'],
        })
      ]
    },
    src: 'web/cms/addons/feathers/tempertemper/css/style.css'
  }
  // },
  // lint: {
  //   options: {
  //     processors: [
  //       require('stylelint')({ // your options  }),
  //       require('postcss-reporter')({ clearMessages: true })
  //     ]
  //   },
  //   src: [
  //     'src/scss/**/*.scss',
  //     '!src/scss/legacy/_ie.scss',
  //     '!src/scss/base/_typography.scss',
  //     '!src/scss/style.scss'
  //   ]
  // }
}
