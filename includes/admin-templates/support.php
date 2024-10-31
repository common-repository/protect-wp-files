<?php
/**
 * Provide a admin area view for the plugin
 *
 *
 * @link       #
 * @since      1.0.2
 *
 * @package    private_wordpress_files
 * @subpackage private_wordpress_files/includes/admin-templates
 */

// check if wordpress is loaded
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<?php
$user_id        = get_current_user_id();
$the_user       = get_userdata($user_id);
$nicename       = $the_user->user_nicename;
?>
<div class="protect_wordpress_files_wizard">
    <div class="container top">
        <div class="row">
            <nav class="navbar navbar-expand-md  w-100">
                <a class="navbar-brand" href="https://www.mauwen.com" target="_blank">
                    <img src="<?php echo PWPF_ASSETS_DIR . 'images/logo-cs.png'; ?>" alt="logo" width="128px"
                        height="auto">
                </a>
            </nav>
        </div>
    </div>
</div>
<div class="protect_wordpress_files_upload">
    <div class="container entry-section">
        <div class="row">
            <div class="col-md-6 pt-4 pb-5 mobile-white mobile-white-border mt-0 mt-md-4">
                <div class="row text-left text-md-left">
                    <div class="col-md-12 pl-md-3 ml-md-3 ml-lg-0">
                        <h2 class="ins-txt-shadow font-20-mobile">
                            <?php echo sprintf(__('Hi %s, we are here to help!','protect-wordpress-files'), $nicename); ?>
                        </h2>
                    </div>
                </div>
                <!-- row END -->
                <div class="row text-left text-md-left mt-3">
                    <div class="col-md-12 pl-md-3 ml-md-3 ml-lg-0 color697B90-08 ">
                        <p class="pr-5 ins-txt-shadow">
                            <?php echo __("Check out the documentation, if you can't find your answer drop us an email.",'protect-wordpress-files'); ?>
                        </p>
                    </div>
                    <div class="col-md-12 pl-md-3 ml-md-3 ml-lg-0 text-center text-lg-left">
                        <a href="<?php echo PWPF_DOCS; ?>"
                            title="<?php echo __('Send support ticket','protect-wordpress-files'); ?>"
                            class="btn btn-success btn-gradient px-4 py-2 mr-3 mb-4" target="_blank"><?php echo __('Documentation','protect-wordpress-files'); ?></a> 
                    </div>
                </div>
                <!-- row END -->
            </div>
        </div>
        <!-- row END -->
    </div>
</div>
<?php
    wp_enqueue_style('protect-wordpress-files-main-css');
?>