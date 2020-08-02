const Encore = require( '@symfony/webpack-encore' );

if ( !Encore.isRuntimeEnvironmentConfigured() ) {
    Encore.configureRuntimeEnvironment( process.env.NODE_ENV || 'dev' );
}

Encore
    .setOutputPath( 'public/build/' )
    .setPublicPath( '/build' )
    .addEntry( 'common', './assets/js/webpack-common.js' )
    .addEntry( 'logic', './assets/js/webpack-login.js' )
    .addEntry( 'checklist-create', './assets/js/webpack-checklist-create.js' )
    .addEntry( 'checklist', './assets/js/webpack-checklist.js' )
    .addEntry( 'checklist-listing', './assets/js/webpack-checklist-listing.js' )
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .configureUrlLoader( {
        fonts: { limit: 4096 },
        images: { limit: 4096 }
    } )


    .copyFiles( {
        from: './assets/images',

        // optional target path, relative to the output dir
        //to: 'images/[path][name].[ext]',

        // if versioning is enabled, add the file hash too
        to: 'images/[path][name].[hash:8].[ext]',
    } )

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps( !Encore.isProduction() )
    .enableVersioning( Encore.isProduction() )

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv( ( config ) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    } )
    .enableIntegrityHashes( Encore.isProduction() )
;

module.exports = Encore.getWebpackConfig();
