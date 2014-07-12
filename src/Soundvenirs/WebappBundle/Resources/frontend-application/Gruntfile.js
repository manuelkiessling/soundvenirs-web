module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-karma');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-jsdoc');

    grunt.initConfig({
        'pkg': grunt.file.readJSON('package.json'),

        'meta': {
            'jsFilesForTesting': [
                '../public/js/vendor/jquery/dist/jquery.js',
                '../public/js/vendor/angular/angular.js',
                '../public/js/vendor/angular-route/angular-route.js',
                '../public/js/vendor/angular-sanitize/angular-sanitize.js',
                '../public/js/vendor/angular-mocks/angular-mocks.js',
                '../public/js/vendor/restangular/dist/restangular.js',
                '../public/js/vendor/lodash/dist/lodash.js',
                'tests/**/*Spec.js'
            ]
        },

        'karma': {
            'development': {
                'configFile': 'karma.conf.js',
                'options': {
                    'files': [
                        '<%= meta.jsFilesForTesting %>',
                        'src/app.js',
                        'src/**/*.js'
                    ]
                }
            },
            'dist': {
                'options': {
                    'configFile': 'karma.conf.js',
                    'files': [
                        '<%= meta.jsFilesForTesting %>',
                        '../public/js/app/<%= pkg.namelower %>.js'
                    ]
                }
            },
            'minified': {
                'options': {
                    'configFile': 'karma.conf.js',
                    'files': [
                        '<%= meta.jsFilesForTesting %>',
                        '../public/js/app/<%= pkg.namelower %>.min.js'
                    ]
                }
            }
        },

        'jshint': {
            'beforeconcat': ['src/**/*.js']
        },

        copy: {
            main: {
                files: [
                    {expand: true, src: ['partials/**'], dest: '../public/js/app/'}
                ]
            }
        },

        'concat': {
            'dist': {
                'src': ['src/app.js', 'src/**/*.js'],
                'dest': '../public/js/app/<%= pkg.namelower %>.js'
            }
        },

        'uglify': {
            'options': {
                'mangle': false
            },
            'dist': {
                'files': {
                    '../public/js/app/<%= pkg.namelower %>.min.js': ['../public/js/app/<%= pkg.namelower %>.js']
                }
            }
        },

        'jsdoc': {
            'src': ['src/**/*.js'],
            'options': {
                'destination': 'doc'
            }
        }

    });

    grunt.registerTask('test', ['karma:development']);
    grunt.registerTask('build',
        [
            'jshint',
            'karma:development',
            'concat',
            'karma:dist',
            'uglify',
            'karma:minified',
            'jsdoc'
        ]);

};
