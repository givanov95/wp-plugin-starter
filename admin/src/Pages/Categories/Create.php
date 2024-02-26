<?php

namespace PLugin\Admin\Pages\Categories;

use Plugin\Support\Pages\Page;

defined('ABSPATH') || exit;

/**
 * Class SettingsPage
 * @package FormOrders\Pages
 */
class Index extends Page
{
    protected string $pageTitle = 'Create Category';

    protected string $menuTitle = 'Create Category';

    protected string $capability = 'edit_pages';

    /**
     * Menu slug
     *
     * @var string
     */
    protected string $slug = 'forms-create-category';


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
     * Add CF Popup submenu page
     *
     * @since 0.0.3
     *
     * @uses "admin_menu"
     */
    public function addPage()
    {
        add_menu_page(
            __($this->pageTitle),
            __($this->menuTitle),
            $this->capability,
            $this->slug,
            [$this, $this->callback],
            $this->iconUrl,
            $this->menuPosition
        );

    }

    /**
     * Enqueue CSS and JS for page
     */
    public function enqueue_assets()
    {
        $manifest = plugin_dir_path(__FILE__) . '../ui/asset-manifest.json';
        if (file_exists($manifest) && $manifestContent = file_get_conCategoryPagetents($manifest)) {
            $json = json_decode($manifestContent, true);
            foreach ($json['entrypoints'] as $entrypoint) {
                $name = 'connectix_' . $entrypoint;
                if (false !== strpos($entrypoint, '.js')) {
                    wp_register_script($name, $this->assetsUrl . 'ui/' . $entrypoint, [], false, true);
                    wp_enqueue_script($name);

                    if (false !== strpos($entrypoint, '/main')) {
                        wp_localize_script($name, 'ctxConfig', [
                            'controllerUrl' => esc_url_raw(rest_url('connectix-api/settings')),
                            'headers' => [
                                'X-WP-Nonce' => wp_create_nonce('wp_rest'),
                            ],
                            'translations' => [
                            'ask' => [
                                'title' => __('ask_title', 'connectix'),
                                'confirm' => __('ask_button_confirm', 'connectix'),
                                'cancel' => __('ask_button_cancel', 'connectix'),
                            ],
                            'settings' => [
                                'title' => __('settings_title', 'connectix'),
                                'label' => [
                                    'enabled' => __('settings_label_enabled', 'connectix'),
                                    'token' => __('settings_label_token', 'connectix'),
                                    'save' => __('settings_label_save', 'connectix'),
                                    'addContacts' => __('settings_add_contacts', 'connectix'),
                                    'trackDelivery' => __('settings_track_delivery', 'connectix'),
                                    'deliveredStatus' => __('settings_delivered_status', 'connectix'),
                                    'deliveredStatus_help' => __('settings_delivered_status_help', 'connectix'),
                                ],
                            ],
                            'templates' => [
                                'button' => __('templates_button', 'connectix'),
                                'button_help' => __('templates_button_help', 'connectix'),
                                'no_templates' => __('templates_no_templates', 'connectix'),
                                'status' => [
                                    'pending' => __('templates_status_pending', 'connectix'),
                                    'rejected' => __('templates_status_rejected', 'connectix'),
                                    'waiting_information' => __('templates_status_waiting_information', 'connectix'),
                                    'approved' => __('templates_status_approved', 'connectix'),
                                ],
                            ],
                            'rules' => [
                                'adding_title' => __('rules_adding_title', 'connectix'),
                                'editing_title' => __('rules_editing_title', 'connectix'),
                                'button' => __('rules_button', 'connectix'),
                                'no_rules' => __('rules_no_rules', 'connectix'),
                                'label' => [
                                    'enabled' => __('rules_label_enabled', 'connectix'),
                                    'not_enabled' => __('rules_label_not_enabled', 'connectix'),
                                    'cancel' => __('rules_label_cancel', 'connectix'),
                                    'save' => __('rules_label_save', 'connectix'),
                                ],

                                'type_placeholder' => __('rules_type_placeholder', 'connectix'),
                                'status' => [
                                    'pending' => __('rules_status_pending', 'connectix'),
                                    'rejected' => __('rules_status_rejected', 'connectix'),
                                    'waiting_information' => __('rules_status_waiting_information', 'connectix'),
                                    'approved' => __('rules_status_approved', 'connectix'),
                                ],
                            ],
                            ],
                        ]);
                    }
                } else {
                    wp_register_style($name, $this->assetsUrl . 'ui/' . $entrypoint);
                    wp_enqueue_style($name);
                }
            }
        }
    }

    /**
     * Render plugin admin page
     */
    public function render()
    {
        $this->enqueue_assets();
        echo <<<HTML
        <div id="forms-ui">
           Category
        </div>
        HTML;
    }


}
