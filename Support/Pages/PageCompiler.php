<?php

namespace Plugin\Support\Pages;

defined('ABSPATH') || exit;

/**
 * Class PageCompiler
 */
class PageCompiler
{
    private string $mainMenuPage;

    private array $submenuPages = [];

    private array $pages = [];


    public function init()
    {

        $mainMenuPageclassName = $this->mainMenuPage;
        $mainMenuPageClass = (new $mainMenuPageclassName());
        $mainMenuPageClass->init();

        foreach ($this->submenuPages as $submenuPage) {
            (new $submenuPage($mainMenuPageClass))->init();
        }

        foreach ($this->pages as $page) {
            (new $page())->init();
        }
    }

    /**
     * Get the value of mainMenuPage
     */
    public function getMainMenuPage()
    {
        return $this->mainMenuPage;
    }

    /**
     * Set the value of mainMenuPage
     */
    public function setMainMenuPage($mainMenuPage): self
    {
        $this->mainMenuPage = $mainMenuPage;

        return $this;
    }

    /**
     * Get the value of submenuPages
     *
     * @return array
     */
    public function getSubmenuPages(): array
    {
        return $this->submenuPages;
    }

    /**
     * Set the value of submenuPages
     *
     * @param array $submenuPages
     *
     * @return self
     */
    public function setSubmenuPage(string $submenuPage): self
    {
        $this->submenuPages[] = $submenuPage;

        return $this;
    }

    /**
     * Get the value of pages
     *
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * Set the value of pages
     *
     * @param array $pages
     *
     * @return self
     */
    public function setPageWithoutMenu(string $page): self
    {
        $this->pages[] = $page;

        return $this;
    }
}
