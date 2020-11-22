<?php

namespace Buttonizer\Frontend\Group\Button;

use  Buttonizer\Frontend\Buttonizer ;
class Button
{
    private  $groupObject ;
    private  $data ;
    //    private $styling = StylingObject;
    /**
     * Buttons constructor.
     * @param $groupObject
     * @param $data
     */
    public function __construct( $groupObject, $data )
    {
        $this->groupObject = $groupObject;
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
    
    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function setOption( $key, $value )
    {
        $this->data[$key] = $value;
        return true;
    }
    
    /**
     * Show buttons, based on their settings
     * @return bool
     */
    public function showButton()
    {
        // Button not in use
        
        if ( !$this->getBoolean( "show_desktop" ) && !$this->getBoolean( "show_mobile" ) ) {
            Buttonizer::addEvent( [
                "id"          => $this->getOption( 'id', null ),
                "group_id"    => $this->groupObject->getId(),
                "name"        => $this->getOption( 'name', "Unnamed" ),
                "button_type" => "button",
                "message"     => __( 'The button is hidden on all devices', 'buttonizer-multifunctional-button' ),
                "type"        => "all_devices_hidden",
            ] );
            return false;
        }
        
        return true;
    }
    
    /**
     * @return array
     */
    public function generate()
    {
        $data = [
            'name'      => $this->getOption( 'name', 'Unnamed button' ),
            '_id'       => $this->getOption( 'id', null ),
            '_group_id' => $this->groupObject->getId(),
            'action'    => [
            'type'           => $this->getOption( 'type', BUTTONIZER_DEF_BUTTON_ACTION ),
            'action'         => $this->getOption( 'action', '/' ),
            'action_new_tab' => $this->getBoolean( 'action_new_tab', BUTTONIZER_DEF_BUTTON_ACTION_NEW_TAB ),
        ],
            'icon'      => [
            'buttonIcon' => $this->getOption( 'icon', BUTTONIZER_DEF_BUTTON_ICON ),
        ],
            'device'    => [
            'show_mobile'  => $this->getBoolean( 'show_mobile', BUTTONIZER_DEF_MOBILE_VISIBILITY ),
            'show_desktop' => $this->getBoolean( 'show_desktop', BUTTONIZER_DEF_DESKTOP_VISIBILITY ),
        ],
            'label'     => [
            'label'              => $this->getOption( 'label', '' ),
            'show_label_desktop' => $this->getOption( 'show_label_desktop', BUTTONIZER_DEF_LABEL_VISIBILITY ),
            'show_label_mobile'  => $this->getOption( 'show_label_mobile', BUTTONIZER_DEF_LABEL_VISIBILITY ),
        ],
            'styling'   => [
            'icon'       => [
            'size' => $this->getNumber( 'icon_size', BUTTONIZER_DEF_BUTTON_ICON_SIZE ) . "px",
        ],
            'main_style' => $this->getBoolean( 'use_main_button_style', BUTTONIZER_DEF_USE_MAIN_BUTTON_STYLE ),
        ],
            'text'      => [
            'subject' => $this->getOption( 'text_subject', '' ),
            'body'    => $this->getOption( 'text_body', '' ),
        ],
        ];
        // Use own button styling
        
        if ( !$this->getBoolean( 'use_main_button_style', BUTTONIZER_DEF_USE_MAIN_BUTTON_STYLE ) ) {
            $data['styling'] = [
                'button' => [
                'color'       => $this->getOption( 'background_color', BUTTONIZER_DEF_BACKGROUND_COLOR ),
                'interaction' => $this->getOption( 'background_color_interaction', BUTTONIZER_DEF_BACKGROUND_COLOR_INTERACTION ),
                'radius'      => $this->getOption( 'border_radius', BUTTONIZER_DEF_BORDER_RADIUS ),
            ],
                'label'  => [
                'text'                   => $this->getOption( 'label_color', BUTTONIZER_DEF_LABEL_COLOR_TEXT ),
                'text_interaction'       => $this->getOption( 'label_color_interaction', BUTTONIZER_DEF_LABEL_COLOR_TEXT ),
                'background'             => $this->getOption( 'label_background_color', BUTTONIZER_DEF_LABEL_COLOR_BACKGROUND ),
                'background_interaction' => $this->getOption( 'label_background_color_interaction', BUTTONIZER_DEF_LABEL_COLOR_BACKGROUND ),
                'size'                   => $this->getOption( 'label_font_size', BUTTONIZER_DEF_LABEL_FONT_SIZE ) . 'px',
                'radius'                 => $this->getOption( 'label_border_radius', BUTTONIZER_DEF_LABEL_BORDER_RADIUS ),
            ],
                'icon'   => [
                'color'       => $this->getOption( 'icon_color', BUTTONIZER_DEF_ICON_COLOR ),
                'interaction' => $this->getOption( 'icon_color_interaction', BUTTONIZER_DEF_ICON_COLOR ),
                'size'        => $this->getNumber( 'icon_size', BUTTONIZER_DEF_BUTTON_ICON_SIZE ) . "px",
            ],
            ];
            $data['styling']['border'] = [
                'width'       => $this->getNumber( 'border_size', 0 ),
                'color'       => $this->getOption( 'border_color', '' ),
                'interaction' => $this->getOption( 'border_color_interaction', '' ),
            ];
        }
        
        return $data;
    }

}