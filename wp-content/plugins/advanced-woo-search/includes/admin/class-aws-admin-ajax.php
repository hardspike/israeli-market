<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Admin_Ajax' ) ) :

    /**
     * Class for plugin admin ajax hooks
     */
    class AWS_Admin_Ajax {

        /*
         * Constructor
         */
        public function __construct() {

            add_action( 'wp_ajax_aws-changeState', array( &$this, 'change_state' ) );

        }

        /*
         * Change option state
         */
        public function change_state() {

            check_ajax_referer( 'aws_admin_ajax_nonce' );

            $setting     = sanitize_text_field( $_POST['setting'] );
            $option      = sanitize_text_field( $_POST['option'] );
            $state       = sanitize_text_field( $_POST['state'] );

            $settings = $this->get_settings();

            $settings[$setting][$option] = $state ? 0 : 1;

            update_option( 'aws_settings', $settings );

            do_action( 'aws_cache_clear' );

            wp_send_json_success( '1' );

        }

        /*
         * Get plugin settings
         */
        private function get_settings() {
            $plugin_options = get_option( 'aws_settings' );
            return $plugin_options;
        }

    }

endif;


new AWS_Admin_Ajax();