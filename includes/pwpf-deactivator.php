<?php

/**
 * Fired during plugin deactivation
 *
 * @link       #
 * @since      1.2
 *
 * @package    private_wordpress_files
 * @subpackage private_wordpress_files/includes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class PWPF_Deactivator { 

	/*
     * Deactivate the plugin
     */
	public static function deactivate() {
		// Remove the admin nginx option
		delete_option( 'dismissed-private_media_nginx_message');
		// Delete the option
		delete_option( 'Activated_Protect_Wordpress_Files' );
		// Detele capabilities
		self::delete_caps();
	}

	/*
     * Delete custom capabilities
     */
    public static function delete_caps(){
		// Get role
        $administrator_role = get_role( 'administrator' );
        // Adding a new capability to role administrator
        $administrator_role->remove_cap( 'manage_pwpf_files' );
	}

}