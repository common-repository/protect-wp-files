<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.2
 * @package    private_wordpress_files
 * @subpackage private_wordpress_files/includes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class PWPF_Activator { 

    /*
     * Activate the plugin
     */
	public function activate() {

        // Create private upload folder
        self::create_private_folder();

        // Add option
		add_option( 'Activated_Protect_Wordpress_Files', true );

	}

    /*
     * Add private folder
     */
    public function create_private_folder() {
        
        $upload_dir     = wp_upload_dir();
        $custom_dir     = PWPF_UPLOAD_DIR ? PWPF_UPLOAD_DIR : 'private';
        $private_dir    = $upload_dir['basedir'] . '/' . $custom_dir;
        
        // Check if the directory exists or create it
        if (!is_dir($private_dir)) {
            if(!mkdir($private_dir, 0777, true)){
                $message = __('We where unable to create a "private" folder in your wp-uploads/ folder. Create it manually or make sure your wp-uploads/ folder has 0777 directory rights.','protect-wordpress-files');
                new PWPF_Message($message, 'error', false);
            }
        }

        // Add .htaccess file to private folder
        self::create_htaccess_file();

    }

    /*
     * Add .htaccess file
     */
    public function create_htaccess_file() {

        $upload_dir     = wp_upload_dir();
        $custom_dir     = PWPF_UPLOAD_DIR ? PWPF_UPLOAD_DIR : 'private';
        $private_dir    = $upload_dir['basedir'] . '/' . $custom_dir;

        if(is_dir($private_dir)){

            if(!file_exists($private_dir.'/.htaccess'))
            {
                $content = 'Deny from all' . "\n";
                file_put_contents($private_dir.'/.htaccess', $content);

                if(!file_exists($private_dir.'/.htaccess')){
                    $message = __('We where unable to create a ".htaccess" file in the wp-uploads/private/ folder. Create it manually or make sure your wp-uploads/private/ folder has 0777 directory rights.','protect-wordpress-files');
                    new PWPF_Message($message, 'error', false);
                }

            }

        }

    }

}