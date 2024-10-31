<?php
/**
 * Provide a wizard view for the plugin
 *
 *
 * @link       #
 * @since      1.0.1
 *
 * @package    private_wordpress_files
 * @subpackage private_wordpress_files/includes/admin-templates
 */

// check if wordpress is loaded
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$user_id    = get_current_user_id();
$the_user   = get_userdata($user_id);
$dashboard_url = admin_url( sprintf('/admin.php?page=%s', self::START_IDENTIFIER) );
$nicename = $the_user->user_nicename;

?>
<div class="protect_wordpress_files_wizard">
    <div class="container top">
        <div class="row">
            <nav class="navbar navbar-expand-md  w-100">
                <a class="navbar-brand" href="https://www.mauwen.com" target="_blank">
                    <img src="<?php echo PWPF_ASSETS_DIR . 'images/logo-cs.png'; ?>" alt="logo" width="128px"
                        height="auto">
                </a>
                <a class="button return-link" href="<?php echo esc_url( $dashboard_url ); ?>">
                    <span aria-hidden="true" class="dashicons dashicons-no"></span>
                    <?php
                        echo __( 'Close the introduction', 'protect-wordpress-files' );
                    ?>
                </a>
            </nav>
        </div>
    </div>
    <div class="container entry-section">
        <div class="row">
            <div class="col-md-6 pt-4 pb-5 mobile-white mobile-white-border mt-0 mt-md-4">
                <div class="row text-left text-md-left">
                    <div class="col-md-12 pl-md-3 ml-md-3 ml-lg-0">
                        <h1 class="ins-txt-shadow font-20-mobile">
                            <?php echo __('Protect WordPress Uploads','protect-wordpress-files'); ?>
                        </h1>
                    </div>
                </div>
                <!-- row END -->
                <div class="row text-left text-md-left mt-3">
                    <div class="col-md-12 pl-md-3 ml-md-3 ml-lg-0 color697B90-08 ">
                        <h3>
                            <?php echo sprintf(__('Hi %s! Thank you for downloading our plugin.','protect-wordpress-files'), $nicename); ?>
                        </h3>
                        <br>
                        <h5>
                            <?php echo __('Did you know? Any file uploaded to your WordPress website is not protected.','protect-wordpress-files'); ?>
                        </h5>
                        <p class="pr-5 ins-txt-shadow">
                            <?php echo __('With Protect WordPress Uploads you can upload files safely and share files with other registered users on your website and community.','protect-wordpress-files'); ?>
                        </p>
                        <p class="pr-5 ins-txt-shadow">
                            <?php echo __('Seamlessly integrated, you can easily protect your WordPress Uploads by just one single click. Once protected, files cannot be accessed directly through their original, unprotected links (URLs) and unwanted users will be redirected.','protect-wordpress-files'); ?>
                        </p>
                    </div>
                </div>
                <div class="row text-left text-md-left mt-3 d-md-flex">
                    <div class="col-md-12 pl-md-3 ml-md-3 ml-lg-0 text-center text-lg-left">
                        <a href="<?php echo esc_url( $dashboard_url ); ?>"
                            title="<?php echo __('Get started','protect-wordpress-files'); ?>"
                            class="btn btn-success btn-gradient px-4 py-2 mr-3 mb-4"><?php echo __('Get Started','protect-wordpress-files'); ?></a>

                        <a href="<?php echo PWPF_DOCS; ?>"
                            title="<?php echo __('Need support?','protect-wordpress-files'); ?>"
                            class="btn btn-gradient px-4 py-2 mr-3 mb-4 color434F5E" target="_blank"><?php echo __('Need support?','protect-wordpress-files'); ?></a>    
                    </div>
                </div>
                <!-- row END -->
            </div>
            <div class="col-md-6 pt-4 mt-4">
                <img class="img-fluid pc-home-widget"
                    src="<?php echo PWPF_ASSETS_DIR . 'images/home-widget-2.png'; ?>">
            </div>
        </div>
        <!-- row END -->
    </div>
    <div class="container entry-section">
        <div class="row">
            <div class="col-md-3 pt-3 mt-3">
                <h4><?php echo __('Upload','protect-wordpress-files'); ?></h4>
                <p><?php echo __('Upload, protect and unprotect WordPress uploads.','protect-wordpress-files'); ?></p>
                <img class="img-fluid pc-home-widget" src="<?php echo PWPF_ASSETS_DIR . 'images/pwpf-image-1.jpg'; ?>">
            </div>
            <div class="col-md-3 pt-3 mt-3">
                <h4><?php echo __('Protected link','protect-wordpress-files'); ?></h4>
                <p><?php echo __('Copy the protected link anywhere on your WordPress website.','protect-wordpress-files'); ?></p>
                <img class="img-fluid pc-home-widget" src="<?php echo PWPF_ASSETS_DIR . 'images/pwpf-image-2.jpg'; ?>">
            </div>
            <div class="col-md-3 pt-3 mt-3">
                <h4><?php echo __('Filter','protect-wordpress-files'); ?></h4>
                <p><?php echo __('Go to the Media Library and filter by protected WordPress uploads.','protect-wordpress-files'); ?></p>
                <img class="img-fluid pc-home-widget" src="<?php echo PWPF_ASSETS_DIR . 'images/pwpf-image-3.jpg'; ?>">
            </div>
            <div class="col-md-3 pt-3 mt-3">
                <h4><?php echo __('Logged-in users','protect-wordpress-files'); ?></h4>
                <p><?php echo __('Protected uploads are only available for logged-in users.','protect-wordpress-files'); ?></p>
                <img class="img-fluid pc-home-widget" src="<?php echo PWPF_ASSETS_DIR . 'images/pwpf-image-4.jpg'; ?>">
            </div>
        </div>
        <!-- row END -->
    </div>
</div>
<?php
    wp_enqueue_style( 'protect-wordpress-files-main-css' );
    wp_enqueue_style( 'protect-wordpress-files-bootstrap-css' );
?>