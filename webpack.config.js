var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .autoProvidejQuery()
    .enableSassLoader()
    .enableSingleRuntimeChunk()
    .addEntry('app', './assets/js/app.js')
    .addEntry('panier', './assets/js/panier.js')
    .addEntry('home', './assets/js/home.js')
    .addEntry('show', './assets/js/show.js')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
