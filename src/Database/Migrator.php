<?php

namespace WpPluginStarter\Database;

/**
 * Runs migration files in lexicographic order and tracks the highest
 * applied version in a WordPress option.
 *
 * Migration files live in src/Database/Migrations and are named with a
 * zero-padded numeric prefix, e.g.:
 *   0001_create_examples_table.php
 *   0002_add_status_to_examples.php
 *
 * Each file must return an instance of a class extending Migration.
 */
class Migrator
{
    private const OPTION_KEY = 'wp_plugin_starter_db_version';

    public function __construct(
        private readonly string $migrationsDir = __DIR__ . '/Migrations',
    ) {
    }

    public function migrate(): void
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $currentVersion = (int) get_option(self::OPTION_KEY, 0);
        $migrations     = $this->discover();

        foreach ($migrations as $version => $migration) {
            if ($version <= $currentVersion) {
                continue;
            }

            $migration->up();
            update_option(self::OPTION_KEY, $version);
        }
    }

    public function rollback(): void
    {
        $migrations = array_reverse($this->discover(), true);

        foreach ($migrations as $version => $migration) {
            $migration->down();
        }

        delete_option(self::OPTION_KEY);
    }

    /**
     * @return array<int, Migration>  keyed by numeric version, sorted ascending
     */
    private function discover(): array
    {
        $files = glob(rtrim($this->migrationsDir, '/') . '/[0-9]*.php') ?: [];
        sort($files);

        $migrations = [];
        foreach ($files as $file) {
            $basename = basename($file, '.php');
            $version  = (int) explode('_', $basename, 2)[0];

            $migration = require $file;
            if ($migration instanceof Migration) {
                $migrations[$version] = $migration;
            }
        }

        return $migrations;
    }
}
