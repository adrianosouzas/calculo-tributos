
module.exports = function (grunt) {
    'use strict';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            assets: {
                files: [ 'assets/css/**/*.styl' ],
                tasks: [ 'clean', 'stylus' ],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
        },

        clean: {
            css: 'public/skin/css/*',
            js: 'public/skin/js/*',
            cache: 'data/cache/*'
        },

        watchify: {
            options: {
                transform: ['babelify']
            },
            app: {
                src: './assets/js/basic.js',
                dest: 'public/skin/js/basic.js',
            }
        },

        uglify: {
            js: {
                files: {
                    'public/skin/js/basic.js': [
                        'assets/js/basic.js'
                    ]
                }
            }
        },

        stylus: {
            dist: {
                options: {
                    paths: [ 'assets/css', 'node_modules' ],
                    compress: false,
                    import: [ 'kouto-swiss' ],
                    'include css': true
                },

                files: [
                    {
                        expand: true,
                        cwd: 'assets/css/',
                        src: [ '**/*.styl' ],
                        dest: 'public/skin/css',
                        ext: '.css'
                    }
                ]
            }
        },

        cssmin: {
            options: {
                keepSpecialComments: 0
            },

            lib: {
                files: [
                    {
                        expand: true,
                        cwd: 'public/skin/css/',
                        src: [ '*.css' ],
                        dest: 'public/skin/css/',
                        ext: '.css',
                        extDot: 'last'
                    }
                ]
            }
        },

        php: {
            dist: {
                options: {
                    hostname: '0.0.0.0',
                    base: 'public'
                }
            }
        }
    });

    require('load-grunt-tasks')(grunt);

    grunt.registerTask('default', [
        'clean',
        'uglify',
        'watchify',
        'stylus',
        'php',
        'watch'
    ]);

    grunt.registerTask('deploy', [
        'clean',
        'uglify',
        'watchify',
        'stylus',
        'cssmin',
        'uglify',
    ]);
};
