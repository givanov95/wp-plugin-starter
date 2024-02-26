<?php

namespace Plugin\Support;

defined('ABSPATH') || exit;

/**
 * Class Installer
 * @package Admin\Plugin
 */
class Installer
{
    /**
     * @var string
     */
    private $file;

    /**
     * Installer constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function init()
    {
        register_activation_hook($this->file, [$this, 'on_activation']);
        register_deactivation_hook($this->file, [$this, 'on_deactivation']);
    }

    public function on_activation()
    {
        global $wpdb;

        $collate = '';
        if ($wpdb->has_cap('collation')) {
            $collate = $wpdb->get_charset_collate();
        }

        $queries = [];
        $queries[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}form_order_categories (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, text LONGTEXT NOT NULL, sent_at DATETIME DEFAULT NULL, INDEX IDX_CEBA6A1C537A1329 (message_id), PRIMARY KEY(id)) $collate;";


        $table = $wpdb->get_row("show create table `{$wpdb->prefix}form_order_categories`;", ARRAY_A);
        if ($table && false === strpos($table['Create Table'], 'FK_CEBA6A1C537A13290')) {
            $queries[] = "ALTER TABLE {$wpdb->prefix}form_order_categories ADD CONSTRAINT FK_CEBA6A1C537A13290 FOREIGN KEY IF NOT EXISTS (message_id) REFERENCES {$wpdb->prefix}connectix_messages (id) ON DELETE CASCADE;";
        }

        foreach ($queries as $query) {
            $wpdb->query($query);
        }


    }

    public function on_deactivation()
    {
        // Settings::delete_settings();
    }

    public static function drop_tables()
    {
        global $wpdb;

        $queries = [];
        $queries[] = "ALTER TABLE {$wpdb->prefix}connectix_message_inbounds DROP FOREIGN KEY FK_CEBA6A1C537A13290;";
        $queries[] = "DROP TABLE {$wpdb->prefix}connectix_message_inbounds;";

        foreach ($queries as $query) {
            $wpdb->query($query);
        }
    }
}
