<?php

return [
    'name'            => 'WP Plugin Starter',
    'slug'            => 'wp-plugin-starter',
    'text_domain'     => 'wp-plugin-starter',
    'version'         => WP_PLUGIN_STARTER_VERSION,

    // REST namespace used by ApiServiceProvider.
    'rest_namespace'  => 'wp-plugin-starter/v1',

    // Asset handles (used by AssetsServiceProvider).
    'script_handle'   => 'wp-plugin-starter',
    'style_handle'    => 'wp-plugin-starter',

    // JS global injected by wp_localize_script with rest_url + endpoints.
    'localize_object' => 'WpPluginStarter',

    // Vite settings.
    'vite' => [
        'dev_server_url' => 'http://localhost:5173',
        'entry_point'    => 'assets/js/main.ts',
        'dist_directory' => 'dist',
    ],

    // DOM id where the Vue app is mounted in the admin page.
    'mount_id'        => 'wp-plugin-starter-app',
];
