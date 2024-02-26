<?php

namespace Plugin\Admin\Pages\Products;

use Plugin\Admin\Views\Products\CreateView;
use Plugin\Support\Pages\PageWithoutMenu;

defined('ABSPATH') || exit;

/**
 * Class SettingsPage
 * @package Plugin\Admin\Pages
 */
class Create extends PageWithoutMenu
{
    protected string $pageTitle = 'Products';

    protected string $menuTitle = 'Products';

    protected string $capability = 'edit_pages';

    protected string $slug = 'form-create-product';

    protected string $iconUrl = '';

    protected ?int $menuPosition = 58; // Position right after WooCommerce menus

    /**
     * URL for assets
     *
     * @var string
     */
    protected string $assetsUrl;



    public function init()
    {
        add_action('admin_menu', [$this, 'addPage']);
    }


    /**
    * Render plugin admin page
    */
    public function render()
    {
        CreateView::render();
    }
}
