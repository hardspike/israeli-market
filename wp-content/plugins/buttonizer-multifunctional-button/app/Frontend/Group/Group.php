<?php

namespace Buttonizer\Frontend\Group;

use  Buttonizer\Frontend\Group\Button\Button ;
use  Buttonizer\Frontend\Buttonizer ;
class Group
{
    private  $buttons = array() ;
    private  $data ;
    private  $totalButtons = 0 ;
    private  $countMobile = 0 ;
    private  $countDesktop = 0 ;
    /**
     * Buttons constructor.
     * @param $data
     */
    public function __construct( $data )
    {
        $this->data = $data;
    }
    
    /**
     * Return option data
     * 
     * @param $key
     * @param $default null
     * @return string
     */
    private function getOption( $key, $default = '' )
    {
        return ( isset( $this->data[$key] ) ? $this->data[$key] : $default );
    }
    
    /**
     * Return option data as number
     * 
     * @param $key
     * @param $default null
     * @return string
     */
    private function getNumber( $key, $default = 0 )
    {
        return ( isset( $this->data[$key] ) && is_numeric( $this->data[$key] ) && $this->data[$key] >= 0 ? $this->data[$key] : $default );
    }
    
    /**
     * Return option data as boolean
     * 
     * @param $key
     * @param $default false
     * @return boolean
     */
    public function getBoolean( $key, $default = false )
    {
        return ( isset( $this->data[$key] ) ? filter_var( $this->data[$key], FILTER_VALIDATE_BOOLEAN, [
            'options' => [
            'default' => false,
        ],
        ] ) === true : $default );
    }
    
    public function getId()
    {
        return $this->getOption( "id", null );
    }
    
    /**
     * Add button
     *
     * @param Button $button
     */
    public function add( Button $button )
    {
        $this->totalButtons++;
        // Show button (on page OR only when opened
        
        if ( $button->showButton() ) {
            if ( ($button->getBoolean( 'show_desktop' ) || $button->getBoolean( 'show_mobile' )) && ($this->countDesktop >= 7 || $this->countMobile >= 7) ) {
                // Is desktop, but no place on desktop? Force hide on desktop
                
                if ( $button->getBoolean( 'show_desktop' ) && $this->countDesktop >= 7 && $this->countMobile < 7 && $button->getBoolean( 'show_mobile' ) ) {
                    $button->setOption( "show_desktop", false );
                } else {
                    
                    if ( $button->getBoolean( 'show_mobile' ) && $this->countMobile >= 7 && $this->countDesktop < 7 && $button->getBoolean( 'show_desktop' ) ) {
                        $button->setOption( "show_mobile", false );
                    } else {
                        return;
                    }
                
                }
            
            }
            // Add mobile
            if ( $button->getBoolean( 'show_mobile' ) ) {
                $this->countMobile++;
            }
            // Add desktop
            if ( $button->getBoolean( 'show_desktop' ) ) {
                $this->countDesktop++;
            }
            $this->buttons[] = $button->generate();
        }
    
    }
    
    /**
     * Show group?
     *
     * @return bool
     */
    public function show()
    {
        // Only one button? Ignore the group show/schedule/pagerule settings, just show the button.
        // Otherwise you'll get confused why a button doesn't show if it isn't a group
        if ( $this->totalButtons === 1 && !($this->countDesktop === 0 && $this->countMobile === 0) ) {
            return true;
        }
        // Hide on all devices
        
        if ( !$this->getBoolean( 'show_desktop' ) && !$this->getBoolean( 'show_mobile' ) && !$this->getBoolean( 'single_button_mode' ) ) {
            Buttonizer::addEvent( [
                "id"          => $this->getOption( 'id', null ),
                "name"        => $this->getOption( 'name', "Unnamed" ),
                "button_type" => "group",
                "message"     => __( 'The group is hidden on all devices', 'buttonizer-multifunctional-button' ),
                "type"        => "all_devices_hidden",
            ] );
            return;
        }
        
        return ( count( $this->buttons ) > 0 ? true : false );
    }
    
    /**
     * Output
     */
    public function fix()
    {
        $data = [
            'name'            => $this->getOption( 'name', "Unnamed group" ),
            '_id'             => $this->getId(),
            'position'        => [
            'vertical'   => $this->getOption( 'vertical', BUTTONIZER_DEF_POSITION_VERTICAL ),
            'horizontal' => $this->getOption( 'horizontal', BUTTONIZER_DEF_POSITION_HORIZONTAL ),
        ],
            'styling'         => [
            'menu'   => [
            'style'     => $this->getOption( 'menu_style', BUTTONIZER_DEF_MENU_STYLE ),
            'animation' => $this->getOption( 'menu_animation', BUTTONIZER_DEF_MENU_ANIMATION ),
        ],
            'button' => [
            'color'       => $this->getOption( 'background_color', BUTTONIZER_DEF_BACKGROUND_COLOR ),
            'interaction' => $this->getOption( 'background_color_interaction', BUTTONIZER_DEF_BACKGROUND_COLOR_INTERACTION ),
            'radius'      => $this->getOption( 'border_radius', BUTTONIZER_DEF_BORDER_RADIUS ),
        ],
            'border' => [
            'width'       => $this->getNumber( 'border_size', 0 ),
            'color'       => $this->getOption( 'border_color', '' ),
            'interaction' => $this->getOption( 'border_color_interaction', '' ),
        ],
            'icon'   => [
            'color'       => $this->getOption( 'icon_color', '' ),
            'interaction' => $this->getOption( 'icon_color_interaction', '' ),
            'size'        => $this->getNumber( 'icon_size', BUTTONIZER_DEF_GROUP_ICON_SIZE ) . "px",
        ],
            'label'  => [
            'text'                   => $this->getOption( 'label_color', BUTTONIZER_DEF_LABEL_COLOR_TEXT ),
            'text_interaction'       => $this->getOption( 'label_color_interaction', BUTTONIZER_DEF_LABEL_COLOR_TEXT ),
            'background'             => $this->getOption( 'label_background_color', BUTTONIZER_DEF_LABEL_COLOR_BACKGROUND ),
            'background_interaction' => $this->getOption( 'label_background_color_interaction', BUTTONIZER_DEF_LABEL_COLOR_BACKGROUND ),
            'size'                   => $this->getNumber( 'label_font_size', BUTTONIZER_DEF_LABEL_FONT_SIZE ) . "px",
            'radius'                 => $this->getOption( 'label_border_radius', BUTTONIZER_DEF_LABEL_BORDER_RADIUS ),
        ],
        ],
            'icon'            => [
            'groupIcon' => $this->getOption( 'icon', BUTTONIZER_DEF_GROUP_ICON ),
        ],
            'label'           => [
            'groupLabel'         => $this->getOption( 'label', '' ),
            'show_label_desktop' => $this->getOption( 'show_label_desktop', BUTTONIZER_DEF_LABEL_VISIBILITY ),
            'show_label_mobile'  => $this->getOption( 'show_label_mobile', BUTTONIZER_DEF_LABEL_VISIBILITY ),
        ],
            'device'          => [
            'show_mobile'  => $this->getBoolean( 'show_mobile', BUTTONIZER_DEF_MOBILE_VISIBILITY ),
            'show_desktop' => $this->getBoolean( 'show_desktop', BUTTONIZER_DEF_DESKTOP_VISIBILITY ),
        ],
            'openedByDefault' => $this->getBoolean( 'start_opened', BUTTONIZER_DEF_START_OPENED ),
            'buttons'         => $this->buttons,
        ];
        return $data;
    }

}