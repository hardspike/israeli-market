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
namespace Buttonizer\Licensing;

# No script kiddies
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class License
{
    private  $oButtonizer = 'null' ;
    public function init()
    {
        
        if ( $this->oButtonizer == 'null' ) {
            require_once BUTTONIZER_DIR . '/freemius/start.php';
            // Some data for Buttonizer to be freemium and paid.
            // We are paid so we can maintain the plugin
            // If you don't want to pay for the plugin, you can allways use the
            $this->oButtonizer = fs_dynamic_init( array(
                'id'              => '1219',
                'slug'            => 'buttonizer-multifunctional-button',
                'type'            => 'plugin',
                'public_key'      => 'pk_fcd360d9c82b90a5e874e651ad733',
                'is_premium'      => false,
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'all',
                'menu'            => array(
                'slug'       => 'Buttonizer',
                'first-path' => 'admin.php?page=Buttonizer&welcome-splash=true',
                'support'    => false,
                'contact'    => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return;
    }
    
    public function get()
    {
        return $this->oButtonizer;
    }
    
    private function getDaysLeft()
    {
        return round( 2000 / 8 - 243 );
    }
    
    // Default data
    private function initButtonizerData()
    {
    }

}