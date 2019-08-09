var     
    gulp            = require('gulp'),
    less            = require('gulp-less'),
    concatCss       = require('gulp-concat-css'),
    cleanCss        = require('gulp-clean-css'),
    autoprefixer    = require('gulp-autoprefixer'),
    browserSync     = require('browser-sync'),
    bourbon         = require('node-bourbon'),
    ftp             = require('vinyl-ftp'),
    path            = require('path');

gulp.task('browser-sync', function() {
    browserSync({
        proxy: 'scarfinity.loc:170',
        notify: false
    });
});

gulp.task('less', function() {
    console.log(bourbon.includePaths);
    //.pipe(concatCss('stylesheet.css?v=' + Math.round(Date.now() / 1000 / 60)))

    return gulp.src('catalog/view/theme/scarfinity/stylesheet/**/*.less')
        .pipe(less({ paths: bourbon.includePaths }))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(concatCss('stylesheet.css'))
        .pipe(cleanCss())
        .pipe(gulp.dest('catalog/view/theme/scarfinity/stylesheet'))
        .pipe(browserSync.reload({stream: true}));
});

gulp.task('watch', ['less'], function() {
    gulp.watch('catalog/view/theme/scarfinity/stylesheet/**/*.less', ['less']);
    gulp.watch('catalog/view/theme/scarfinity/template/**/*.twig', browserSync.reload);
    gulp.watch('catalog/view/theme/scarfinity/js/**/*.js', browserSync.reload);
    gulp.watch('catalog/view/theme/scarfinity/libs/**/*', browserSync.reload);
});

gulp.task('deploy', function() {
    var conn = ftp.create({
        host:       'scarfinity.beget.tech',
        user:       'scarfinity',
        password:   'qrs9742z',
        parallel:   10,
        // log:        gutil.log
    });

    var globs = [
        'catalog/**'
    ];

    return gulp.src(globs, { buffer: false })
        .pipe(conn.dest('/home/s/scarfinity/scarfinity.beget.tech/public_html/catalog'));
});

gulp.task('default', ['watch']);