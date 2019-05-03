let mix = require('laravel-mix');

/**
 * App-related tasks (compile css, scripts, and etc.)
 *
 * `npm run dev` or `npm run watch` or `npm run prod`
 */

let webpackConfigOptions = {
    resolve: {
        alias: {
            // Force all modules to use the same jquery version.
            'jquery': path.join(__dirname, 'node_modules/jquery/src/jquery')
        }
    }
}

if (!mix.inProduction()) {
    // Do non-inline source-maps
    webpackConfigOptions.devtool = "source-map";
}

mix.webpackConfig(webpackConfigOptions);

// Compile Sass into CSS
mix.sass('resources/assets/sass/app.scss', 'public/css', {
    precision: 10,
});

// Create CSS sourcemaps
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Make specified modules globally known by certain aliases
mix.autoload({
    jquery: ['$', 'jQuery', 'window.jQuery']
});

// Compile JS
mix
    .js('resources/assets/js/app.js', 'public/js')
    // extract these vendor libraries into one separate vendor.js file
    .extract([
        'jquery',
        'popper.js',
        'bootstrap',
        '@fortawesome/fontawesome',
        '@fortawesome/fontawesome-free-solid',
        '@fortawesome/fontawesome-free-regular',
        '@fortawesome/fontawesome-free-brands',
        'sortablejs',
        'bootstrap-datepicker',
        'bootstrap4-tagsinput',
        'trix',
        'corejs-typeahead',
    ]);

// Cache-busting
mix.version();
