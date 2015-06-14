module.exports = {
  fonts: {
    expand: true,
    cwd: 'assets/fonts/',
    src: '**',
    dest: 'cms/addons/feathers/tempertemper/fonts/',
  },
  favicon: {
    expand: true,
    cwd: 'assets/images/icons/',
    src: 'favicon.ico',
    dest: 'cms/addons/feathers/tempertemper/img/icons',
  },
  js: {
    expand: true,
    flatten: true,
    src: [
      'assets/components/responsive-nav/responsive-nav.min.js',
      'assets/components/html5shiv/dist/html5shiv.min.js'
    ],
    dest: 'cms/addons/feathers/tempertemper/js/',
  }
}