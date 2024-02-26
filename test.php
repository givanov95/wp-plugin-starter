<?php


/*
 * Plugin Name:       Forms shop
 * Description:       Custom shop based on forms
 * Author:            Georgi Ivanov
 * Author URI:        https://github.com/givanov95
 * Plugin URI:        https://github.com/
 * Version:           0.1
 * Text Domain:       domain
 * Domain Path:       /languages/
 * Requires at least: 6.2
 * Requires PHP:      8.3.0
 *
 * License:           GNU General Public License, version 2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.en.html
*/

// TODO

use Plugin\Admin\Pages\Categories\Index as CategoriesIndexPage;

use Plugin\Admin\Pages\Products\Create as ProductsCreatePage;
use Plugin\Admin\Pages\Products\Index as ProductsIndexPage;
use Plugin\Support\Database\Connections\PDOConnection;
use Plugin\Support\Installer;
use Plugin\Support\Pages\PageCompiler;

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

(new Installer(__FILE__))->init();

add_action('plugins_loaded', function () {
    load_plugin_textdomain('connectix', false, basename(dirname(__FILE__)) . '/languages/');
});

add_action('init', function () {
    if (is_admin()) {

        PDOConnection::getInstance();

        $page = new PageCompiler();

        $page
            ->setMainMenuPage(CategoriesIndexPage::class)
            ->setSubmenuPage(ProductsIndexPage::class)
            ->setPageWithoutMenu(ProductsCreatePage::class)
        ->init();
    }

});
