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
  //     'dev/scss/**/*.scss',
  //     '!dev/scss/legacy/_ie.scss',
  //     '!dev/scss/base/_typography.scss',
  //     '!dev/scss/style.scss'
  //   ]
  // }
}
