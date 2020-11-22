<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
	exit();
} else{
	global $wpdb;
	if(get_option('ahcfree_wp_hits_counter_options') !== false){
		delete_option('ahcfree_wp_hits_counter_options');
	}
        $delete_plugin_data = get_option('ahcfree_delete_plugin_data_on_uninstall');
        if( $delete_plugin_data ){
            $sqlQueries = array();
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_hits`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_browsers`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_search_engines`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_search_engine_crawlers`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_countries`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_visitors`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_searching_visits`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_refering_sites`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_recent_visitors`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_keywords`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_title_traffic`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_visits_time`";
            $sqlQueries[] = "DROP TABLE IF EXISTS `ahc_settings`";

            foreach($sqlQueries as $sql){
                    $wpdb->query($sql);
            }
            
            delete_option('ahcfree_custom_timezone');
            delete_option('ahcfree_delete_plugin_data_on_uninstall');
        }
}
?>
