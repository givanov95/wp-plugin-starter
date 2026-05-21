<?php

namespace WpPluginStarter\Providers;

use WpPluginCore\Admin\Interfaces\ShouldHaveAdminMenu;
use WpPluginCore\Admin\Menu\AdminMenu;
use WpPluginCore\Providers\ServiceProvider;
use WpPluginStarter\Admin\Pages\ExamplePage;

class AdminMenuServiceProvider extends ServiceProvider implements ShouldHaveAdminMenu
{
    public function boot(): void
    {
        // AdminMenuRegistrar handles registration; nothing to wire here.
    }

    public function getAdminMenu(): AdminMenu
    {
        return AdminMenu::topLevel(
            pageTitle:         config('app.name'),
            menuTitle:         config('app.name'),
            capability:        'manage_options',
            menuSlug:          config('app.slug'),
            pageRenderCallback: static fn () => print (new ExamplePage())->render(),
            iconUrl:           'dashicons-admin-generic',
            position:          80,
        );
    }
}
