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
class Notices
{

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('wp_ajax_ElastiForm_notice_action', [ $this, 'manageNotices' ]);
        add_action('wp_ajax_nopriv_ElastiForm_notice_action', [ $this, 'manageNotices' ]);
    }

    /**
     * Manage notices ajax endpoint response.
     *
     * @since 1.0.0
     */
    public function manageNotices()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ELASTIFORMnotices_nonce') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid action', 'elastiform'),
                ]
            );
        }

        $action_type = isset($_POST['actionType']) ? sanitize_text_field(wp_unslash($_POST['actionType'])) : '';
        $info_type   = isset($_POST['info']['type']) ? sanitize_text_field(wp_unslash($_POST['info']['type'])) : '';
        $info_value  = isset($_POST['info']['value']) ? sanitize_text_field(wp_unslash($_POST['info']['value'])) : '';

        if ('hide_notice' === $info_type ) {
            $this->hideNotice($action_type);
        }

        if ('reminder' === $info_type ) {
            $this->setReminder($action_type, $info_value);
        }
    }

    /**
     * Hide notices.
     *
     * @param string $action_type The action type.
     * @since 1.0.0
     */
    public function hideNotice( $action_type )
    {
        if ('review_notice' === $action_type ) {
            update_option('ElastiFormReviewNotice', true);
        }

        wp_send_json_success(
            [
            'response_type' => 'success',
            ]
        );
    }

    /**
     * Set reminder to display notice.
     *
     * @param string $action_type The action type.
     * @param string $info_value  The reminder value.
     * @since 1.0.0
     */
    public function setReminder( $action_type, $info_value = '' )
    {
        if ('hide_notice' === $info_value ) {
            $this->hideNotice($action_type);
            wp_send_json_success(
                [
                'response_type' => 'success',
                ]
            );
        } else {

            if ('review_notice' === $action_type ) {
                update_option('deafaultNoticeInterval', ( time() + intval($info_value) * 24 * 60 * 60 ));
            }

            wp_send_json_success(
                [
                'response_type' => 'success',
                ]
            );
        }
    }
}
