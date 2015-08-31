module.exports = {
    dist: {
        options: {
            config: '.scss-lint.yml',
            exclude: [
              'dev/scss/style.scss'
            ],
            colorizeOutput: true
        },
        files: [{
            expand: true,
            cwd: 'dev/scss/',
            src: ['**/*.scss']
        }]
    },
};