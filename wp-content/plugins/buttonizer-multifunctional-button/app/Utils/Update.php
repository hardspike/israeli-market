<?php namespace Buttonizer\Utils;

/**
 * Class Update Buttonizer to version 2.0 (or more)
 *
 * This PHP class contains NO premium code
 *
 * Thank you for updating Buttonizer and keeping Buttonizer UP TO DATE!
 *
 * By the way, not many people will ever see this comment after all...
 * If you read this, email jasper@buttonizer.pro and say something nice referring to this comment!
 *
 * @package Buttonizer\Utils
 */
class Update
{
    private $initialized = false;
    private $removedAnything = false;
    private $backupSettings = [];
    private $backupButtons = [];
    private $backupSchedule = [];
    private $backupRules = [];
    private $timeScheduleId = null;

    public function __construct()
    {
        $this->timeScheduleId = time();
    }

    /**
     * Run update to 2.0
     */
    public function run()
    {
        try {
            $this->registerSettings();

            // If there's no Buttonizer data, new install
            if(!$this->backupButtons && !$this->backupSchedule && !$this->backupRules)
            {
                $this->cleanup(true);
                return; // all OK, new installation :)
            }

            $this->convertButtons();
            $this->convertTimeSchedules();
            if($this->backupRules) {
                $this->convertPageRules();
            }

            $this->cleanup();
        } catch (\Exception $e) {
            $this->beforeCrashRevert();

            header('Content-Type: application/javascript');

            echo json_encode([
                "status" => "error",
                "message" => "Something went wrong while trying to migrate settings from version 1.5 to version 2.0. Please contact Buttonizer Support or go to the <a href=\"https://community.buttonizer.pro/\" target=\"_blank\">Buttonizer Community</a> and let us know the following message: " . $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Register settings
     */
    private function registerSettings()
    {
        // Backup settings
        register_setting("buttonizer", "buttonizer_buttons_backup");
        register_setting("buttonizer", "buttonizer_opening_settings_backup");
        register_setting("buttonizer", "buttonizer_page_categories_backup");
        register_setting("buttonizer", "buttonizer_general_settings_backup");

        // Get buttons
        register_setting("buttonizer", "buttonizer_buttons");
        register_setting("buttonizer", "buttonizer_opening_settings");
        register_setting("buttonizer", "buttonizer_page_categories");

        // Buttonizer 2.0 setting groups
        register_setting("buttonizer", "buttonizer_buttons");
        register_setting("buttonizer", "buttonizer_buttons_published");
        register_setting("buttonizer", "buttonizer_settings");
        register_setting("buttonizer", "buttonizer_schedules");
        register_setting("buttonizer", "buttonizer_rules");

        // Load buttons
        $this->backupButtons = get_option('buttonizer_buttons');
        $this->backupSettings = get_option('buttonizer_general_settings');
        $this->backupSchedule = get_option('buttonizer_opening_settings');
        $this->backupRules = get_option('buttonizer_page_categories');

        $this->initialized = true;
    }

    /**
     * Woah! Something went wrong :'(
     */
    private function beforeCrashRevert() {
        // Check if anything got removed
        if(!$this->initialized || !$this->removedAnything) return;

        // Delete created options
        delete_option('buttonizer_general_settings_backup');
        delete_option("buttonizer_buttons_backup");
        delete_option("buttonizer_opening_settings_backup");
        delete_option("buttonizer_page_categories_backup");
        delete_option("buttonizer_general_settings_backup");


        // Make sure that the old values are still there
        update_option('buttonizer_general_settings', $this->backupSettings);
        update_option('buttonizer_buttons', $this->backupButtons);
        update_option('buttonizer_opening_settings', $this->backupSchedule);
        update_option('buttonizer_page_categories', $this->backupRules);
    }

    /**
     * Convert
     */
    private function convertButtons()
    {
        // Make backup
        update_option('buttonizer_buttons_backup', $this->backupButtons);

        $buttons = [];

        $defaultButtonColor = $this->get15GeneralSettings('button_unpushed', '#ba54ff');
        $defaultButtonColorHover = $this->get15GeneralSettings('button_pushed', '#ba54ff');

        foreach ($this->backupButtons['buttonorder'] as $buttonId)
        {
            // Skip the placeholder button
            if($buttonId == -1) continue;
            $hasStyleChanges = false;

            // Get action type and action
            $actionType = $this->get15buttonValue($buttonId, 'action', '');
            $action = $this->get15buttonValue($buttonId, 'url', '');

            // Make sure social sharing actions get migrated
            if($actionType == 'socialsharing') {
                $action = $this->get15buttonValue($buttonId, 'social', 'facebook');
            }else if($actionType == 'javascript') {
                $actionType = 'javascript_pro';
            }

            // Make sure label gets migrated correctly
            $showLabel = ($this->get15buttonValue($buttonId, 'show_label_on_hover', 'always') === 'default' ? 'always' : $this->get15buttonValue($buttonId, 'show_label_on_hover', 'always'));

            // Hover on desktop, show on mobile
            if($showLabel === 'showOnHover') {
                $showLabelDesktop = 'hover';
                $showLabelMobile = 'always';
            }
            // Hover on desktop, hide on mobile
            else if($showLabel === 'showOnHoverDesktop') {
                $showLabelDesktop = 'hover';
                $showLabelMobile = 'hide';
            }
            // Hide on desktop, show on mobile
            else if($showLabel === 'showOnHoverMobile') {
                $showLabelDesktop = 'hide';
                $showLabelMobile = 'always';
            }
            // Always show label
            else {
                $showLabelDesktop = 'always';
                $showLabelMobile = 'always';
            }

            $button = [
                // Name
                "name" => $this->get15buttonValue($buttonId, 'title', 'Button ' . count($buttons)),
                "icon" => 'fa ' . $this->get15buttonValue($buttonId, 'icon', 'fa-lightbulb'),

                // When do we show this button?
                "show_desktop" => $this->get15buttonValue($buttonId, 'show_on_desktop', 0) == 1 ? 'true' : 'false',
                "show_mobile" => $this->get15buttonValue($buttonId, 'show_on_phone', 0) == 1 ? 'true' : 'false',

                // Button actions
                "type" => $actionType,
                "action" => $action,
                "action_new_tab" => $this->get15buttonValue($buttonId, 'url_newtab', 0) == 1 ? 'true' : 'false',

                // Button label
                "label" => $this->get15buttonValue($buttonId, 'text', 'Button label'),
                "show_label_desktop" => $showLabelDesktop,
                "show_label_mobile" => $showLabelMobile
            ];

            // Custom colors
            if($this->get15buttonValue($buttonId, 'using_custom_colors', 0) == 1)
            {
                // Button background color
                if($this->get15buttonValue($buttonId, 'colors_button', '#ba54ff') !== '#ba54ff' && $this->get15buttonValue($buttonId, 'colors_button', '#ba54ff') !== $defaultButtonColor) {
                    $hasStyleChanges = true;
                    $button['background_color'] = $this->fixColor($this->get15buttonValue($buttonId, 'colors_button', '#ba54ff'));
                }

                // Button background hover color
                if($this->get15buttonValue($buttonId, 'colors_pushed', '#8d1acc') !== '#8d1acc' && $this->get15buttonValue($buttonId, 'colors_pushed', '#8d1acc') !== $defaultButtonColorHover) {
                    $hasStyleChanges = true;
                    $button['background_color_interaction'] = $this->fixColor($this->get15buttonValue($buttonId, 'colors_pushed', '#8d1acc'));
                }

                // Button icon color
                if($this->fixColor($this->get15buttonValue($buttonId, 'colors_icon', '#FFFFFF')) !== '#FFFFFF' && $this->get15buttonValue($buttonId, 'colors_icon', '#FFFFFF') != $this->get15GeneralSettings('icon_color', '#FFFFFF')) {
                    $hasStyleChanges = true;
                    $button['icon_color'] = $this->fixColor($this->get15buttonValue($buttonId, 'colors_icon', '#FFFFFF'));
                    $button['icon_color_interaction'] = $this->fixColor($this->get15buttonValue($buttonId, 'colors_icon', '#FFFFFF'));
                }
            }

            // Does it have a background image?
            if($this->get15buttonValue($buttonId, 'image', '') !== '') {
                if($this->get15buttonValue($buttonId, 'image_background', 0) == 1) {
                    $button['background_image'] = $this->get15buttonValue($buttonId, 'image', '');

                    // Empty icon
                    $button['icon'] = '';

                    $hasStyleChanges = true;
                }else {
                    // is icon!
                    $button['icon_is_image'] = 'true';
                    $button['icon_image'] = $this->get15buttonValue($buttonId, 'image', '');
                }
            }

            // Did it have a custom class
            if($this->get15buttonValue($buttonId, 'custom_class', '') !== '') {
                $button['custom_class'] = $this->get15buttonValue($buttonId, 'custom_class', '');
            }

            // Time schedule
            if($this->get15buttonValue($buttonId, 'show_when_opened', 0) !== 0) {
                $button['selected_schedule'] = $this->timeScheduleId;
            }

            // Page rule
            if($this->get15buttonValue($buttonId, 'show_on_pages', -1) >= 0) {
                $button['selected_page_rule'] = $this->get15buttonValue($buttonId, 'show_on_pages', '');
            }

            // Using main button style
            $button['use_main_button_style'] = $hasStyleChanges ? 'false' : 'true';

            // Add button
            $buttons[] = $button;
        }

        $menuStyle = $this->get15GeneralSettings('buttons_animation', 'default');

        // Update to new style name
        if($menuStyle === 'circle') {
            $menuStyle = 'corner-circle';
        }else if($menuStyle === 'fade-left-to-right') {
            $menuStyle = 'faded';
        }

        // Group label
        $showGroupLabel = $this->get15GeneralSettings('buttons_label_show_on_hover', 'always');

        // Hover on desktop, show on mobile
        if($showGroupLabel === 'showOnHover') {
            $showGroupLabelDesktop = 'hover';
            $showGroupLabelMobile = 'always';
        }
        // Hover on desktop, hide on mobile
        else if($showGroupLabel === 'showOnHoverDesktop') {
            $showGroupLabelDesktop = 'hover';
            $showGroupLabelMobile = 'hide';
        }
        // Hide on desktop, show on mobile
        else if($showGroupLabel === 'showOnHoverMobile') {
            $showGroupLabelDesktop = 'hide';
            $showGroupLabelMobile = 'always';
        }
        // Always show label
        else {
            $showGroupLabelDesktop = 'always';
            $showGroupLabelMobile = 'always';
        }

        $groups = [];
        $groups[] = [
            'data' => [
                'name' => 'Buttonizer',

                // Show on mobile and desktop
                'show_desktop' => 'true',
                'show_mobile' => 'true',

                // Icon
                'icon_is_image' => 'false',
                'icon' => 'fa ' . $this->get15GeneralSettings('icon_icon', 'fa-plus'),
                'icon_color' => $this->fixColor($this->get15GeneralSettings('icon_color', '#ffffff')),
                'icon_color_interaction' => $this->fixColor($this->get15GeneralSettings('icon_color', '#ffffff')),

                // Button background
                'background_color' => $defaultButtonColor,
                'background_color_interaction' => $defaultButtonColorHover,

                // Position
                'horizontal' => $this->get15GeneralSettings('position_horizontal', 5),
                'vertical' => $this->get15GeneralSettings('position_bottom', 5),
                'position' => (($this->get15GeneralSettings('position_horizontal', 5) != 5 || $this->get15GeneralSettings('position_vertical', 5) != 5) ? 'advanced' : 'bottomright'),

                // Menu style & animation
                'menu_style' => $menuStyle,
                'menu_animation' => $this->get15GeneralSettings('attention_animation', 'none'),

                // Label
                'label' => $this->get15GeneralSettings('icon_label', ''),
                'label_color' => $this->get15GeneralSettings('label_text_color', ''),
                'label_background_color' => $this->get15GeneralSettings('label_color', ''),

                // Show label
                'show_label_desktop' => $showGroupLabelDesktop,
                'show_label_mobile' => $showGroupLabelMobile,

                'advanced_scroll' => $this->get15GeneralSettings('procent_to_scroll', ''),
                'advanced_timeout' => $this->get15GeneralSettings('time_to_timeout', ''),
                'advanced_exit_intent' => $this->get15GeneralSettings('enable_exit_intent', '')
            ],

            'buttons' => $buttons
        ];

        // Save new buttons and publish them
        update_option('buttonizer_buttons', $groups);
        update_option('buttonizer_buttons_published', $groups);

        // Make backup from general settings and delete
        update_option('buttonizer_general_settings_backup', $this->backupSettings);
        delete_option('buttonizer_general_settings');
        $this->removedAnything = true;
    }

    private function fixColor($hex) {
        if(strtoupper($hex) === '#FFFFF') {
            return '#FFFFFF';
        }

        return $hex;
    }

    /**
     * Convert 'opening hours' to time schedules
     */
    private function convertTimeSchedules()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $newSchedule = [
            'name' => 'First time schedule',
            'id' => $this->timeScheduleId,
            'weekdays' => [],
            'custom' => [],
            'dates' => [],
        ];

        // Loop through all days
        foreach ($days as $day)
        {
            $opened = isset($this->backupSchedule['buttonizer_'. $day .'_opened']) && $this->backupSchedule['buttonizer_'. $day .'_opened'] == '1' ? 'true' : 'false';
            $openingFrom = isset($this->backupSchedule['buttonizer_'. $day .'_opened_from']) ? $this->backupSchedule['buttonizer_'. $day .'_opened_from'] : '10:00';
            $closingOn = isset($this->backupSchedule['buttonizer_'. $day .'_closing_on']) ? $this->backupSchedule['buttonizer_'. $day .'_closing_on'] : '17:00';

            $newSchedule['weekdays'][] = [
                'opened' => $opened,
                'open' => $openingFrom,
                'close' => $closingOn,
                'weekday' => $day
            ];
        }

        // Save time schedule
        update_option('buttonizer_schedules', [$newSchedule]);

        // Make backup and delete old one
        update_option('buttonizer_opening_settings_backup', $this->backupSchedule);
        delete_option('buttonizer_opening_settings');
    }

    /**
     * Convert page rules, not many changes though
     */
    private function convertPageRules()
    {
        $pageRules = [];

        // Full page rule
        foreach ($this->backupRules as $pageRule) {
            $container = [
                'id' => $pageRule['id'],
                'name' => $pageRule['title'],
                'filter_role' => $pageRule['filter_role'],
                'type' => $pageRule['andor'],
                'rules' => []
            ];

            $rules = [];
            foreach ($pageRule['rules'] as $rule)
            {
                $rules[] = [
                    'type' => $rule['type'],
                    'value' => $rule['value']
                ];
            }

            $container['rules'] = $rules;


            $pageRules[] = $container;
        }

        // Save page rule
        update_option('buttonizer_rules', $pageRules);
        update_option('buttonizer_rules_published', $pageRules);

        // Make backup and delete old one
        update_option('buttonizer_page_categories_backup', $this->backupRules);
        delete_option('buttonizer_page_categories');
    }

    /**
     * Let's get the old Buttonizer values
     *
     * @param $id
     * @param $option
     * @param string $default
     * @return mixed|string
     */
    private function get15buttonValue($id, $option, $default = '')
    {
        $optionName = 'button_'. $id .'_' . $option;

        // Option does not exists OR has no value
        if(!isset($this->backupButtons[$optionName])) return $default;

        return $this->backupButtons[$optionName];
    }

    /**
     * Let's get the old Buttonizer values
     *
     * @param $option
     * @param string $default
     * @return mixed|string
     */
    private function get15GeneralSettings($option, $default = '')
    {
        // Option does not exists OR has no value
        if(!isset($this->backupSettings[$option])) return $default;

        return $this->backupSettings[$option];
    }

    /**
     * Cleanup
     * @param bool $newInstall
     */
    private function cleanup($newInstall = false)
    {
        $settings = [];

        $settings['migration_version'] = '2.2';
        $settings['import_icon_library'] = true;

        if($newInstall === false) {
            $settings['google_analytics'] = $this->get15GeneralSettings('google_analytics', '');
            $settings['icon_library'] = 'fontawesome';
            $settings['icon_library_version'] = '4.7.0';
            $settings['welcome'] = true;
        }else{
            $settings['google_analytics'] = '';
            $settings['icon_library'] = 'fontawesome';
            $settings['icon_library_version'] = '5.free';
             // Add default button data
            update_option('buttonizer_buttons', [
                [
                    'data' => [
                        'name' => __('New group', 'buttonizer-multifunctional-button'), 
                        'show_mobile' => BUTTONIZER_DEF_MOBILE_VISIBILITY, 
                        'show_desktop' => BUTTONIZER_DEF_DESKTOP_VISIBILITY,
                    ],
                    'buttons' => [
                        [
                            'name' => __('New button', 'buttonizer-multifunctional-button'), 
                            'show_mobile' => BUTTONIZER_DEF_MOBILE_VISIBILITY, 
                            'show_desktop' => BUTTONIZER_DEF_DESKTOP_VISIBILITY
                        ]
                    ]
                ]
            ]);
        }

        update_option('buttonizer_settings', $settings);
    }

    /**
     * Run update 2.0 to 2.1
     */
    public function update20to21()
    {
        $this->registerSettings();

        $groups = $this->convertButtons20to21($this->backupButtons);
        $settings = get_option('buttonizer_settings');

        //backup 2.0.x settings
        update_option('buttonizer_buttons_backup_20', $this->backupButtons);
        update_option('buttonizer_buttons_published_20', get_option('buttonizer_buttons_published'));
        update_option('buttonizer_has_changes_20', get_option('buttonizer_has_changes'));
        update_option('buttonizer_rules_20', get_option('buttonizer_rules'));
        update_option('buttonizer_rules_published_20', get_option('buttonizer_rules_published'));
        update_option('buttonizer_schedules_20', get_option('buttonizer_schedules'));
        update_option('buttonizer_schedules_published_20', get_option('buttonizer_schedules_published'));
        update_option('buttonizer_settings_20', get_option('buttonizer_settings'));

        // Set migration version to 2.1
        $settings["migration_version"] = "2.1";
        update_option('buttonizer_settings', $settings);

        // Overwrite new settings
        update_option('buttonizer_buttons', $groups);
        
        // If buttonizer was already published, update published.
        if(get_option('buttonizer_buttons_published')) {
            $published = $this->convertButtons20to21(get_option('buttonizer_buttons_published'));
            update_option('buttonizer_buttons_published', $published);
        }

        // If buttonizer has timeschedules, convert booleans.
        if(get_option('buttonizer_schedules')) {
            $schedules =$this->convertSchedules20to21(get_option('buttonizer_schedules'));
            update_option('buttonizer_schedules', $schedules);
        }

        // If buttonizer has timeschedules published, convert booleans.
        if(get_option('buttonizer_schedules_published')) {
            $schedules_published =$this->convertSchedules20to21(get_option('buttonizer_schedules_published'));
            update_option('buttonizer_schedules_published', $schedules_published);
        }
    }

    /**
     * Return converted 2.0 schedule settings to 2.1
     */
    private function convertSchedules20to21($array) { 
        $schedules = [];

        foreach($array as $scheduleID) {
            $scheduleID['end_date'] = date( "d-m-Y", strtotime( $scheduleID['end_date'] ) );
            $scheduleID['start_date'] = date( "d-m-Y", strtotime( $scheduleID['start_date'] ) );

            $weekdays = [];
            foreach($scheduleID["weekdays"] as $days) {
                $days['opened'] = filter_var($days['opened'], FILTER_VALIDATE_BOOLEAN);

                $weekdays[] = $days;
            }  

            $dates = [];
            foreach($scheduleID["dates"] as $days) {
                $days['opened'] = filter_var($days['opened'], FILTER_VALIDATE_BOOLEAN);
                $days['date'] = date( "d-m-Y", strtotime( $days['date'] ) );

                $dates[] = $days;
            }  

            $scheduleID['weekdays'] = $weekdays;
            $scheduleID['dates'] = $dates;
            
            $schedules[] = $scheduleID;
        }

        return $schedules;
    }
    

    /**
     * Return converted 2.0 button settings to 2.1
     */
    private function convertButtons20to21($array) {
        $groups = [];

        foreach ($array as $group)
        {
            // // if it has a background image, set background_is_image to true
            if(!empty($group["data"]["background_image"])) {
                $group["data"]["background_is_image"] = true;
            }
            // If icon is image and image size is not set, max width
            if(!empty($group["data"]["icon_image"]) && empty($group["data"]["icon_image_size"])) {
                $group["data"]["icon_image_size"] = "100";
                $group["data"]["icon_size"] = "100";
            }

            $buttons = [];

            foreach($group["buttons"] as $button) {
                // // if it has a background image, set background_is_image to true
                if(!empty($button["background_image"])) {
                    $button["background_is_image"] = true;
                }
                // If icon is image and image size is not set, max width
                if(!empty($button["icon_image"]) && empty($button["icon_image_size"])) {
                    $button["icon_image_size"] = "100";
                    $button["icon_size"] = "100";
                }
                // change all true values to boolean true
                foreach(array_keys($button, "true", true) as $keys) {
                    $button[$keys] = true;
                }
                // change all false values to boolean false
                foreach(array_keys($button, "false", true) as $keys) {
                    $button[$keys] = false;
                }

                $buttons[] = $button;
            }

            // change all true values to boolean true
            foreach(array_keys($group["data"], "true", true) as $keys) {
                $group["data"][$keys] = true;
            }
            // change all false values to boolean false
            foreach(array_keys($group["data"], "false", true) as $keys) {
                $group["data"][$keys] = false;
            }

            // Since we change how enabling of Exit Intent works on 2.1. Disable if exit intent is disabled
            if($group["data"]["exit_intent"] === false) {
                $group["data"]["exit_intent_trigger_leaving_window"] = false;
                $group["data"]["exit_intent_trigger_inactive"] = false;
            }

            $group["buttons"] = $buttons;

            $groups[] = $group;
        }

        return $groups;
    }

    /**
     * Run update 2.1 to 2.2
     */
    public function update21to22()
    {
        $this->registerSettings();

        $groups = $this->convertButtons21to22($this->backupButtons);

        // If buttonizer was already published, update published.
        if(get_option('buttonizer_buttons_published')) {
            $published = $this->convertButtons21to22(get_option('buttonizer_buttons_published'));
            update_option('buttonizer_buttons_published', $published);
        }

        // Get all current settings first
        $settings = get_option('buttonizer_settings');
        // Set migration version to 2.2
        $settings["migration_version"] = "2.2";
        update_option('buttonizer_settings', $settings);

        // Overwrite new settings
        update_option('buttonizer_buttons', $groups);
    }

    /**
     * Return converted 2.0 button settings to 2.1
     */
    private function convertButtons21to22($array) {
        $groups = [];

        foreach ($array as $group)
        {
            // Convert position parameters
            if(!empty($group["data"]["vertical"])) {
                if($group["data"]["vertical"] <= 50) $group["data"]["vertical"] = "bottom:" . $group['data']['vertical'] . '%;';
                if($group["data"]["vertical"] > 50) $group["data"]["vertical"] = "top:" . (100 - $group['data']['vertical']) . '%;';
            }
            if(!empty($group["data"]["horizontal"])) {
                if($group["data"]["horizontal"] <= 50) $group["data"]["horizontal"] = "right:" . $group['data']['horizontal'] . '%;';
                if($group["data"]["horizontal"] > 50) $group["data"]["horizontal"] = "left:" . (100 - $group['data']['horizontal']) . '%;';
            }

            $groups[] = $group;
        }

        return $groups;
    }
}