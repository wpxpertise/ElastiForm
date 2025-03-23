<?php
/**
 * Responsible for displaying notices in the plugin.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm;

// If direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Manages notices.
 *
 * @since 1.0.0
 */
class Notices
{
    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        /**
         * Detect plugin. For frontend only.
         */
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        if (\is_plugin_active(plugin_basename(ELASTIFORMPLUGIN_FILE)) ) {
            $this->reviewNoticeByCondition();
        }

        $this->version_check();
    }

    /**
     * Running version check.
     *
     * @since 1.0.0
     */
    public function version_check()
    {
        if (ElastiForm()->helpers->version_check() ) {
            if (is_plugin_active(plugin_basename(ELASTIFORMPLUGIN_FILE)) ) {
                deactivate_plugins(plugin_basename(ELASTIFORMPLUGIN_FILE));
                add_action('admin_notices', [ $this, 'show_notice' ]);
            }
        }
    }

    /**
     * Loads review notice based on condition.
     *
     * @since 1.0.0
     */
    public function reviewNoticeByCondition()
    {
        if (time() >= intval(get_option('deafaultNoticeInterval')) ) {
            if (false === get_option('ElastiFormReviewNotice') ) {
                add_action('admin_notices', [ $this, 'showReviewNotice' ]);
            }
        }
    }


    /**
     * Display plugin error notice.
     *
     * @return void
     */
    public function show_notice()
    {
        printf(
            '<div class="notice notice-error is-dismissible"><h3><strong>%s </strong></h3><p>%s</p></div>',
            esc_html__('Plugin', 'elastiform'),
            esc_html__('cannot be activated - requires at least PHP 5.4. Plugin automatically deactivated.', 'elastiform')
        );
    }

    /**
     * Display plugin review notice.
     *
     * @return void
     */
    public function showReviewNotice()
    {
        load_template(ELASTIFORMBASE_PATH . 'app/templates/parts/review_notice.php');
    }
}
