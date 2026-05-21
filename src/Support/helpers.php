<?php

use WpPluginStarter\Bootstrap\Plugin;

if (!function_exists('config')) {
    /**
     * Get a configuration value using dot notation.
     *
     *   config('app.name')
     *   config('app.vite.dev_server_url', 'http://localhost:5173')
     */
    function config(string $key, mixed $default = null): mixed
    {
        return Plugin::instance()->config()->get($key, $default);
    }
}

if (!function_exists('plugin_path')) {
    /**
     * Absolute path inside the plugin directory.
     */
    function plugin_path(string $relative = ''): string
    {
        return WP_PLUGIN_STARTER_DIR . ltrim($relative, '/');
    }
}

if (!function_exists('plugin_asset')) {
    /**
     * URL to a file inside the plugin directory.
     */
    function plugin_asset(string $relative): string
    {
        return WP_PLUGIN_STARTER_URL . ltrim($relative, '/');
    }
}
