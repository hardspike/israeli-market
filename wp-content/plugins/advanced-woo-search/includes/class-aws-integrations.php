<?php
/**
 * AWS plugin integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Integrations' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Integrations {

        private $data = array();

        /**
         * @var AWS_Integrations Current theme name
         */
        private $current_theme = '';

        /**
         * @var AWS_Integrations The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Integrations Instance
         *
         * Ensures only one instance of AWS_Integrations is loaded or can be loaded.
         *
         * @static
         * @return AWS_Integrations - Main instance
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

            $theme = function_exists( 'wp_get_theme' ) ? wp_get_theme() : false;

            if ( $theme ) {
                $this->current_theme = $theme->name;
                $this->child_theme = $theme->name;
                if ( $theme->parent() ) {
                    $this->current_theme = $theme->parent();
                }
            }

            $this->includes();

            //add_action('woocommerce_product_query', array( $this, 'woocommerce_product_query' ) );

            if ( class_exists( 'BM' ) ) {
                add_action( 'aws_search_start', array( $this, 'b2b_set_filter' ) );
            }

            // Protected categories
            if ( class_exists( 'WC_PPC_Util' )
                && method_exists( 'WC_PPC_Util', 'showing_protected_categories' )
                && method_exists( 'WC_PPC_Util', 'to_category_visibilities' )
                && method_exists( 'WC_PPC_Util', 'get_product_categories' )
            ) {
                add_action( 'aws_search_start', array( $this, 'wc_ppc_set_filter' ) );
            }

            if ( function_exists( 'dfrapi_currency_code_to_sign' ) ) {
                add_filter( 'woocommerce_currency_symbol', array( $this, 'dfrapi_set_currency_symbol_filter' ), 10, 2 );
            }

            // WC Marketplace - https://wc-marketplace.com/
            if ( defined( 'WCMp_PLUGIN_VERSION' ) ) {
                add_filter( 'aws_search_data_params', array( $this, 'wc_marketplace_filter' ), 10, 3 );
                add_filter( 'aws_search_pre_filter_products', array( $this, 'wc_marketplace_products_filter' ), 10, 2 );
            }

            // Maya shop theme
            if ( defined( 'YIW_THEME_PATH' ) ) {
                add_action( 'wp_head', array( $this, 'myashop_head_action' ) );
            }

            // Porto theme
            add_filter( 'porto_search_form_content', array( $this, 'porto_search_form_content_filter' ) );

            add_filter( 'aws_terms_exclude_product_cat', array( $this, 'filter_protected_cats_term_exclude' ) );
            add_filter( 'aws_exclude_products', array( $this, 'filter_products_exclude' ) );

            // Seamless integration
            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {

                add_filter( 'aws_js_seamless_selectors', array( $this, 'js_seamless_selectors' ) );

                add_filter( 'et_html_main_header', array( $this, 'et_html_main_header' ) );
                add_filter( 'et_html_slide_header', array( $this, 'et_html_main_header' ) );
                add_filter( 'generate_navigation_search_output', array( $this, 'generate_navigation_search_output' ) );
                add_filter( 'et_pb_search_shortcode_output', array( $this, 'divi_builder_search_module' ) );
                add_filter( 'et_pb_menu_shortcode_output', array( $this, 'divi_builder_search_module' ) );
                add_filter( 'et_pb_fullwidth_menu_shortcode_output', array( $this, 'divi_builder_search_module' ) );

                // Ocean wp theme
                if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
                    add_action( 'wp_head', array( $this, 'oceanwp_head_action' ) );
                }

                // Avada theme
                if ( class_exists( 'Avada' ) ) {
                    add_action( 'wp_head', array( $this, 'avada_head_action' ) );
                }

                // Twenty Twenty theme
                if (  function_exists( 'twentytwenty_theme_support' ) ) {
                    add_action( 'wp_head', array( $this, 'twenty_twenty_head_action' ) );
                }

                if ( 'Jupiter' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'jupiter_head_action' ) );
                }

                if ( 'Woodmart' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'woodmart_head_action' ) );
                }

                if ( 'Storefront' === $this->current_theme ) {
                    add_action( 'wp_footer', array( $this, 'storefront_footer_action' ) );
                }

                // Elementor pro
                if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
                    add_action( 'wp_footer', array( $this, 'elementor_pro_popup' ) );
                    add_filter( 'elementor/widget/render_content', array( $this, 'elementor_render_content' ), 10, 2 );
                }

            }

            add_action( 'wp_head', array( $this, 'head_js_integration' ) );

            // Wholesale plugin hide certain products
            if ( class_exists( 'WooCommerceWholeSalePrices' ) ) {
                add_filter( 'aws_search_results_products', array( $this, 'wholesale_hide_products' ) );
            }

            // Ultimate Member plugin hide certain products
            if ( class_exists( 'UM_Functions' ) ) {
                add_filter( 'aws_search_results_products', array( $this, 'um_hide_products' ) );
            }

            // Search Exclude plugin
            if ( class_exists( 'SearchExclude' ) ) {
                add_filter( 'aws_index_product_ids', array( $this, 'search_exclude_filter' ) );
            }

            // WooCommerce Product Table plugin
            if ( class_exists( 'WC_Product_Table_Plugin' ) ) {
                add_filter( 'wc_product_table_data_config', array( $this, 'wc_product_table_data_config' ) );
                add_filter( 'aws_posts_per_page', array( $this, 'wc_product_table_posts_per_page' ) );
            }

            // Flatsome theme remove search page blocl
            if ( isset( $_GET['type_aws'] ) && function_exists( 'flatsome_pages_in_search_results' ) ) {
                remove_action('woocommerce_after_main_content','flatsome_pages_in_search_results', 10);
            }

            // Divi builder dynamic text shortcodes
            if ( defined( 'ET_BUILDER_PLUGIN_DIR' ) ) {
                add_filter( 'aws_before_strip_shortcodes', array( $this, 'divi_builder_strip_shortcodes' ) );
            }

            // WP all import finish
            add_action( 'pmxi_after_xml_import', array( $this, 'pmxi_after_xml_import' ) );

            // BeRocket WooCommerce AJAX Products Filter
            if ( defined( 'BeRocket_AJAX_filters_version' ) ) {
                add_filter( 'aws_search_page_filters', array( $this, 'berocket_search_page_filters' ) );
            }

            // Product Sort and Display for WooCommerce plugin
            if ( defined( 'WC_PSAD_NAME' ) ) {
                add_filter( "option_psad_shop_page_enable", array( $this, 'psad_filter' ) );
            }

            if ( 'Avada' === $this->current_theme ) {
                add_filter( 'aws_posts_per_page', array( $this, 'avada_posts_per_page' ), 1 );
                add_filter( 'aws_products_order_by', array( $this, 'avada_aws_products_order_by' ), 1 );
                add_filter( 'post_class', array( $this, 'avada_post_class' ) );
            }

            // FacetWP plugin
            if ( class_exists( 'FacetWP' ) ) {
                add_filter( 'facetwp_filtered_post_ids', array( $this, 'facetwp_filtered_post_ids' ), 1 );
                add_filter( 'aws_searchpage_enabled', array( $this, 'facetwp_aws_searchpage_enabled' ), 1 );
            }

            // Product Visibility by User Role for WooCommerce plugin
            if ( class_exists( 'Alg_WC_PVBUR' ) ) {
                add_filter( 'aws_search_results_products', array( $this, 'pvbur_aws_search_results_products' ), 1 );
            }

        }

        /**
         * Include files
         */
        public function includes() {

            // Elementor plugin widget
            if ( defined( 'ELEMENTOR_VERSION' ) ) {
                include_once( AWS_DIR . '/includes/modules/elementor-widget/class-elementor-aws-init.php' );
            }

            // Divi module
            if ( defined( 'ET_BUILDER_PLUGIN_DIR' ) || function_exists( 'et_setup_theme' ) ) {
                include_once( AWS_DIR . '/includes/modules/divi/class-divi-aws-module.php' );
            }

        }

        /*
         * B2B market plugin
         */
        public function b2b_set_filter() {

            $args = array(
                'posts_per_page' => - 1,
                'post_type'      => 'customer_groups',
                'post_status'    => 'publish',
            );

            $posts           = get_posts( $args );
            $customer_groups = array();
            $user_role       = '';

            foreach ( $posts as $customer_group ) {
                $customer_groups[$customer_group->post_name] = $customer_group->ID;
            }

            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];
            } else {
                $guest_slugs = array( 'Gast', 'Gäste', 'Guest', 'Guests', 'gast', 'gäste', 'guest', 'guests' );
                foreach( $customer_groups as $customer_group_key => $customer_group_id ) {
                    if ( in_array( $customer_group_key, $guest_slugs ) ) {
                        $user_role = $customer_group_key;
                    }
                }
            }

            if ( $user_role ) {

                if ( isset( $customer_groups[$user_role] ) ) {
                    $curret_customer_group_id = $customer_groups[$user_role];

                    $whitelist = get_post_meta( $curret_customer_group_id, 'bm_conditional_all_products', true );

                    if ( $whitelist && $whitelist === 'off' ) {

                        $products_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_products', false );
                        $cats_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_categories', false );

                        if ( $products_to_exclude && ! empty( $products_to_exclude ) ) {

                            foreach( $products_to_exclude as $product_to_exclude ) {
                                $this->data['exclude_products'][] = trim( $product_to_exclude, ',' );
                            }

                        }

                        if ( $cats_to_exclude && ! empty( $cats_to_exclude ) ) {

                            foreach( $cats_to_exclude as $cat_to_exclude ) {
                                $this->data['exclude_categories'][] = trim( $cat_to_exclude, ',' );
                            }

                        }

                    }

                }

            }

        }

        /*
         * Protected categories plugin
         */
        public function wc_ppc_set_filter() {

            $hidden_categories = array();
            $show_protected	   = WC_PPC_Util::showing_protected_categories();

            // Get all the product categories, and check which are hidden.
            foreach ( WC_PPC_Util::to_category_visibilities( WC_PPC_Util::get_product_categories() ) as $category ) {
                if ( $category->is_private() || ( ! $show_protected && $category->is_protected() ) ) {
                    $hidden_categories[] = $category->term_id;
                }
            }

            if ( $hidden_categories && ! empty( $hidden_categories ) ) {

                foreach( $hidden_categories as $hidden_category ) {
                    $this->data['exclude_categories'][] = $hidden_category;
                }

                $args = array(
                    'posts_per_page'      => -1,
                    'fields'              => 'ids',
                    'post_type'           => 'product',
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                    'suppress_filters'    => true,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'id',
                            'terms'    => $hidden_categories
                        )
                    )
                );

                $exclude_products = get_posts( $args );

                if ( $exclude_products && count( $exclude_products ) > 0 ) {

                    foreach( $exclude_products as $exclude_product ) {
                        $this->data['exclude_products'][] = $exclude_product;
                    }

                }

            }

        }

        /*
         * Datafeedr WooCommerce Importer plugin
         */
        public function dfrapi_set_currency_symbol_filter( $currency_symbol, $currency ) {

            global $product;
            if ( ! is_object( $product ) || ! isset( $product ) ) {
                return $currency_symbol;
            }
            $fields = get_post_meta( $product->get_id(), '_dfrps_product', true );
            if ( empty( $fields ) ) {
                return $currency_symbol;
            }
            if ( ! isset( $fields['currency'] ) ) {
                return $currency_symbol;
            }
            $currency_symbol = dfrapi_currency_code_to_sign( $fields['currency'] );
            return $currency_symbol;

        }

        /*
         * WC Marketplace plugin support
         */
        public function wc_marketplace_filter( $data, $post_id, $product ) {

            $wcmp_spmv_map_id = get_post_meta( $post_id, '_wcmp_spmv_map_id', true );

            if ( $wcmp_spmv_map_id ) {

                if ( isset( $data['wcmp_price'] ) && isset( $data['wcmp_price'][$wcmp_spmv_map_id] )  ) {

                    if ( $product->get_price() < $data['wcmp_price'][$wcmp_spmv_map_id] ) {
                        $data['wcmp_price'][$wcmp_spmv_map_id] = $product->get_price();
                        $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] = $post_id;
                    }

                } else {
                    $data['wcmp_price'][$wcmp_spmv_map_id] = $product->get_price();
                }

                $data['wcmp_spmv_product_id'][$wcmp_spmv_map_id][] = $post_id;

            }

            return $data;

        }

        /*
         * WC Marketplace plugin products filter
         */
        public function wc_marketplace_products_filter( $products_array, $data ) {

            $wcmp_spmv_exclude_ids = array();

            if ( isset( $data['wcmp_spmv_product_id'] ) ) {

                foreach( $data['wcmp_spmv_product_id'] as $wcmp_spmv_map_id => $wcmp_spmv_product_id ) {

                    if ( count( $wcmp_spmv_product_id ) > 1 ) {

                        if ( isset( $data['wcmp_lowest_price_id'] ) && isset( $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] ) ) {

                            foreach ( $wcmp_spmv_product_id as $wcmp_spmv_product_id_n ) {

                                if ( $wcmp_spmv_product_id_n === $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] ) {
                                    continue;
                                }

                                $wcmp_spmv_exclude_ids[] = $wcmp_spmv_product_id_n;

                            }

                        } else {

                            foreach ( $wcmp_spmv_product_id as $key => $wcmp_spmv_product_id_n ) {

                                if ( $key === 0 ) {
                                    continue;
                                }

                                $wcmp_spmv_exclude_ids[] = $wcmp_spmv_product_id_n;

                            }

                        }

                    }

                }

            }

            $new_product_array = array();

            foreach( $products_array as $key => $pr_arr ) {

                if ( ! in_array( $pr_arr['id'], $wcmp_spmv_exclude_ids ) ) {
                    $new_product_array[] = $pr_arr;
                }

            }

            return $new_product_array;

        }

        /*
         * Maya shop theme support
         */
        public function myashop_head_action() { ?>

            <style>
                #header .aws-container {
                    margin: 0;
                    position: absolute;
                    right: 0;
                    bottom: 85px;
                }

                @media only screen and (max-width: 960px) {
                    #header .aws-container {
                        bottom: 118px !important;
                        right: 10px !important;
                    }
                }

                @media only screen and (max-width: 600px) {
                    #header .aws-container {
                        position: relative !important;
                        bottom: auto !important;
                        right: auto !important;
                        display: inline-block !important;
                        margin-top: 20px !important;
                        margin-bottom: 20px !important;
                    }
                }
            </style>

        <?php }

        /*
         * Ocean wp theme
         */
        public function oceanwp_head_action() { ?>

            <style>
                .oceanwp-theme #searchform-header-replace .aws-container {
                    padding-right: 45px;
                    padding-top: 15px;
                }
                .oceanwp-theme #searchform-overlay .aws-container {
                    position: absolute;
                    top: 50%;
                    left: 0;
                    margin-top: -33px;
                    width: 100%;
                    text-align: center;
                }
                .oceanwp-theme #searchform-overlay .aws-container form {
                    position: static;
                }
                .oceanwp-theme #searchform-overlay a.search-overlay-close {
                    top: -100px;
                }
                #sidr .aws-container {
                    margin: 30px 20px 0;
                }
                #medium-searchform .aws-container,
                #vertical-searchform .aws-container {
                    background: #f5f5f5;
                }
                #medium-searchform .aws-container .aws-search-form .aws-search-field {
                    max-width: 100%;
                }
                #medium-searchform .aws-container .aws-search-form .aws-form-btn,
                #vertical-searchform .aws-container .aws-search-form .aws-form-btn{
                    background: #f5f5f5;
                    border: none;
                }
            </style>

            <script>

                window.addEventListener('load', function() {

                    window.setTimeout(function(){
                        var formOverlay = document.querySelector("#searchform-overlay form");
                        if ( formOverlay ) {
                            formOverlay.innerHTML += '<a href="#" class="search-overlay-close"><span></span></a>';
                        }
                    }, 300);

                    jQuery(document).on( 'click', 'a.search-overlay-close', function (e) {

                        jQuery( '#searchform-overlay' ).removeClass( 'active' );
                        jQuery( '#searchform-overlay' ).fadeOut( 200 );

                        setTimeout( function() {
                            jQuery( 'html' ).css( 'overflow', 'visible' );
                        }, 400);

                        jQuery( '.aws-search-result' ).hide();

                    } );

                }, false);

            </script>

        <?php }

        /*
         * Avada wp theme
         */
        public function avada_head_action() { ?>

            <style>

                .fusion-flyout-search .aws-container {
                    margin: 0 auto;
                    padding: 0;
                    width: 100%;
                    width: calc(100% - 40px);
                    max-width: 600px;
                    position: absolute;
                    top: 40%;
                    left: 20px;
                    right: 20px;
                }

            </style>

            <script>

                window.addEventListener('load', function() {
                    var awsSearch = document.querySelectorAll(".fusion-menu .fusion-main-menu-search a, .fusion-flyout-menu-icons .fusion-icon-search");
                    if ( awsSearch ) {
                        for (var i = 0; i < awsSearch.length; i++) {
                            awsSearch[i].addEventListener('click', function() {
                                window.setTimeout(function(){
                                    document.querySelector(".fusion-menu .fusion-main-menu-search .aws-search-field, .fusion-flyout-search .aws-search-field").focus();
                                }, 100);
                            }, false);
                        }
                    }

                }, false);

            </script>

        <?php }

        /*
         * Twenty Twenty theme
         */
        public function twenty_twenty_head_action() { ?>

            <style>

                .search-modal .aws-container {
                    width: 100%;
                    margin: 20px 0;
                }

            </style>

            <script>

                window.addEventListener('load', function() {

                    var awsSearch = document.querySelectorAll("#site-header .search-toggle");
                    if ( awsSearch ) {
                        for (var i = 0; i < awsSearch.length; i++) {
                            awsSearch[i].addEventListener('click', function() {
                                window.setTimeout(function(){
                                    document.querySelector(".aws-container .aws-search-field").focus();
                                    jQuery( '.aws-search-result' ).hide();
                                }, 100);
                            }, false);
                        }
                    }

                    var searchToggler = document.querySelectorAll('[data-modal-target-string=".search-modal"]');
                    if ( searchToggler ) {
                        for (var i = 0; i < searchToggler.length; i++) {
                            searchToggler[i].addEventListener('toggled', function() {
                                jQuery( '.aws-search-result' ).hide();
                            }, false);
                        }
                    }

                }, false);

            </script>

        <?php }

        /*
         * Jupiter theme
         */
        public function jupiter_head_action() { ?>

            <style>

                .mk-fullscreen-search-overlay .aws-container .aws-search-form {
                    height: 60px;
                }

                .mk-fullscreen-search-overlay .aws-container .aws-search-field {
                    width: 800px;
                    background-color: transparent;
                    box-shadow: 0 3px 0 0 rgba(255,255,255,.1);
                    border: none;
                    font-size: 35px;
                    color: #fff;
                    padding-bottom: 20px;
                    text-align: center;
                }

                .mk-fullscreen-search-overlay .aws-container .aws-search-form .aws-form-btn {
                    background-color: transparent;
                    border: none;
                    box-shadow: 0 3px 0 0 rgba(255,255,255,.1);
                }

                .mk-fullscreen-search-overlay .aws-container .aws-search-form .aws-search-btn_icon {
                    height: 30px;
                    line-height: 30px;
                }

                .mk-header .aws-container {
                    margin: 10px;
                }

                .mk-header .mk-responsive-wrap {
                    padding-bottom: 1px;
                }

            </style>

            <script>

                window.addEventListener('load', function() {

                    var iconSearch = document.querySelectorAll(".mk-fullscreen-trigger");
                    if ( iconSearch ) {
                        for (var i = 0; i < iconSearch.length; i++) {
                            iconSearch[i].addEventListener('click', function() {
                                window.setTimeout(function(){
                                    document.querySelector(".mk-fullscreen-search-overlay .aws-container .aws-search-field").focus();
                                    jQuery( '.aws-search-result' ).hide();
                                }, 100);
                            }, false);
                        }
                    }


                }, false);

            </script>

        <?php }

        /*
         * Woodmart theme
         */
        public function woodmart_head_action() { ?>

             <style>

                 .woodmart-search-full-screen .aws-container .aws-search-form {
                     padding-top: 0;
                     padding-right: 0;
                     padding-bottom: 0;
                     padding-left: 0;
                     height: 110px;
                     border: none;
                     background-color: transparent;
                     box-shadow: none;
                 }

                 .woodmart-search-full-screen .aws-container .aws-search-field {
                     color: #333;
                     text-align: center;
                     font-weight: 600;
                     font-size: 48px;
                 }

                 .woodmart-search-full-screen .aws-container .aws-search-form .aws-form-btn,
                 .woodmart-search-full-screen .aws-container .aws-search-form.aws-show-clear.aws-form-active .aws-search-clear {
                     display: none !important;
                 }

             </style>

        <?php }

        /*
         * Storefront theme search form layout
         */
        public function storefront_footer_action() { ?>
            <script>
                window.addEventListener('load', function() {
                    function aws_results_layout( styles, options  ) {
                        if ( typeof jQuery !== 'undefined' ) {
                            var $storefrontHandheld = options.form.closest('.storefront-handheld-footer-bar');
                            if ( $storefrontHandheld.length ) {
                                if ( ! $storefrontHandheld.find('.aws-search-result').length ) {
                                    $storefrontHandheld.append( options.resultsBlock );
                                }
                                styles.top = 'auto';
                                styles.bottom = 130;
                            }
                        }
                        return styles;
                    }
                    if ( typeof AwsHooks === 'object' && typeof AwsHooks.add_filter === 'function' ) {
                        AwsHooks.add_filter( 'aws_results_layout', aws_results_layout );
                    }
                }, false);
            </script>
            <style>
                .storefront-handheld-footer-bar .aws-search-result ul li {
                    float: none !important;
                    display: block !important;
                    text-align: left !important;
                }
                .storefront-handheld-footer-bar .aws-search-result ul li a {
                    text-indent: 0 !important;
                    text-decoration: none;
                }
            </style>
        <?php }

        /*
         * Elementor popup search form init
         */
        public function elementor_pro_popup() { ?>

            <script>
                window.addEventListener('load', function() {
                    if (window.jQuery) {
                        jQuery( document ).on( 'elementor/popup/show', function() {
                            window.setTimeout(function(){
                                jQuery('.elementor-container .aws-container').each( function() {
                                    jQuery(this).aws_search();
                                });
                            }, 1000);
                        } );
                    }
                }, false);
            </script>

        <?php }

        /*
         * Elementor replace search form widget
         */
        public function elementor_render_content( $content, $widget ) {
            if ( method_exists( $widget, 'get_name' ) && $widget->get_name() === 'search-form' ) {
                if ( method_exists( $widget, 'get_settings' )  ) {
                    $settings = $widget->get_settings();
                    if ( is_array( $settings ) && isset( $settings['skin'] ) && $settings['skin'] === 'full_screen' ) {
                        $content = '<style>
                            .elementor-search-form--skin-full_screen .elementor-search-form__container {
                                overflow: hidden;
                            }
                            .elementor-search-form--full-screen .aws-container {
                                width: 100%;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-form {
                                height: auto !important;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-form .aws-search-btn.aws-form-btn {
                                display: none;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-field {
                                border-bottom: 1px solid #fff !important;
                                font-size: 50px !important;
                                text-align: center !important;
                                line-height: 1.5 !important;
                                color: #7a7a7a !important;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-field:focus {
                                background-color: transparent !important;
                            }
                        </style>' . $content;
                        $content = str_replace( array( '<form', '</form>' ), array( '<div', '</div>' ), $content );
                        $content = preg_replace( '/(<input[\S\s]*?elementor-search-form__input[\S\s]*?\>)/i', aws_get_search_form( false ), $content );
                        return $content;
                    }
                }
                return aws_get_search_form( false );
            }
            return $content;
        }

        /*
         * Porto theme seamless integration
         */
        public function porto_search_form_content_filter( $markup ) {

            if ( AWS()->get_settings('seamless') === 'true' ) {
                $pattern = '/(<form[\S\s]*?<\/form>)/i';
                $markup = preg_replace( $pattern, aws_get_search_form( false ), $markup );
            }

            return $markup;

        }

        /*
         * Exclude product categories
         */
        public function filter_protected_cats_term_exclude( $exclude ) {
            if ( isset( $this->data['exclude_categories'] ) ) {
                foreach( $this->data['exclude_categories'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        /*
         * Exclude products
         */
        public function filter_products_exclude( $exclude ) {
            if ( isset( $this->data['exclude_products'] ) ) {
                foreach( $this->data['exclude_products'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        public function woocommerce_product_query( $query ) {

            $query_args = array(
                's'                => 'a',
                'post_type'        => 'product',
                'suppress_filters' => true,
                'fields'           => 'ids',
                'posts_per_page'   => 1
            );

            $query = new WP_Query( $query_args );
            $query_vars = $query->query_vars;

            $query_args_options = get_option( 'aws_search_query_args' );

            if ( ! $query_args_options ) {
                $query_args_options = array();
            }

            $user_role = 'non_login';

            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];
            }

            $query_args_options[$user_role] = array(
                'post__not_in' => $query_vars['post__not_in'],
                'category__not_in' => $query_vars['category__not_in'],
            );

            update_option( 'aws_search_query_args', $query_args_options );

        }

        /*
         * Divi theme seamless integration for header
         */
        public function et_html_main_header( $html ) {
            if ( function_exists( 'aws_get_search_form' ) ) {

                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $form = aws_get_search_form(false);

                if ( strpos( $html, 'aws-container' ) !== false ) {
                    $pattern = '/(<div class="aws-container"[\s\S]*?<form.*?<\/form><\/div>)/i';
                }

                $html = '<style>.et_search_outer .aws-container { position: absolute;right: 40px;top: 20px; }</style>' . $html;
                $html = trim(preg_replace('/\s\s+/', ' ', $html));
                $html = preg_replace( $pattern, $form, $html );

            }
            return $html;
        }

        /*
         * Generatepress theme support
         */
        public function generate_navigation_search_output( $html ) {
            if ( function_exists( 'aws_get_search_form' ) ) {
                $html = '<style>.navigation-search .aws-container .aws-search-form{height: 60px;} .navigation-search .aws-container{margin-right: 60px;} .navigation-search .aws-container .search-field{border:none;} </style>';
                $html .= '<script>
                     window.addEventListener("awsShowingResults", function(e) {
                         var links = document.querySelectorAll(".aws_result_link");
                         if ( links ) {
                            for (var i = 0; i < links.length; i++) {
                                links[i].className += " search-item";
                            }
                        }
                     }, false);
                    </script>';
                $html .= '<div class="navigation-search">' . aws_get_search_form( false ) . '</div>';
                $html = str_replace( 'aws-search-field', 'aws-search-field search-field', $html );
            }
            return $html;
        }

        /*
         * Divi builder replace search module
         */
        public function divi_builder_search_module( $output ) {
            if ( function_exists( 'aws_get_search_form' ) && is_string( $output ) ) {

                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $form = aws_get_search_form(false);

                if ( strpos( $output, 'aws-container' ) !== false ) {
                    $pattern = '/(<div class="aws-container"[\s\S]*?<form.*?<\/form><\/div>)/i';
                }

                $output = trim(preg_replace('/\s\s+/', ' ', $output));
                $output = preg_replace( $pattern, $form, $output );

            }
            return $output;
        }

        /*
         * Selector filter of js seamless
         */
        public function js_seamless_selectors( $selectors ) {

            // shopkeeper theme
            if ( function_exists( 'shopkeeper_theme_setup' ) ) {
                $selectors[] = '.site-search .woocommerce-product-search';
            }

            // ocean wp theme
            if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
                $selectors[] = '#searchform-header-replace form';
                $selectors[] = '#searchform-overlay form';
                $selectors[] = '#sidr .sidr-class-mobile-searchform';
                $selectors[] = '#mobile-menu-search form';
                $selectors[] = '#site-header form';
            }

            if ( 'Jupiter' === $this->current_theme ) {
                $selectors[] = '#mk-fullscreen-searchform';
                $selectors[] = '.responsive-searchform';
            }

            if ( 'Woodmart' === $this->current_theme ) {
                $selectors[] = '.woodmart-search-form form, form.woodmart-ajax-search';
            }

            if ( 'Venedor' === $this->current_theme ) {
                $selectors[] = '#search-form form';
            }

            return $selectors;

        }

        /*
         * Js seamless integration method
         */
        public function head_js_integration() {

            /**
             * Filter seamless integrations js selectors for forms
             * @since 1.85
             * @param array $forms Array of css selectors
             */
            $forms = apply_filters( 'aws_js_seamless_selectors', array() );

            if ( ! is_array( $forms ) || empty( $forms ) ) {
                return;
            }

            $forms_selector = implode( ',', $forms );

            ?>

            <script>

                window.addEventListener('load', function() {
                    var forms = document.querySelectorAll("<?php echo $forms_selector; ?>");

                    var awsFormHtml = <?php echo json_encode( str_replace( 'aws-container', 'aws-container aws-js-seamless', aws_get_search_form( false ) ) ); ?>;

                    if ( forms ) {

                        for ( var i = 0; i < forms.length; i++ ) {
                            if ( forms[i].parentNode.outerHTML.indexOf('aws-container') === -1 ) {
                                forms[i].outerHTML = awsFormHtml;
                            }
                        }

                        window.setTimeout(function(){
                            jQuery('.aws-js-seamless').each( function() {
                                jQuery(this).aws_search();
                            });
                        }, 1000);

                    }
                }, false);
            </script>

        <?php }

        /*
         * Wholesale plugin hide products
         */
        public function wholesale_hide_products( $products ) {

            $user_role = 'all';
            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $roles = ( array ) $user->roles;
                $user_role = $roles[0];
            }

            $all_registered_wholesale_roles = unserialize( get_option( 'wwp_options_registered_custom_roles' ) );
            if ( ! is_array( $all_registered_wholesale_roles ) ) {
                $all_registered_wholesale_roles = array();
            }

            $product_cat_wholesale_role_filter = get_option( 'wwpp_option_product_cat_wholesale_role_filter' );
            $categories_exclude_list = array();

            if ( is_array( $product_cat_wholesale_role_filter ) && ! empty( $product_cat_wholesale_role_filter ) && $user_role !== 'administrator' ) {
                foreach( $product_cat_wholesale_role_filter as $term_id => $term_roles ) {
                    if ( array_search( $user_role, $term_roles ) === false ) {
                        $categories_exclude_list[] = $term_id;
                    }
                }
            }

            $new_products_array = array();

            foreach( $products as $product ) {

                $custom_fields = get_post_meta( $product['id'], 'wwpp_product_wholesale_visibility_filter' );
                $custom_price = get_post_meta( $product['id'], 'wholesale_customer_wholesale_price' );

                if ( $custom_fields && ! empty( $custom_fields ) && $custom_fields[0] !== 'all' && $custom_fields[0] !== $user_role ) {
                    continue;
                }

                if ( is_user_logged_in() && !empty( $all_registered_wholesale_roles ) && isset( $all_registered_wholesale_roles[$user_role] )
                    && get_option( 'wwpp_settings_only_show_wholesale_products_to_wholesale_users', false ) === 'yes' && ! $custom_price ) {
                    continue;
                }

                if ( ! empty( $categories_exclude_list ) ) {
                    $terms = wp_get_object_terms( $product['id'], 'product_cat' );
                    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                        foreach ( $terms as $term ) {
                            if ( array_search( $term->term_id, $categories_exclude_list ) !== false ) {
                                continue 2;
                            }
                        }
                    }
                }

                $new_products_array[] = $product;

            }

            return $new_products_array;

        }

        /*
         * Ultimate Member hide products
         */
        public function um_hide_products( $products ) {

            foreach( $products as $key => $product ) {

                $um_content_restriction = get_post_meta( $product['id'], 'um_content_restriction', true );

                if ( $um_content_restriction && is_array( $um_content_restriction ) && ! empty( $um_content_restriction ) ) {

                    $um_custom_access_settings = isset( $um_content_restriction['_um_custom_access_settings'] ) ? $um_content_restriction['_um_custom_access_settings'] : false;
                    $um_access_hide_from_queries = isset( $um_content_restriction['_um_access_hide_from_queries'] ) ? $um_content_restriction['_um_access_hide_from_queries'] : false;

                    if ( $um_custom_access_settings && $um_custom_access_settings === '1' && $um_access_hide_from_queries && $um_access_hide_from_queries === '1' ) {

                        $um_accessible = isset( $um_content_restriction['_um_accessible'] ) ? $um_content_restriction['_um_accessible'] : false;

                        if ( $um_accessible ) {

                            if ( $um_accessible === '1' && is_user_logged_in() ) {
                                unset( $products[$key] );
                            }
                            elseif ( $um_accessible === '2' && ! is_user_logged_in() ) {
                                unset( $products[$key] );
                            }
                            elseif ( $um_accessible === '2' && is_user_logged_in() ) {

                                $um_access_roles = isset( $um_content_restriction['_um_access_roles'] ) ? $um_content_restriction['_um_access_roles'] : false;

                                if ( $um_access_roles && is_array( $um_access_roles ) && ! empty( $um_access_roles ) ) {
                                    $user = wp_get_current_user();
                                    $role = ( array ) $user->roles;
                                    $user_role = $role[0];
                                    if ( $user_role && $user_role !== 'administrator' && ! isset( $um_access_roles[$user_role] ) ) {
                                        unset( $products[$key] );
                                    }
                                }

                            }

                        }

                    }

                }

            }

            return $products;

        }

        /*
         * Remove products that was excluded with Search Exclude plugin ( https://wordpress.org/plugins/search-exclude/ )
         */
        public function search_exclude_filter( $products ) {

            $excluded = get_option('sep_exclude');

            if ( $excluded && is_array( $excluded ) && ! empty( $excluded ) && $products && is_array( $products ) ) {
                foreach( $products as $key => $product_id ) {
                    if ( false !== array_search( $product_id, $excluded ) ) {
                        unset( $products[$key] );
                    }
                }
            }

            return $products;

        }

        /*
         * Fix WooCommerce Product Table for search page
         */
        public function wc_product_table_data_config( $config ) {
            if ( isset( $_GET['type_aws'] ) && isset( $config['search'] ) ) {
                $config['search']['search'] = '';
            }
            return $config;
        }

        /*
         * WooCommerce Product Table plugin change number of products on page
         */
        public function wc_product_table_posts_per_page( $num ) {
            return 9999;
        }

        /*
         * Divi builder remove dynamic text shortcodes
         */
        public function divi_builder_strip_shortcodes( $str ) {
            $str = preg_replace( '#\[et_pb_text.[^\]]*?_dynamic_attributes.*?\]@ET-.*?\[\/et_pb_text\]#', '', $str );
            return $str;
        }

        /*
         * WP all import cron job
         */
        public function pmxi_after_xml_import() {
            $sunc = AWS()->get_settings( 'autoupdates' );
            if ( $sunc === 'true' ) {
                wp_schedule_single_event( time() + 1, 'aws_reindex_table' );
            }
        }

        /*
         * BeRocket WooCommerce AJAX Products Filter
         */
        public function berocket_search_page_filters( $filters ) {

            if ( isset( $_GET['filters'] ) ) {

                $get_filters = explode( '|', $_GET['filters'] );

                foreach( $get_filters as $get_filter ) {

                    if ( $get_filter === '_stock_status[1]' ) {
                        $filters['in_status'] = true;
                    } elseif ( $get_filter === '_stock_status[2]' ) {
                        $filters['in_status'] = false;
                    } elseif ( $get_filter === '_sale[1]' ) {
                        $filters['on_sale'] = true;
                    } elseif ( $get_filter === '_sale[2]' ) {
                        $filters['on_sale'] = false;
                    } elseif ( strpos( $get_filter, 'price[' ) === 0 ) {
                        if ( preg_match( '/([\w]+)\[(\d+)_(\d+)\]/', $get_filter, $matches ) ) {
                            $filters['price_min'] = intval( $matches[2] );
                            $filters['price_max'] = intval( $matches[3] );
                        }
                    } elseif ( preg_match( '/(.+)\[(.+?)\]/', $get_filter, $matches ) ) {
                        $taxonomy = $matches[1];
                        $operator = strpos( $matches[2], '-' ) !== false ? 'OR' : 'AND';
                        $explode_char = strpos( $matches[2], '-' ) !== false ? '-' : '+';
                        $filters['tax'][$taxonomy] = array(
                            'terms' => explode( $explode_char, $matches[2] ),
                            'operator' => $operator
                        );
                    }

                }

            }

            return $filters;

        }

        /*
         * Product Sort and Display for WooCommerce plugin disable on search page
         */
        function psad_filter( $value ) {
            if ( isset( $_GET['type_aws'] ) ) {
                return 'no';
            }
            return $value;
        }

        /*
         * Avada theme posts per page option
         */
        public function avada_posts_per_page( $posts_per_page ) {
            $posts_per_page = isset( $_GET['product_count'] ) && intval( sanitize_text_field( $_GET['product_count'] ) ) ? intval( sanitize_text_field( $_GET['product_count'] ) ) : 12;
            return $posts_per_page;
        }

        /*
         * Avada theme order by options
         */
        public function avada_aws_products_order_by( $order_by ) {

            $order_by_new = '';

            if ( isset( $_GET['product_orderby'] ) ) {
                switch( sanitize_text_field( $_GET['product_orderby'] ) ) {
                    case 'name':
                        $order_by_new = 'title';
                        break;
                    case 'price':
                        $order_by_new = 'price';
                        break;
                    case 'date':
                        $order_by_new = 'date';
                        break;
                    case 'popularity':
                        $order_by_new = 'popularity';
                        break;
                    case 'rating':
                        $order_by_new = 'rating';
                        break;
                }
            }

            if ( isset( $_GET['product_order'] ) && $order_by_new ) {
                $product_order = sanitize_text_field( $_GET['product_order'] );
                if ( in_array( $product_order, array( 'asc', 'desc' ) ) ) {
                    $order_by_new = $order_by_new . '-' . $product_order;
                }

            }

            if ( $order_by_new ) {
                $order_by = $order_by_new;
            }

            return $order_by;

        }

        /*
         * Avada theme fix for product variations inside list products view
         */
        public function avada_post_class( $classes ) {
            if ( 'product_variation' === get_post_type()  ) {
                if ( isset( $_SERVER['QUERY_STRING'] ) ) {
                    parse_str( sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ), $params );
                    if ( isset( $params['product_view'] ) && $params['product_view'] ) {
                        $classes[] = 'product-' . $params['product_view'] . '-view';
                    }
                }
            }
            return $classes;
        }

        /*
         * FacetWP check for active filters
         */
        public function facetwp_filtered_post_ids( $post_ids ) {
            if ( isset( $_GET['type_aws'] ) && isset( $_GET['s'] ) && ! empty( $post_ids ) ) {
                $this->data['facetwp'] = true;
            }
            return $post_ids;
        }

        /*
         * Disable AWS search if FacetWP is active
         */
        public function facetwp_aws_searchpage_enabled( $enabled ) {
            if ( isset( $this->data['facetwp'] ) && $this->data['facetwp'] ) {
                $enabled = false;
            }
            return $enabled;
        }

        /*
         * Product Visibility by User Role for WooCommerce plugin hide products for certain users
         */
        public function pvbur_aws_search_results_products( $products ) {

            $user_role = 'guest';
            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $roles = ( array ) $user->roles;
                $user_role = $roles[0];
            }

            foreach( $products as $key => $product ) {

                $visible_roles = get_post_meta( $product['parent_id'], '_alg_wc_pvbur_visible', true );
                $invisible_roles = get_post_meta( $product['parent_id'], '_alg_wc_pvbur_invisible', true );

                if ( is_array( $invisible_roles ) && ! empty( $invisible_roles ) ) {
                    foreach( $invisible_roles as $invisible_role ) {
                        if ( $user_role == $invisible_role ) {
                            unset( $products[$key] );
                            continue 2;
                        }
                    }
                }

                if ( is_array( $visible_roles ) && ! empty( $visible_roles ) ) {
                    $show = false;
                    foreach( $visible_roles as $visible_role ) {
                        if ( $user_role == $visible_role ) {
                            $show = true;
                            break;
                        }
                    }
                    if ( ! $show ) {
                        unset( $products[$key] );
                        continue;
                    }
                }

            }

            return $products;

        }

    }

endif;