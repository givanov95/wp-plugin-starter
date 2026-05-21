<?php

namespace WpPluginStarter\Admin\Pages;

use WpPluginCore\Pages\Page;

class ExamplePage extends Page
{
    public static function title(): string
    {
        return __('WP Plugin Starter', 'wp-plugin-starter');
    }

    public function render(): string
    {
        $mountId = config('app.mount_id');

        return self::wrap(
            '<h1>' . self::escape(self::title()) . '</h1>'
            . '<div id="' . self::escapeAttr($mountId) . '"></div>'
        );
    }
}
