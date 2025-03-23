<?php
/**
 * Responsible for plugin compatibility with WordPress multisite.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Plugin multisite support.
 *
 * @since 1.0.0
 */
class Multisite
{

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('wp_initialize_site', [ $this, 'on_create_site' ]);
        add_filter('wpmu_drop_tables', [ $this, 'on_delete_site' ]);
    }

    /**
     * Run on create single site.
     *
     * @param object $site The single site instance.
     * @since 1.0.0
     */
    public function on_create_site( $site )
    {
        if (is_plugin_active_for_network('simple-form/simple-form.php') ) {
            switch_to_blog((int) $site->blog_id);
            ElastiForm()->database->create_tables();
            restore_current_blog();
        }
    }

    /**
     * Run on delete single site.
     *
     * @param array $tables Site database tables.
     * @since 1.0.0
     */
    public function on_delete_site( $tables )
    {
        global $wpdb;
        $tables['simple_form_tables'] = $wpdb->prefix . 'simple_form_tables';
        return $tables;
    }
}
