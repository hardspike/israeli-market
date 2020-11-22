<?php
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2017 Buttonizer
*/
namespace Buttonizer\Utils;

# No script kiddies
defined( 'ABSPATH' ) or die('No script kiddies please!');

class Maintain {

    // Construct
    public function __construct($ready = false) {
        if(!$ready) return;

        register_activation_hook('buttonizer', array(&$this, 'pluginActivate'));
        register_deactivation_hook('buttonizer', array(&$this, 'pluginDeactivate'));

        add_action('upgrader_process_complete', array(&$this, 'pluginUpdated'), 10, 2);

        add_action( 'admin_bar_menu', array(&$this, 'wordpress_admin_bar'), 100);
    }

    /**
    * Activate Buttonizer, AWESOMAAH!
    */
    public function pluginActivate() {
        // Check updated data
        $this->pluginUpdated();
    }

    /**
    * Deactivate plugin, SEE YOU SOON!
    */
    public function pluginDeactivate(){
        // Nothing to handle right now. Maybe later
    }

    /**
     * Buttonizer settings
     *
     * @return array
     */
    private function getSettings() {
        register_setting("buttonizer", "buttonizer_settings");
        return get_option('buttonizer_settings');
    }

    /**
     * Updated?
     */
    public function pluginUpdated()
    {
        $settings = $this->getSettings();

        if(!isset($settings['migration_version']))
        {
            (new Update())->run();
        }
        else if ($settings['migration_version'] === "2.0") {
            (new Update())->update20to21();
        }
        else if ($settings['migration_version'] === "2.1") {
            (new Update())->update21to22();
        }
    }

    /**
     * Add Buttonizer to the admin bar
     * @param $admin_bar
     */
    public function wordpress_admin_bar($admin_bar)
    {
        // Only show to admins and when enabled
        if(current_user_can('editor') || current_user_can('administrator')) {
            $settings = $this->getSettings();

            if(!isset($settings['admin_top_bar_show_button']) || (isset($settings['admin_top_bar_show_button']) && filter_var($settings['admin_top_bar_show_button'], FILTER_VALIDATE_BOOLEAN, ['options' => ['default' => false]]) === true)) {
                $admin_bar->add_menu(array(
                    'id' => 'buttonizer',
                    'title' => '<img src="' . plugins_url('/assets/images/wp-icon.png', BUTTONIZER_PLUGIN_DIR) . '" style="vertical-align: text-bottom; opacity: 0.7; display: inline-block;" />',
                    'href' => admin_url() . 'admin.php?page=Buttonizer', // (!is_admin() ? '#' . urlencode($_SERVER["REQUEST_URI"]) : '')
                    'meta' => [],
                ));

                // Buttonizer buttons
                $admin_bar->add_menu(array(
                    'id' => 'buttonizer_buttons',
                    'parent' => 'buttonizer',
                    'title' => __('Manage buttons', 'buttonizer-multifunctional-button'),
                    'href' => admin_url() . 'admin.php?page=Buttonizer', // (!is_admin() ? '#' . urlencode($_SERVER["REQUEST_URI"]) : '')
                    'meta' => array(),
                ));

                // Settings
                $admin_bar->add_menu(array(
                    'id' => 'buttonizer_settings',
                    'parent' => 'buttonizer',
                    'title' => __('Settings', 'buttonizer-multifunctional-button'),
                    'href' => admin_url() . 'admin.php?page=Buttonizer#/settings/preferences',
                    'meta' => array(),
                ));

                // Settings
                $admin_bar->add_menu(array(
                    'id' => 'buttonizer_knowledgebase',
                    'parent' => 'buttonizer',
                    'title' => __('Knowledge base', 'buttonizer-multifunctional-button'),
                    'href' => "https://community.buttonizer.pro/knowledgebase",
                    'meta' => [
                        "target" => "_blank",
                        "title" => __('Find out everything you need to know about Buttonizer', 'buttonizer-multifunctional-button')
                    ],
                ));
            }
        }
    }

    /**
     * Get WordPress timezone
     */
    public static function getTimezone()
    {
        $timezone_string = get_option( 'timezone_string' );

        if (!empty( $timezone_string ) ) {
            return $timezone_string;
        }

        $offset  = get_option( 'gmt_offset' );
        $hours   = (int) $offset;
        $minutes = ( $offset - floor( $offset ) ) * 60;
        $offset  = sprintf( '%+03d:%02d', $hours, $minutes );

        return $offset;
    }
}
