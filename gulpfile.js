var     
    gulp            = require('gulp'),
    less            = require('gulp-less'),
    tap             = require('gulp-tap'),
    concatCss       = require('gulp-concat-css'),
    cleanCss        = require('gulp-clean-css'),
    autoprefixer    = require('gulp-autoprefixer'),
    browserSync     = require('browser-sync'),
    bourbon         = require('node-bourbon'),
    ftp             = require('vinyl-ftp'),
    path            = require('path');

// 

// var common = {
//     core: [
//         'less/base.less'
//     ],
//     navigation: [
//         lessPath + 'components/navigation/*.less'
//     ]
// }

// var pages = [
//     {
//         name: 'home',
//         bundleList: [
//             'less/home.less'
//         ]
//     },
//     {
//         name: 'about',
//         bundleList: [
//             'less/about.less'
//         ]
//     }
// ];

gulp.task('bundle1', function() {
    pages.forEach(page => {
        return gulp.src([
            ...common.core,
            ...common.navigation,
            ...page.bundleList
        ].map(src => 'catalog/view/theme/scarfinity/stylesheet/' + src))
            .pipe(less({ paths: bourbon.includePaths }))
            .pipe(autoprefixer(['last 15 versions']))
            .pipe(concatCss('bundle.css'))
            .pipe(cleanCss())
            .pipe(gulp.dest('dir/css/' + page.name))
            .pipe(browserSync.reload({stream: true}));
    });
});

// gulp.task('default', ['bundle']);


gulp.task('bundle', function() {
    return gulp.src('catalog/view/theme/scarfinity/stylesheet/pages/*.less')
        .pipe(tap(function(file, t) {
            var filename = path.basename(file.path, ".less");

            gulp.src(file.path)
                .pipe(less({ paths: bourbon.includePaths }))
                .pipe(autoprefixer(['last 15 versions']))
                .pipe(concatCss(filename + '.css'))
                .pipe(cleanCss())
                .pipe(gulp.dest('catalog/view/theme/scarfinity/stylesheet/'));
        }));
});
































gulp.task('browser-sync', function() {
    browserSync({
        proxy: 'scarfinity.loc:170',
        notify: false
    });
});

gulp.task('less', function() {
    console.log(bourbon.includePaths);
    //.pipe(concatCss('stylesheet.css?v=' + Math.round(Date.now() / 1000 / 60)))

    return gulp.src([
            'catalog/view/theme/scarfinity/stylesheet/**/*.less', 
            'catalog/view/theme/scarfinity/stylesheet/lib/*.less',
            '!catalog/view/theme/scarfinity/stylesheet/pages/**/*.less',
            '!catalog/view/theme/scarfinity/stylesheet/__/*.less'
        ])
        .pipe(less({ paths: bourbon.includePaths }))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(concatCss('stylesheet.css'))
        .pipe(cleanCss())
        .pipe(gulp.dest('catalog/view/theme/scarfinity/stylesheet'))
        .pipe(browserSync.reload({stream: true}));
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

gulp.task('utility', function() {
    return gulp.src([
        'catalog/view/theme/scarfinity/stylesheet/components/currency.less',
        'catalog/view/theme/scarfinity/stylesheet/components/button.less',
        'catalog/view/theme/scarfinity/stylesheet/components/rating-box.less'
    ])
        .pipe(less({ paths: bourbon.includePaths }))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(concatCss('utility.css'))
        .pipe(cleanCss())
        .pipe(gulp.dest('catalog/view/theme/scarfinity/stylesheet'))
        .pipe(browserSync.reload({stream: true}));
});

gulp.task('less1', function() {
    return gulp.src([
            'catalog/view/theme/scarfinity/stylesheet/source/common/product-list.less',
            'catalog/view/theme/scarfinity/stylesheet/source/common/navigation-mmenu.less',
            'catalog/view/theme/scarfinity/stylesheet/components/navigation/mobile-main-menu.less'
        ])
        .pipe(less({ paths: bourbon.includePaths }))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(concatCss('mobile.css'))
        .pipe(cleanCss())
        .pipe(gulp.dest('catalog/view/theme/scarfinity/stylesheet'))
        .pipe(browserSync.reload({stream: true}));
});

var t = {
    navigation: [
        'catalog/view/theme/scarfinity/stylesheet/components/navigation/breadcrumbs.less',
        'catalog/view/theme/scarfinity/stylesheet/components/navigation/footer.less',
    ]
}

gulp.task('navigation', function() {
    return gulp.src([
        'catalog/view/theme/scarfinity/stylesheet/components/navigation/mobile-main-menu.less',
        'catalog/view/theme/scarfinity/stylesheet/components/navigation/breadcrumbs.less',
        'catalog/view/theme/scarfinity/stylesheet/components/navigation/footer.less',
        'catalog/view/theme/scarfinity/stylesheet/components/button.less',
        'catalog/view/theme/scarfinity/stylesheet/components/rating-box.less'
    ])
        .pipe(less({ paths: bourbon.includePaths }))
        .pipe(autoprefixer(['last 15 versions']))
        .pipe(concatCss('utility.css'))
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






















gulp.task('default', ['watch']);