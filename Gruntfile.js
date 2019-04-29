module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    // Configurable paths
    var config = {
        devRoot: './',
        tmpRoot: '.tmp',
        distRoot: 'dist',
        staticRoot: 'static',
        sassPath: '<%= config.staticRoot %>/scss',
        cssPath: '<%= config.staticRoot %>/css',
        imgPath: '<%= config.staticRoot %>/img',
        jsPath: '<%= config.staticRoot %>/js'
    };

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // Project settings
        config: config,

        sass: {
            dist: {
                files: {
                    '<%= config.tmpRoot %>/<%= config.cssPath %>/style.css' : '<%= config.devRoot %>/<%= config.sassPath %>/style.scss'
                }
            }
        },

        file_append: {
          default_options: {
            files: [
              {
                prepend: "@import-normalize;",
                input: '<%= config.tmpRoot %>/<%= config.cssPath %>/style.css',
                output: '<%= config.tmpRoot %>/<%= config.cssPath %>/style.css'
              }
            ]
          }
        },

        postcss: {
          options: {
            map: {
                inline: false,
                annotation: '<%= config.tmpRoot %>/<%= config.cssPath %>/maps/'
            },

            processors: [
              require('pixrem')(),
              require('autoprefixer')(),
              require('postcss-normalize')(),
              require('cssnano')({
                  preset: 'default',
              })
            ]
          },
          dist: {
            src: '<%= config.tmpRoot %>/<%= config.cssPath %>/style.css'
          }
        },

        cmq: {
            options: {
                log: false
            },
            target: {
                files: {
                    '<%= config.tmpRoot %>/<%= config.cssPath %>/style.css': ['<%= config.tmpRoot %>/<%= config.cssPath %>/style.css']
                }
            }
        },

        // modernizr: {
        //   dist: {
        //     "parseFiles": true,
        //     "customTests": [],
        //     "dest": "<%= config.devRoot %>/<%= config.jsPath %>/modernizr-custom.js",
        //     "tests": [
        //       "cssanimations"
        //     ],
        //     "options": [
        //       "setClasses"
        //     ],
        //     "uglify": true
        //   }
        // },

        // Project configuration.
        concat: {
          core: {
            src: [
              '<%= config.devRoot %>/<%= config.jsPath %>/main.js',
            ],
            dest: '<%= config.tmpRoot %>/<%= config.jsPath %>/main.js',
          }
        },

        uglify: {
            js: {
                files: {
                    '<%= config.tmpRoot %>/<%= config.jsPath %>/main.js': ['<%= config.tmpRoot %>/<%= config.jsPath %>/main.js']
                }
            }
        },

        copy: {
            images: {
              expand: true,
              cwd: '<%= config.devRoot %>/<%= config.imgPath %>/',
              src: ['./**'],
              dest: '<%= config.tmpRoot %>/<%= config.imgPath %>/'
            },
            dist: {
              expand: true,
              cwd: '<%= config.tmpRoot %>',
              src: ['./**'],
              dest: '<%= config.distRoot %>'
            }
        },

        // connect: {
        //   server: {
        //     options: {
        //       port: 9001,
        //       base: '<%= config.tmpRoot %>'
        //     }
        //   }
        // },

        watch: {
          css: {
       //     files: 'src/**/*.scss',
            files: '/*.scss',
            tasks: [
              'sass',
              'cmq',
              'file_append',
              'postcss'
            ],
            options: {

              livereload: true
            },
          },
          js: {
            files: 'src/**/*.js',
            tasks: [
              'concat:core',
              'uglify:js'
            ],
            options: {
              livereload: true
            },
          },
          html: {
            files: 'src/**/*.html',
            tasks: [
              'htmlmin'
            ],
            options: {
              livereload: true
            },
          }
        }
    });

    grunt.registerTask('default', [
      'sass',
      'cmq',
      'file_append',
      'postcss',
      'concat:core',
      'uglify:js',
      'copy:images',
      'watch'
    ]);

    grunt.registerTask('build', [
      'sass',
      'cmq',
      'file_append',
      'postcss',
      'concat:core',
      'uglify:js',
      'copy:images',
      'copy:dist',
    ]);
}
