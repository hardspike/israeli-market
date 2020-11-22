<?php
/**
 * AWS plugin elementor widgets init
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Elementor_Init' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Elementor_Init {

        /**
         * @var AWS_Elementor_Init The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Elementor_Init Instance
         *
         * Ensures only one instance of AWS_Elementor_Init is loaded or can be loaded.
         *
         * @static
         * @return AWS_Elementor_Init - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {
            add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_elementor_widgets' ) );
            add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'filter_editor_styles' ) );
            add_action( 'elementor/preview/enqueue_styles', array( $this, 'filter_editor_styles' ) );
        }

        /**
         * Register elementor widget
         */
        public function register_elementor_widgets() {
            include_once( 'class-elementor-aws-widget.php' );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_AWS_Widget() );
        }

        /**
         * Enqueue editor filter styles
         */
        public function filter_editor_styles() {

            wp_enqueue_style(
                'aws-icons',
                AWS_URL . '/includes/modules/elementor-widget/elementor.css', array(), AWS_VERSION
            );

        }
        
    }

endif;

AWS_Elementor_Init::instance();