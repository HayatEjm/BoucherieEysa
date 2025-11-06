const Encore = require('@symfony/webpack-encore');

// Configure le runtime selon l'environnement
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Dossier de sortie des assets compilés
    .setOutputPath('public/build/')
    // URL publique pour accéder aux assets
    .setPublicPath('/build')

    // Entrées JS principales (adapter selon tes besoins)
    .addEntry('app', './assets/app.js')
    .addEntry('cart-app', './assets/js/cart-app.js')
    .addEntry('admin', './assets/admin/main-admin.js')

    // Entrées CSS séparées (uniquement pour les pages qui en ont besoin)
    .addStyleEntry('legal', './assets/styles/legal.css')

    // Optimisation : split des chunks
    .splitEntryChunks()

    .enableVueLoader()

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')
    .enableSingleRuntimeChunk()

    // Support Vue.js (si utilisé)
    // Source maps en dev
    .enableSourceMaps(!Encore.isProduction())
    // Pas de versioning pour des noms de fichiers propres
    .enableVersioning(false)

    // Babel (pour compatibilité JS)
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })
;

module.exports = Encore.getWebpackConfig();
