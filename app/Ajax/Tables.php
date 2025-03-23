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
 * Responsible for handling table operations.
 *
 * @since   1.0.0
 * @package ElastiForm
 */
class Tables
{

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('wp_ajax_ElastiForm_create_form', [ $this, 'create' ]);
        add_action('wp_ajax_ElastiForm_save_settings', [ $this, 'save_settings' ]);

        add_action('wp_ajax_ElastiForm_get_tables', [ $this, 'get_all' ]);
        add_action('wp_ajax_ElastiForm_get_leads', [ $this, 'get_all_leads' ]);
        
        add_action('wp_ajax_ElastiForm_get_settings', [ $this, 'get_settings' ]);

        add_action('wp_ajax_ElastiForm_delete_table', [ $this, 'delete' ]);
        add_action('wp_ajax_ElastiForm_delete_leads', [ $this, 'delete_leads' ]);

        add_action('wp_ajax_ElastiForm_edit_table', [ $this, 'edit' ]);
        add_action('wp_ajax_ElastiForm_save_table', [ $this, 'save' ]);

        add_action('wp_ajax_ElastiForm_table_html', [ $this, 'rendertable' ]);
        add_action('wp_ajax_nopriv_ElastiForm_table_html', [ $this, 'rendertable' ]);

        add_action('wp_ajax_ElastiForm_get_submit_data', [ $this, 'get_submitdata' ]);
        add_action('wp_ajax_nopriv_ElastiForm_get_submit_data', [ $this, 'get_submitdata' ]);
    }

    /**
     * Create table on ajax request.
     *
     * @since 3.0.0
     */
    public function create()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', 'elastiform'),
                ]
            );
        }

        function sanitize_text_or_array_field( $array_or_string )
        {
            if (is_string($array_or_string) ) {
                $array_or_string = sanitize_text_field($array_or_string);
            } elseif (is_array($array_or_string) ) {
                foreach ( $array_or_string as $key => &$value ) {
                    if (is_array($value) ) {
                        $value = sanitize_text_or_array_field($value);
                    } else {
                        $value = sanitize_text_field($value);
                    }
                }
            }
            return $array_or_string;
        }

        $name     = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : __('Untitled', 'elastiform');
        $from_data = isset($_POST['formdata']) ? sanitize_text_or_array_field(wp_unslash($_POST['formdata'])) : [];

        $table = [
        'form_name'     => $name,
        'form_fields'     => $from_data,
        'time'     => current_time('mysql'),
        ];

        $table_id = ElastiForm()->database->table->insert($table);

        wp_send_json_success(
            [
            'id'      => absint($table_id),
            'form_name'      => $name,
            'form_fields'     => $from_data,
            'message' => esc_html__('Table created successfully', 'elastiform'),
            ]
        );
    }

    public function save_settings()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', 'elastiform'),
                ]
            );
        }

        function sanitize_text_or_array_field( $array_or_string )
        {
            if (is_string($array_or_string) ) {
                $array_or_string = sanitize_text_field($array_or_string);
            } elseif (is_array($array_or_string) ) {
                foreach ( $array_or_string as $key => &$value ) {
                    if (is_array($value) ) {
                        $value = sanitize_text_or_array_field($value);
                    } else {
                        $value = sanitize_text_field($value);
                    }
                }
            }
            return $array_or_string;
        }

        $from_data_settings = isset($_POST['settings']) ? sanitize_text_or_array_field(wp_unslash($_POST['settings'])) : [];

        // error_log( 'Data Received: ' . print_r( $from_data_settings, true ) );

        // Save the settings in the WordPress options table
        update_option('form_settings', $from_data_settings);

        if (false === get_option('form_settings') ) {
            wp_send_json_error(
                [
                'message' => esc_html__('Failed to save settings.', 'elastiform'),
                ]
            );
        }

        wp_send_json_success(
            [
            'settings'     => $from_data_settings,
            'message' => esc_html__('Settings created successfully', 'elastiform'),
            ]
        );
    }


    /**
     * Get all tables on ajax request.
     *
     * @since 3.0.0
     */
    public function get_all()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', 'elastiform'),
                ]
            );
        }

        $tables = ElastiForm()->database->table->get_all();

        wp_send_json_success(
            [
            'tables'       => $tables,
            'tables_count' => count($tables),
            ]
        );
    }
    

    public function get_all_leads()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', ''),
                ]
            );
        }

        $table_id = ! empty($_POST['form_id']) ? absint($_POST['form_id']) : 0;

        if (! $table_id ) {
            wp_send_json_error(
                [
                'message' => __('Invalid table to edit.', 'elastiform'),
                ]
            );
        }

        $table = ElastiForm()->database->table->getleads($table_id);

        // error_log( 'Data Received: ' . print_r( $table, true ) );

        if (! $table ) {
            wp_send_json_error(
                [
                'type'   => 'invalid_request',
                'output' => esc_html__('Request is invalid', 'elastiform'),
                ]
            );
        }

        wp_send_json_success(
            [
            'tables'       => $table,
            ]
        );
    }

    /**
     * Settigns get
     */
    public function get_settings()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', 'elastiform'),
                ]
            );
        }

        $settings = ElastiForm()->database->table->get_settings();

        wp_send_json_success(
            [
            'settings'       => $settings
            ]
        );
    }


    /**
     * Delete table by id.
     *
     * @param  int $id The table ID.
     * @return int|false
     */
    public function delete()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', ''),
                ]
            );
        }

        $id = ! empty($_POST['id']) ? absint($_POST['id']) : false;
        $tables = ElastiForm()->database->table->get_all();

        if ($id ) {
            $response = ElastiForm()->database->table->delete($id);

            if ($response ) {
                wp_send_json_success(
                    [
                    'message'      => sprintf(__('%s form deleted.', ''), $response),
                    'tables'       => $tables,
                    'tables_count' => count(ElastiForm()->database->table->get_all()),
                    ]
                );
            }

            wp_send_json_error(
                [
                'message'      => sprintf(__('Failed to delete form with id %d'), $id),
                'tables'       => $tables,
                'tables_count' => count(ElastiForm()->database->table->get_all()),
                ]
            );
        }

        wp_send_json_error(
            [
            'message'      => sprintf(__('Invalid table to perform delete.'), $id),
            'tables'       => $tables,
            'tables_count' => count(ElastiForm()->database->table->get_all()),
            ]
        );
    }

    public function delete_leads()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', ''),
                ]
            );
        }

        $id = ! empty($_POST['id']) ? absint($_POST['id']) : false;
        $tables = ElastiForm()->database->table->get_all();

        if ($id ) {
            $response = ElastiForm()->database->table->deleteleads($id);

            if ($response ) {
                wp_send_json_success(
                    [
                    'message'      => sprintf(__('%s form deleted.', ''), $response),
                    'tables'       => $tables,
                    'tables_count' => count(ElastiForm()->database->table->get_all()),
                    ]
                );
            }

            wp_send_json_error(
                [
                'message'      => sprintf(__('Failed to delete form with id %d'), $id),
                'tables'       => $tables,
                'tables_count' => count(ElastiForm()->database->table->get_all()),
                ]
            );
        }

        wp_send_json_error(
            [
            'message'      => sprintf(__('Invalid table to perform delete.'), $id),
            'tables'       => $tables,
            'tables_count' => count(ElastiForm()->database->table->get_all()),
            ]
        );
    }


    /**
     * Edit table on ajax request.
     *
     * @since 3.0.0
     */
    public function edit()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', ''),
                ]
            );
        }

        $table_id = ! empty($_POST['id']) ? absint($_POST['id']) : 0;

        // error_log( 'Data Received: ' . print_r( $table_id, true ) );

        if (! $table_id ) {
            wp_send_json_error(
                [
                'message' => __('Invalid table to edit.', 'elastiform'),
                ]
            );
        }

        $table = ElastiForm()->database->table->get($table_id);

        if (! $table ) {
            wp_send_json_error(
                [
                'type'   => 'invalid_request',
                'output' => esc_html__('Request is invalid', 'elastiform'),
                ]
            );
        }

        $settings   = json_decode($table['form_fields'], true);

        // error_log( 'Data Received: ' . print_r( $settings, true ) );

        wp_send_json_success(
            [
            'form_name'     => esc_attr($table['form_name']),
            'table_settings' => $settings,
            ]
        );
    }


    /**
     * Save table by id.
     */
    public function save()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm-admin-app-nonce-action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', ''),
                ]
            );
        }

        function sanitize_text_or_array_field( $array_or_string )
        {
            if (is_string($array_or_string) ) {
                $array_or_string = sanitize_text_field($array_or_string);
            } elseif (is_array($array_or_string) ) {
                foreach ( $array_or_string as $key => &$value ) {
                    if (is_array($value) ) {
                        $value = sanitize_text_or_array_field($value);
                    } else {
                        $value = sanitize_text_field($value);
                    }
                }
            }
            return $array_or_string;
        }

        $id = ! empty($_POST['id']) ? absint($_POST['id']) : false;
        $name     = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : __('Untitled', 'elastiform');
        $from_data = isset($_POST['formdata']) ? sanitize_text_or_array_field(wp_unslash($_POST['formdata'])) : [];

        $table = [
        'id'  => $id,
        'form_name'     => $name,
        'form_fields'     => $from_data,
        'time'     => current_time('mysql'),
        ];

        // error_log( 'Data Received: ' . print_r( $id, true ) );

        $table_id = ElastiForm()->database->table->update($id, $table);

        wp_send_json_success(
            [
            'id'      => absint($table_id),
            'form_name'     => esc_attr($name),
            'form_fields' => json_encode($from_data, true),
            'message' => __('Table updated successfully.', 'elastiform'),

            ]
        );
    }


    /**
     * Get Form tables on ajax request.
     *
     * @since 3.0.0
     */
    public function rendertable()
    {
        
        if (! isset($_GET['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['nonce'])), 'ElastiForm_sheet_nonce_action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', ''),
                ]
            );
        }


        $table_id = ! empty($_GET['id']) ? absint($_GET['id']) : 0;

        if (! $table_id ) {
            wp_send_json_error(
                [
                'message' => __('Invalid table to edit.', 'elastiform'),
                ]
            );
        }

        $table = ElastiForm()->database->table->get($table_id);

        if (! $table ) {
            wp_send_json_error(
                [
                'type'   => 'invalid_request',
                'output' => esc_html__('Request is invalid', 'elastiform'),
                ]
            );
        }

        $settings   = json_decode($table['form_fields'], true);

        wp_send_json_success(
            [
            'form_name'     => esc_attr($table['form_name']),
            'table_settings' => $settings,
            ]
        );
    }

    /**
     * Get Form submitted data on ajax request.
     *
     * @since 3.0.0
     */
    public function get_submitdata()
    {
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ElastiForm_sheet_nonce_action') ) {
            wp_send_json_error(
                [
                'message' => __('Invalid nonce.', ''),
                ]
            );
        }

        $id = isset($_POST['id']) ? sanitize_text_field(wp_unslash($_POST['id'])) : 'elastiform';
        // Sanitize and validate form_data.
        $form_data = isset($_POST['form_data']) ? json_decode(stripslashes(wp_unslash($_POST['form_data'])), true) : array();
        $form_data = is_array($form_data) ? array_map('sanitize_text_field', $form_data) : array();

        if (empty($form_data) ) {
            wp_send_json_error(
                array(
                'message' => esc_html__('Form data is empty, not storing in the database.', 'elastiform'),
                ) 
            );
        }

        // Validate and sanitize other values.
        $table = array(
        'form_id' => $id,
        'fields'  => $form_data,
        'time'    => current_time('mysql'),
        );

        $table_id = ElastiForm()->database->table->insertleads($table);

        /**
         * WhatsApp redirection.
         */
        $options = get_option('form_settings');

        $selectedWhatsapp = isset($options['selectedWhatsapp']) && $options['selectedWhatsapp'] !== '' ? array_map('sanitize_text_field', $options['selectedWhatsapp']) : [];
        $mailNotification = isset($options['mailNotification']) ? filter_var($options['mailNotification'], FILTER_VALIDATE_BOOLEAN) : false;
        $recipientMail = isset($options['recipientMail']) ? sanitize_email($options['recipientMail']) : null;

        if (in_array($id, $selectedWhatsapp) ) {

            // WhatsApp redirection
            if (isset($options['whatsappRedirection']) && $options['whatsappRedirection'] === 'true' ) {

                $whatsappNumber = $options['whatsappNumber'];
                $openInNewTab = $options['openInNewTab'];

                $whatsappNumber = preg_replace('/[^0-9\+]/', 'elastiform', $whatsappNumber);
                if (substr($whatsappNumber, 0, 1) !== '+' ) {
                    $whatsappNumber = '+' . $whatsappNumber;
                }

                if ('true' !== $openInNewTab ) {
                    // Ensure $form_data is a string by joining array elements
                    $form_data_str = is_array($form_data) ? implode(' ', $form_data) : $form_data;
                    $wh_url = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode(html_entity_decode($form_data_str));
                } else {
                    // Ensure $form_data is a string by joining array elements
                    $form_data_str = is_array($form_data) ? implode(' ', $form_data) : $form_data;
                    $wh_url = 'https://web.whatsapp.com/send?phone=' . $whatsappNumber . '&text=' . urlencode(html_entity_decode($form_data_str));
                }

                $simple_form_new_opt = [];
                // Send to WhatsApp now it has no used as url set from JS with new update code.
                $simple_form_new_opt['simple_form_whatsapp_url'] = $wh_url;
                $simple_form_new_opt['simple_form_whatsapp_number'] = $whatsappNumber;
                $simple_form_new_opt['simple_form_whatsapp_data'] = $form_data;
                $simple_form_new_opt['simple_form_new_tab'] = $openInNewTab;

                // Add nonce.
                $nonce = wp_create_nonce('simple_form_submission');
                $simple_form_new_opt['nonce'] = $nonce;

                $cookie_name = 'simple_form_whatsapp_data';
                setcookie($cookie_name, json_encode($simple_form_new_opt), time() + ( 86400 * 30 ), '/');
            }
        }

        wp_send_json_success(
            [
            'message' => __('Form data received and processed successfully.', 'elastiform'),
            'form_data' => $table,
            ]
        );
    }
}
