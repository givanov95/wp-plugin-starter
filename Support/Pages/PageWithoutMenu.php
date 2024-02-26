<?php

namespace Plugin\Support\Pages;

defined('ABSPATH') || exit;

class PageWithoutMenu extends Page
{
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Add CF Popup submenu page
     *
     * @since 0.0.3
     *
     * @uses "admin_sumbenu_page"
     */

    public function addPage()
    {

        add_submenu_page(
            null,
            __($this->getPageTitle()),
            __($this->getMenuTitle()),
            $this->getCapability(),
            $this->getSlug(),
            [$this, $this->getCallback()]
        );

    }
}
