<?php

namespace Plugin\Support\Pages;

use Plugin\Configuration\Config;

defined('ABSPATH') || exit;

class Page
{
    protected string $pageTitle;

    protected string $menuTitle;

    /**
     * Capability -> find roles capabilities here: https://wordpress.org/documentation/article/roles-and-capabilities
     *
     * @var string
     */
    protected string $capability;

    /**
     * Menu slug
     *
     * @var string
     */
    protected string $slug;

    protected string $callback = 'render';

    protected string $iconUrl;

    protected ?int $menuPosition; // The default WP positions can be found in the WP documentation

    /**
     * Path for the styles of the plugin
     *
     * @var string
     */
    protected string $stylesPath;

    public function __construct()
    {

        $this->stylesPath = Config::get('path.pluginPath') . Config::get('path.defaultStylesPath');
    }


    public function init()
    {
        $this->registerStyles();
        add_action('admin_menu', [$this, 'addPage']);
    }

    /**
     * Get the value of pageTitle
     *
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    /**
     * Set the value of pageTitle
     *
     * @param string $pageTitle
     *
     * @return self
     */
    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get the value of menuTitle
     *
     * @return string
     */
    public function getMenuTitle(): string
    {
        return $this->menuTitle;
    }

    /**
     * Set the value of menuTitle
     *
     * @param string $menuTitle
     *
     * @return self
     */
    public function setMenuTitle(string $menuTitle): self
    {
        $this->menuTitle = $menuTitle;

        return $this;
    }

    /**
     * Get the value of capability
     *
     * @return string
     */
    public function getCapability(): string
    {
        return $this->capability;
    }

    /**
     * Set the value of capability
     *
     * @param string $capability
     *
     * @return self
     */
    public function setCapability(string $capability): self
    {
        $this->capability = $capability;

        return $this;
    }

    /**
     * Get the value of slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @param string $slug
     *
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of callback
     *
     * @return string
     */
    public function getCallback(): string
    {
        return $this->callback;
    }

    /**
     * Set the value of callback
     *
     * @param string $callback
     *
     * @return self
     */
    public function setCallback(string $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get the value of iconUrl
     *
     * @return string
     */
    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    /**
     * Set the value of iconUrl
     *
     * @param string $iconUrl
     *
     * @return self
     */
    public function setIconUrl(string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * Get the value of menuPosition
     *
     * @return ?int
     */
    public function getMenuPosition(): ?int
    {
        return $this->menuPosition;
    }

    /**
     * Set the value of menuPosition
     *
     * @param ?int $menuPosition
     *
     * @return self
     */
    public function setMenuPosition(?int $menuPosition): self
    {
        $this->menuPosition = $menuPosition;

        return $this;
    }

    /**
     * Get the value of styles path
     *
     * @return stringdefaultStylesPath
     */
    public function getStylesPath(): string
    {
        return $this->stylesPath;
    }


    private function registerStyles()
    {
        wp_register_style('styles', $this->getStylesPath());
        wp_enqueue_style('styles');
    }



}
