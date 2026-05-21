<?php

namespace WpPluginStarter\Database;

abstract class Migration
{
    /**
     * Apply the migration. Use dbDelta() for CREATE TABLE to leverage WP's
     * schema diffing on subsequent runs.
     */
    abstract public function up(): void;

    /**
     * Reverse the migration. Default no-op; override when needed.
     */
    public function down(): void
    {
    }

    protected function wpdb(): \wpdb
    {
        global $wpdb;
        return $wpdb;
    }

    protected function charsetCollate(): string
    {
        return $this->wpdb()->get_charset_collate();
    }
}
