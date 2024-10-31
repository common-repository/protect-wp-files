<?php

/**
 * 
 * WP Admin messages
 * 
 * @link       csorbamedia.com
 * @since      1.2
 *
 * @package    private_wordpress_files
 * @subpackage private_wordpress_files/includes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'PWPF_Message' ) ){

    class PWPF_Message {

        private $message;
        private $type;
        private $isdismissible;

        public function __construct( $message, $type, $isdismissible ) {

            $this->message         = $message;
            $this->type            = $type;
            $this->isdismissible   = $isdismissible;
            add_action( 'admin_notices', array( $this, 'show_private_message' ) );

        }

        public function show_private_message(){
            if(!empty($this->message) && !empty($this->type)){
                $dismissible = ($this->isdismissible == true) ? 'is-dismissible' : '';
                ?>
                <div class="private-media notice notice-<?php echo $this->type; ?> <?php echo $dismissible; ?>" data-notice="private_media_nginx_message">
                <p><?php echo $this->message; ?></p>
                </div>
            <?php
            }
        }

    }

}