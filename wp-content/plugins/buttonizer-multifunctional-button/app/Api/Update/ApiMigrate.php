<?php
namespace Buttonizer\Api\Update;

use Buttonizer\Utils\Update;
use Buttonizer\Utils\Remigrate;

/**
 * Reset API
 * 
 * @endpoint /wp-json/buttonizer/update/migrate
 * @endpoint /wp-json/buttonizer/update/remigrate
 * @methods POST
 */
class ApiMigrate {
    /**
     * Register route
     */
    public function registerRoute() {
        // Register migrate
        register_rest_route('buttonizer', '/update/migrate', [
            [
                'methods'  => ['POST'],
                'args' => [
                    'nonce' => [
                        'validate_callback' => function($value) {
                            return wp_verify_nonce($value, 'wp_rest');
                        },        
                        'required' => true
                    ],        
                ],
                'callback' => [$this, 'migrate'],
                'permission_callback' => function() {
                    return current_user_can('edit_posts') && wp_get_current_user()->ID !== 0;
                }
            ]
        ]);

        // Register remigrate
        register_rest_route('buttonizer', '/update/remigrate', [
            [
                'methods'  => ['POST'],
                'args' => [
                    'nonce' => [
                        'validate_callback' => function($value) {
                            return wp_verify_nonce($value, 'wp_rest');
                        },        
                        'required' => true
                    ],        
                ],
                'callback' => [$this, 'remigrate'],
                'permission_callback' => function() {
                    return current_user_can('edit_posts') && wp_get_current_user()->ID !== 0;
                }
            ]
        ]);
    }

    /**
     * Migrate buttons
     */
    public function migrate() {
        register_setting('buttonizer', 'buttonizer_buttons');

        $buttons = get_option('buttonizer_buttons');

        // Nothing to convert...
        if(!isset($buttons['buttonorder'])) {
            return new \WP_Error('migration_nothing_to_convert', 'Nothing to convert.', [ 
                'status' => 404 
            ]);
        }

        (new Update())->run();

        return [
            'status' => 'success'
        ];
    }

    /**
     * Remigrate buttons
     */
    public function remigrate() {
        register_setting('buttonizer', 'buttonizer_buttons_backup');

        $buttons = get_option('buttonizer_buttons_backup');

        if(!isset($buttons['buttonorder'])) {
            return new \WP_Error('remigration_nothing_to_convert', 'Nothing to convert.', [ 
                'status' => 404 
            ]);
        }

        (new Remigrate())->run();

        return [
            'status' => 'success'
        ];
    }
}