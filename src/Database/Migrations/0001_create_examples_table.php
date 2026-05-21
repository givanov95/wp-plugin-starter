<?php

use WpPluginStarter\Database\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $table   = $this->wpdb()->prefix . 'wp_plugin_starter_examples';
        $charset = $this->charsetCollate();

        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(191) NOT NULL,
            email VARCHAR(191) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_status (status),
            KEY idx_created_at (created_at)
        ) {$charset};";

        dbDelta($sql);
    }

    public function down(): void
    {
        $table = $this->wpdb()->prefix . 'wp_plugin_starter_examples';
        $this->wpdb()->query("DROP TABLE IF EXISTS `{$table}`");
    }
};
