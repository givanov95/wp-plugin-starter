<?php

namespace Plugin\Admin\Pages\Products;

use Plugin\Support\Pages\Page;
use Plugin\Support\Pages\SubMenuPage;
use Plugin\Support\Redirects\Redirector;

defined('ABSPATH') || exit;

/**
 * Class SettingsPage
 * @package Plugin\Admin\Pages
 */
class Index extends SubMenuPage
{
    protected string $pageTitle = 'Products';

    protected string $menuTitle = 'Products';

    protected string $capability = 'edit_pages';

    /**
     * Menu slug
     *
     * @var string
     */


    protected string $slug = 'form-products';


    protected string $iconUrl = '';

    protected ?int $menuPosition = 58; // Position right after WooCommerce menus

    /**
     * URL for assets
     *
     * @var string
     */
    protected string $assetsUrl;


    public function __construct(Page $mainPageClass)
    {
        parent::__construct($mainPageClass);
    }

    /**
     * Render plugin admin page
     */
    public function render()
    {
        $createProductUrl = Redirector::redirect('form-create-product');
        $this->enqueue_assets();

        echo <<<HTML
        <div id="forms-ui">
            Product page

            <a href="{$createProductUrl}">Create</a>
        </div>
        HTML;
    }
}
