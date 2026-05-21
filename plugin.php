<?php
/**
 * Plugin Name:       WP Plugin Starter
 * Plugin URI:        https://example.com/wp-plugin-starter
 * Description:       Starter plugin built on wp-plugin-core (Vite + TS + Vue SPA, Laravel-like architecture).
 * Version:           0.1.0
 * Requires at least: 6.4
 * Requires PHP:      8.3
 * Author:            Your Name
 * License:           MIT
 * Text Domain:       wp-plugin-starter
 * Domain Path:       /languages
 */

defined('ABSPATH') || exit;

define('WP_PLUGIN_STARTER_FILE',    __FILE__);
define('WP_PLUGIN_STARTER_DIR',     plugin_dir_path(__FILE__));
define('WP_PLUGIN_STARTER_URL',     plugin_dir_url(__FILE__));
define('WP_PLUGIN_STARTER_VERSION', '0.1.0');
define('WP_PLUGIN_STARTER_SLUG',    'wp-plugin-starter');

require_once WP_PLUGIN_STARTER_DIR . 'vendor/autoload.php';

use WpPluginStarter\Bootstrap\Plugin;

register_activation_hook(__FILE__,   [Plugin::class, 'onActivate']);
register_deactivation_hook(__FILE__, [Plugin::class, 'onDeactivate']);

add_action('plugins_loaded', static function (): void {
    Plugin::instance()->boot();
});
