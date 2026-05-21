<?php

namespace WpPluginStarter\Bootstrap;

use WpPluginCore\Admin\Menu\AdminMenuRegistrar;
use WpPluginCore\Providers\RestEndpointsManager;
use WpPluginStarter\Database\Migrator;
use WpPluginStarter\Providers\AdminMenuServiceProvider;
use WpPluginStarter\Providers\ApiServiceProvider;
use WpPluginStarter\Providers\AssetsServiceProvider;
use WpPluginStarter\Support\Config;

/**
 * Plugin entry point. Holds the application instance, registers providers,
 * and exposes the lifecycle hooks (activation / deactivation).
 *
 * Use Plugin::instance() to retrieve the singleton, or the global config()
 * helper to read configuration values.
 */
final class Plugin
{
    private static ?self $instance = null;

    private Config $config;
    private bool $booted = false;

    /**
     * Service providers booted on every request.
     *
     * Top-level "framework" providers (assets, REST endpoints) live alongside
     * feature providers — order matters only when feature providers depend on
     * services registered by earlier ones.
     *
     * @var class-string[]
     */
    private array $providers = [
        AssetsServiceProvider::class,
        ApiServiceProvider::class,
    ];

    /**
     * Providers that contribute admin menus. Listed separately because they
     * are registered via WpPluginCore\Admin\Menu\AdminMenuRegistrar, which
     * batches top-level + submenu registrations on the admin_menu hook.
     *
     * @var class-string[]
     */
    private array $adminMenuProviders = [
        AdminMenuServiceProvider::class,
    ];

    private function __construct()
    {
        $this->config = new Config(WP_PLUGIN_STARTER_DIR . 'config');
    }

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }
        $this->booted = true;

        $this->loadTextDomain();

        foreach ($this->providers as $providerClass) {
            (new $providerClass())->boot();
        }

        AdminMenuRegistrar::register($this->adminMenuProviders);

        // Localize REST endpoints for the frontend bundle.
        add_action('wp_enqueue_scripts', static function (): void {
            RestEndpointsManager::localizeEndpoints(
                config('app.script_handle'),
                config('app.localize_object'),
            );
        }, 20);

        add_action('admin_enqueue_scripts', static function (): void {
            RestEndpointsManager::localizeEndpoints(
                config('app.script_handle'),
                config('app.localize_object'),
            );
        }, 20);
    }

    public static function onActivate(): void
    {
        (new Migrator())->migrate();
    }

    public static function onDeactivate(): void
    {
        // No destructive cleanup here — see uninstall.php.
    }

    private function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'wp-plugin-starter',
            false,
            dirname(plugin_basename(WP_PLUGIN_STARTER_FILE)) . '/languages',
        );
    }
}
