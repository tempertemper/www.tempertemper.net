module.exports = {
  dev: {
    bsFiles: {
      src : 'cms/addons/feathers/tempertemper/css/style.css'
    },
    options: {
      proxy: "tempertemper.local/",
      watchTask: true
    }
  }
};