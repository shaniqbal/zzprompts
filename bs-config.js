module.exports = {
    // Update this URL to match your local WordPress URL
    // Laragon default: http://zzprompts.test or http://localhost/zzprompts
    proxy: "http://zzprompts.test",
    files: [
        "**/*.php",
        "assets/css/**/*.css",
        "assets/js/**/*.js",
        "template-parts/**/*.php",
        "*.php",
        "inc/**/*.php"
    ],
    watchOptions: {
        ignored: [
            "node_modules/**",
            "*.zip",
            ".git/**",
            "**/*.zip"
        ]
    },
    open: false,
    notify: true,
    reloadOnRestart: true,
    ghostMode: {
        clicks: false,
        forms: false,
        scroll: false
    },
    port: 3000,
    ui: {
        port: 3001
    }
};

