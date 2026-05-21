<?php

namespace WpPluginStarter\Providers;

use WpPluginCore\Providers\PluginServiceProvider;

class AssetsServiceProvider extends PluginServiceProvider
{
    protected function pluginMainFile(): string
    {
        return WP_PLUGIN_STARTER_FILE;
    }

    protected function scriptHandle(): string
    {
        return config('app.script_handle');
    }

    protected function styleHandle(): string
    {
        return config('app.style_handle');
    }

    protected function devServerUrl(): string
    {
        return config('app.vite.dev_server_url');
    }

    protected function entryPoint(): string
    {
        return config('app.vite.entry_point');
    }

    protected function distDirectory(): string
    {
        return config('app.vite.dist_directory');
    }

    /**
     * Only enqueue admin assets on this plugin's own admin page.
     * Adjust the check (page slug) if you add more pages.
     */
    protected function enqueueOnAdmin(): bool
    {
        $screen = function_exists('get_current_screen') ? get_current_screen() : null;
        if (!$screen) {
            return false;
        }

        return str_contains($screen->id ?? '', config('app.slug'));
    }

    /**
     * Frontend enqueue is off by default for this starter; flip to true
     * when your plugin renders a public UI.
     */
    protected function enqueueOnFrontend(): bool
    {
        return false;
    }
}
