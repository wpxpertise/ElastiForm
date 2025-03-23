<?php
/**
 * Responsible for managing helper methods.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm;

use WP_Error;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Manages notices.
 *
 * @since 1.0.0
 */
class Helpers
{

    /**
     * Check if the pro plugin exists.
     *
     * @return boolean
     */
    public function check_pro_plugin_exists(): bool
    {
        return file_exists(WP_PLUGIN_DIR . '/elastiform-pro/elastiform-pro.php');
    }

    /**
     * Check if pro plugin is active or not
     *
     * @return boolean
     */
    public function is_pro_active(): bool
    {
        $license = function_exists('ElastiFormpro') ? ElastiFormpro()->license_status : false;

        return in_array('elastiform-pro/elastiform-pro.php', get_option('active_plugins', []), true) && $license;
    }

    /**
     * Checks for php versions.
     *
     * @return bool
     */
    public function version_check(): bool
    {
        return version_compare(PHP_VERSION, '5.4') < 0;
    }

    /**
     * Get nonce field.
     *
     * @param string $nonce_action The nonce action.
     * @param string $nonce_name   The nonce input name.
     */
    public function nonceField( $nonce_action, $nonce_name )
    {
        wp_nonce_field($nonce_action, $nonce_name);
    }


    /**
     * Checks plugin version.
     *
     * @since  1.0.0
     * @return bool
     */
    public function is_latest_version(): bool
    {
        return version_compare(ELASTIFORMVERSION, '1.0.0', '>');
    }
}
