<?php
/**
 * Responsible for managing plugin admin area.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Responsible for registering admin menus.
 *
 * @since   1.0.0
 * @package ElastiForm
 */
class Admin
{

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_menu', [ $this, 'admin_menus' ]);
    }

    /**
     * Registers admin menus.
     *
     * @since 1.0.0
     */
    public function admin_menus()
    {
        add_menu_page(
            __('ElastiForm', 'elastiform'),
            __('ElastiForm', 'elastiform'),
            'manage_options',
            'ElastiForm-dashboard',
            [ $this, 'dashboardPage' ],
            ELASTIFORMBASE_URL . 'assets/public/icons/logo.svg'
            // 'dashicons-welcome-widgets-menus'
        );

        if (current_user_can('manage_options') ) {
            global $submenu;

         $submenu['ElastiForm-dashboard'][] = [ __( 'Dashboard', 'ElastiForm-dashboard' ), 'manage_options', 'admin.php?page=ElastiForm-dashboard#/' ]; // phpcs:ignore

         $submenu['ElastiForm-dashboard'][] = [ __( 'Create Form', 'ElastiForm-dashboard' ), 'manage_options', 'admin.php?page=ElastiForm-dashboard#/create-form' ]; // phpcs:ignore

         $submenu['ElastiForm-dashboard'][] = [ __( 'Leads', 'ElastiForm-dashboard' ), 'manage_options', 'admin.php?page=ElastiForm-dashboard#/Leads' ]; // phpcs:ignore

         $submenu['ElastiForm-dashboard'][] = [ __( 'Settings', 'ElastiForm-dashboard' ), 'manage_options', 'admin.php?page=ElastiForm-dashboard#/settings' ]; // phpcs:ignore
        }
    }

    /**
     * Displays admin page.
     *
     * @return void
     */
    public static function dashboardPage()
    {
        echo '<div id="ElastiForm-app-root"></div>';
        echo '<div id="ElastiForm-app-portal"></div>';
    }
}
