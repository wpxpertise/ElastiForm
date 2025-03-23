<?php
/**
 * Managing database operations for tables.
 *
 * @since   3.0.0
 * @package ElastiForm
 */

namespace ElastiForm\Database;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Manages plugin database operations.
 *
 * @since 3.0.0
 */
class Migration
{

    /**
     * Create plugins required database table for tables.
     *
     * @param int $network_wide The network wide site id.
     * @since 1.0.0
     */
    public function run( $network_wide )
    {
        global $wpdb;
        if (is_multisite() && $network_wide ) {
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog($blog_id);
                $this->create_tables();
                $this->create_leads();
                restore_current_blog();
            }
        } else {
            $this->create_tables();
            $this->create_leads();
        }
    }

    /**
     * Create plugins required database table for tables.
     *
     * @since 1.0.0
     */
    public function create_tables()
    {
        global $wpdb;

        $collate = $wpdb->get_charset_collate();
        $table   = $wpdb->prefix . 'simple_form_tables';

        $sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (
			`id` INT(255) NOT NULL AUTO_INCREMENT,
            `form_name` VARCHAR(255) DEFAULT NULL,
            `form_fields` LONGTEXT,
			`time` datetime DEFAULT \'0000-00-00 00:00:00\' NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB ' . $collate . '';

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }


    /**
     * Create plugins required database table for tables.
     *
     * @since 1.0.0
     */
    public function create_leads()
    {
        global $wpdb;

        $collate = $wpdb->get_charset_collate();
        $table   = $wpdb->prefix . 'simple_form_leads';

        $sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (
			`id` INT(255) NOT NULL AUTO_INCREMENT,
			`form_id` text NULL DEFAULT NULL,
            `fields` text NULL DEFAULT NULL,
			`time` datetime DEFAULT \'0000-00-00 00:00:00\' NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB ' . $collate . '';

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
