<?php
/**
 * Uninstall handler — runs when the plugin is deleted from the WP admin.
 * Drops plugin tables and removes all plugin options.
 */

defined('WP_UNINSTALL_PLUGIN') || exit;

global $wpdb;

// Drop tables created by migrations.
$tables = [
    $wpdb->prefix . 'wp_plugin_starter_examples',
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS `{$table}`");
}

// Remove plugin options.
$options = [
    'wp_plugin_starter_db_version',
];

foreach ($options as $option) {
    delete_option($option);
}
