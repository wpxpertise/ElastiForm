<?php
/**
 * Represents as plugin base file.
 *
 * @since   1.0.0
 * @package ElastiForm
 */

namespace ElastiForm {

    // If direct access than exit the file.
    defined('ABSPATH') || exit;

    /**
     * Represents as plugin base file.
     *
     * @since 1.0.0
     */
    class ElastiForm
    {

        /**
         * Holds the instance of the plugin currently in use.
         *
         * @var ElastiForm\ElastiForm
         */
        private static $instance = null;

        /**
         * Contains the helpers methods.
         *
         * @var ElastiForm\Helpers
         */
        public $helpers;

        /**
         * Contains plugin notices.
         *
         * @var ElastiForm\Notices
         */
        public $notices;

        /**
         * Contains the plugin assets.
         *
         * @var ElastiForm\Assets
         */
        public $assets;

        /**
         * Contains the plugin multisite functionalities.
         *
         * @var ElastiForm\Multisite
         */
        public $multisite;

        /**
         * Contains the admin functionalities.
         *
         * @var ElastiForm\Admin
         */
        public $admin;

        /**
         * Contains the plugin settings.
         *
         * @var ElastiForm\Settings
         */
        public $settings;

        /**
         * Contains the plugin settings api.
         *
         * @var ElastiForm\SettingsApi
         */
        public $settingsApi;

        /**
         * Contains the plugin shortcode.
         *
         * @var ElastiForm\Shortcode
         */
        public $shortcode;
        public $floatingWidgets;

        /**
         * Contains the plugin database helpers.
         *
         * @var ElastiForm\Database
         */
        public $database;

        /**
         * Contains the plugin ajax endpoints.
         *
         * @var ElastiForm\Ajax
         */
        public $ajax;

        /**
         * Main Plugin Instance.
         *
         * Insures that only one instance of the addon exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since  1.0.0
         * @return ElastiForm\ElastiForm
         */
        public static function getInstance()
        {
            if (null === self::$instance || ! self::$instance instanceof self ) {
                self::$instance = new self();

                self::$instance->init();
            }

            return self::$instance;
        }

        /**
         * Class constructor.
         *
         * @since 1.0.0
         */
        public function init()
        {
            $this->includes();
            $this->loader();

            if (ElastiForm()->helpers->version_check() ) {
                return;
            }
        }

        /**
         * Instantiate plugin available classes.
         *
         * @since 1.0.0
         */
        public function includes()
        {
            $dependencies = [
            '/vendor/autoload.php',
            ];

            foreach ( $dependencies as $path ) {
                if (! file_exists(ELASTIFORMBASE_PATH . $path) ) {
                    status_header(500);
                    wp_die(esc_html__('Plugin is missing required dependencies. Please contact support for more information.', 'elastiform'));
                }

                include ELASTIFORMBASE_PATH . $path;
            }
        }

        /**
         * Load plugin classes.
         *
         * @since  1.0.0
         * @return void
         */
        private function loader()
        {
            add_action('admin_init', [ $this, 'redirection' ]);
            add_filter('plugin_action_links_' . plugin_basename(ELASTIFORMPLUGIN_FILE), [ $this, 'add_action_links' ]);

            register_activation_hook(ELASTIFORMPLUGIN_FILE, [ $this, 'register_active_deactive_hooks' ]);

            $this->helpers     = new \ElastiForm\Helpers();
            $this->multisite   = new \ElastiForm\Multisite();
            $this->assets      = new \ElastiForm\Assets();
            $this->admin       = new \ElastiForm\Admin();
            $this->shortcode   = new \ElastiForm\Shortcode();
            $this->floatingWidgets   = new \ElastiForm\FloatingWidget();
            $this->database    = new \ElastiForm\Database();
            $this->ajax        = new \ElastiForm\Ajax();
        }



        /**
         * Add plugin action links.
         *
         * @param  array $links The plugin links.
         * @return array
         */
        public function add_action_links( $links )
        {
            $plugin = [
            sprintf(
                '<a href="%s">%s</a>',
                esc_url(admin_url('admin.php?page=ElastiForm-dashboard')),
                esc_html__('Dashboard', 'elastiform')
            ),

            ];

            return array_merge($links, $plugin);
        }

        /**
         * Redirect to admin page on plugin activation
         *
         * @since 1.0.0
         */
        public function redirection()
        {
            $redirect_to_admin_page = absint(get_option('ElastiForm_activation_redirect', 0));

            if (1 === $redirect_to_admin_page ) {
                delete_option('ElastiForm_activation_redirect');
                wp_safe_redirect(admin_url('admin.php?page=ElastiForm-dashboard'));
                // wp_safe_redirect( admin_url( 'admin.php' ) );
                exit;
            }
        }

        /**
         * Registering activation and deactivation Hooks
         *
         * @param  int $network_wide The network site ID.
         * @return void
         */
        public function register_active_deactive_hooks( $network_wide )
        {
            ElastiForm()->database->migration->run($network_wide);

            $form_settings = [
            'selectedTable' => null,
            'selectedWhatsapp' => null,
            'whatsappRedirection' => false,
            'formCustomization' => false,
            'floatingwidgets' => false,

            'whatsappNumber' => '',
            'openInNewTab' => false,

            'submitbtntext' => 'Send Message',
            'formheader' => "Have question? - Submit the Form",
            'formcta' => 'Have queries?',

            'submitbtnbgcolor' => "#FFA500",
            'submitbtntextcolor' => "#FFFFFF",
            'submitbtntexthovercolor' => "#3F98D2",

            'headerbackgroundcolor' => "#293239",
            'headertextcolor' => "#FFFFFF",

            'formfieldtextcolor' => "#293239",
            'formbackgroundcolor' => "#F7F7F7",

            'flotingwidgetsbgcolor' => "#0065A0",
            'selectedFont' => 'Arial',
            ];

            add_option('form_settings', json_encode($form_settings));

            add_option('ElastiForm_activation_redirect', 1);

            if (! get_option('ElastiFormActivationTime') ) {
                add_option('ElastiFormActivationTime', time());
            }

            flush_rewrite_rules();
        }
    }
}

namespace {
    // if direct access than exit the file.
    defined('ABSPATH') || exit;

    /**
     * This function is responsible for running the main plugin.
     *
     * @since  1.0.0
     * @return object ElastiForm\ElastiForm The plugin instance.
     */
    function ElastiForm()
    {
        return \ElastiForm\ElastiForm::getInstance();
    }
}
