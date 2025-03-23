<?php
/**
 * Responsible for enqueuing assets.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Responsible for enqueuing assets.
 *
 * @since   1.0.0
 * @package ElastiForm
 */
class Assets
{

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [ $this, 'admin_scripts' ]);
        add_action('wp_enqueue_scripts', [ $this, 'fe_scripts' ]);
    }

    /**
     * Enqueue backend files.
     *
     * @since 1.0.0
     */
    public function admin_scripts()
    {
        $current_screen = get_current_screen();

        if ('toplevel_page_ElastiForm-dashboard' === $current_screen->id ) {
            // We don't want any plugin adding notices to our screens. Let's clear them out here.
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');

            $this->formTableScripts();

            $dependencies = include_once ELASTIFORMBASE_PATH . 'react/build/index.asset.php';
            $dependencies['dependencies'][] = 'wp-util';

            wp_enqueue_style(
                'ElastiForm-admin',
                ELASTIFORMBASE_URL . 'assets/admin.css',
                '',
                time(),
                'all'
            );

            if (! ElastiForm()->helpers->is_pro_active() ) {

            }

            wp_enqueue_style(
                'ElastiForm-app',
                ELASTIFORMBASE_URL . 'react/build/index.css',
                '',
                time(),
                'all'
            );

            wp_enqueue_script(
                'ElastiForm-app',
                ELASTIFORMBASE_URL . 'react/build/index.js',
                $dependencies['dependencies'],
                time(),
                true
            );

            $icons = apply_filters('export_buttons_logo_backend', false);

            $localize = [
            'nonce'            => wp_create_nonce('ElastiForm-admin-app-nonce-action'),
            'icons'            => $icons,
            'tables'           => ElastiForm()->database->table->get_all(),
            'formsettings'     => ElastiForm()->database->table->get_settings(),
            'ran_setup_wizard' => wp_validate_boolean(get_option('ELASTIFORMran_setup_wizard', false)),
            ];

            wp_localize_script(
                'ElastiForm-app',
                'ELASTIFORMAPP',
                $localize
            );

            wp_enqueue_script(
                'ElastiForm-admin-js',
                ELASTIFORMBASE_URL . 'assets/public/scripts/backend/admin.min.js',
                [ 'jquery' ],
                time(),
                true
            );

        }
    }

    /**
     * Load assets frontend
     *
     * @param  mixed $content The page content.
     * @return mixed
     */
    public function fe_scripts()
    {
        $this->frontend_scripts();
    }

    /**
     * Enqueue frontend files.
     *
     * @since 1.0.0
     */
    public function frontend_scripts()
    {

        wp_enqueue_style(
            'ElastiForm-frontend-minified',
            ELASTIFORMBASE_URL . 'assets/public/styles/frontendcss.min.css',
            [],
            time(),
            'all'
        );

        wp_enqueue_script(
            'ElastiForm-frontend-js',
            ELASTIFORMBASE_URL . 'assets/public/scripts/frontend/frontend.min.js',
            [ 'jquery', 'jquery-ui-draggable' ],
            time(),
            true
        );

        wp_enqueue_script(
            'ElastiForm-sweet-alert-js',
            ELASTIFORMBASE_URL . 'assets/public/library/sweeralert.js',
            [ 'jquery' ],
            time(),
            true
        );

        $iconsURLs = apply_filters('export_buttons_logo_frontend', false);

        wp_localize_script(
            'ElastiForm-frontend-js', 'front_end_data', [
            'admin_ajax'           => esc_url(admin_url('admin-ajax.php')),
            'isProActive'          => ElastiForm()->helpers->is_pro_active(),
            'iconsURL'             => $iconsURLs,
            'nonce'                => wp_create_nonce('ElastiForm_sheet_nonce_action'),
            ]
        );
    }


    /**
     * Enqueue data tables scripts.
     *
     * @since 1.0.0
     */
    public function formTableScripts()
    {
        wp_enqueue_script(
            'ElastiForm-sweet-alert-js',
            ELASTIFORMBASE_URL . 'assets/public/library/sweeralert.js',
            [ 'jquery' ],
            time(),
            true
        );
    }
}
