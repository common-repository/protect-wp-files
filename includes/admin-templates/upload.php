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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

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
    <h1><?php echo __('Upload file(s)', 'protect-wordpress-files'); ?></h1>
    
    <div class="ajax-holder" style="position: relative;">

        <form class="private-media" id="private-media-upload" action="" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="file" class="inputfile" id="input_file" />
            <label for="file" class="private_upload">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                    <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
                <span><?php echo __('Choose a file&hellip;', 'protect-wordpress-files'); ?></span>
            </label>
            <br><br>
            <?php wp_nonce_field( 'fileToUpload', 'private_upload_media_nonce' ); ?>
            <div class="action">
                <input type="submit" class="btn-submit btn btn-success" value="<?php echo __('Submit', 'protect-wordpress-files'); ?>" disabled />
            </div>
        </form>

        <div class="progressbar clearfix" style="display: none">
            <div class="progressbar-bar" style="background: #2ecc71;"></div>
            <div class="progressbar-bar-percent">0</div>
        </div>

        <div class="progress-msg" style="display: none">
            <i class="fa fa-cog fa-spin" style="font-size:24px"></i> <span><?php echo __('The file is uploading', 'protect-wordpress-files'); ?></span>
        </div> 

        <div class="uploaded_link" style="display: none;">
            <button class="copy-linkage btn" style="vertical-align:top;"><?php echo __('Copy','protect-wordpress-files'); ?></button>
            <input type="text" class="linkage" value=""/>
            <div class="copy_message" style="display: none;">
                <?php echo __('The url has been copied to your clipboard!','protect-wordpress-files'); ?>
            </div>
            <br><br>
            <a href="<?php echo admin_url( 'admin.php?page=protect-wordpress-files' ); ?>" class="btn btn-success" title="<?php echo __('Upload new file','protect-wordpress-files'); ?>"><?php echo __('Upload new file','protect-wordpress-files'); ?></a>
        </div>

    </div>

    <div class="short_message">
        <p><?php echo sprintf(__('After upload you can find the files <a href="%s" target="_blank">here</a>','protect-wordpress-files'), admin_url('/upload.php')); ?></p>
    </div>

</div>
<?php
    wp_enqueue_style('protect-wordpress-files-main-css');
    wp_enqueue_script('protect-wordpress-files-admin-js');
?>