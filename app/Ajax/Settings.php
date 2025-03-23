<?php
/**
 * Responsible for managing ajax endpoints.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm\Ajax;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Manage notices.
 *
 * @since 1.0.0
 */
class Settings
{

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('wp_ajax_ElastiForm_get_settings', [ $this, 'get' ]);
        add_action('wp_ajax_ElastiForm_save_settings', [ $this, 'save' ]);
    }

    /**
     * Get function.
     *
     * @since 1.0.0
     */
    public function get()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', 'elastiform'),
                ]
            );
        }

        wp_send_json_success(
            [
            'async' => get_option('asynchronous_loading', false),
            'css'   => get_option('css_code_value'),
            ]
        );
    }

    public function save()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', 'elastiform'),
                ]
            );
        }

        // $settings = ! empty( $_POST['settings'] ) ? json_decode( wp_unslash( $_POST['settings'] ), true ) : false;
        $settings = ! empty($_POST['settings']) ? json_decode(wp_unslash(sanitize_text_field($_POST['settings'])), true) : false;


        update_option('asynchronous_loading', sanitize_text_field($settings['async_loading']));
        update_option('css_code_value', sanitize_text_field($settings['css_code_value']));

        wp_send_json_success(
            [
            'message' => __('Settings saved successfully.', 'elastiform'),
            'async' => get_option('asynchronous_loading', false),
            'css'   => get_option('css_code_value'),
            ]
        );
    }
}
