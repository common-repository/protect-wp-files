<?php
/**
 * Provide a admin area view for the plugin
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('manage_options') && isset($_POST['pwpf-settings-nonce']) && wp_verify_nonce($_POST['pwpf-settings-nonce'], 'pwpf-save-settings')) {
    if(isset($_POST['PWPF_access_denied_message'])) update_option('PWPF_access_denied_message', sanitize_text_field($_POST['PWPF_access_denied_message']));
    if(isset($_POST['PWPF_access_denied_link'])) update_option('PWPF_access_denied_link', sanitize_text_field($_POST['PWPF_access_denied_link']));
    if(isset($_POST['PWPF_access_denied_link_target'])) update_option('PWPF_access_denied_link_target', sanitize_text_field($_POST['PWPF_access_denied_link_target']));
}
$hooks                      = new PWPF_Hooks();
$access_denied_message      = !empty(get_option('PWPF_access_denied_message')) ? get_option('PWPF_access_denied_message') : __('You need to be loggedin to access this file.','protect-wordpress-files');
$access_denied_link         = !empty(get_option('PWPF_access_denied_link')) ? get_option('PWPF_access_denied_link') : '#';
$access_denied_link_target  = !empty(get_option('PWPF_access_denied_link_target')) ? get_option('PWPF_access_denied_link_target') : '_self';

$tabs                       = array('settings1' => __('General','protect-wordpress-files'));
$current                    = isset($_GET['tab']) ? esc_attr(strip_tags($_GET['tab'])) : 'settings1';
$settings_page              = PWPF_SETTINGS;
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
<div style="width: 97%; margin-top: 40px;">
    <div class="container navigation">
        <h2 class="nav-tab-wrapper">
            <?php
                foreach( $tabs as $tab => $name ){
                    $class = ( $tab == $current ) ? ' nav-tab-active' : '';
                    echo "<a class='nav-tab{$class}' href='?page={$settings_page}&tab={$tab}'>{$name}</a>";
                }
            ?>
        </h2>
    </div>
    <div class="container settings-form" style="margin-top: 0px;">
        <?php if($current == 'settings1'){ ?>
            <!-- Downloads settings form -->
            <form method="POST" id="settings-form">
                <table class="widefat fixed" style="border-top: 0px;">
                    <tr style="vertical-align:top;">
                        <th>
                            <label for="PWPF_access_denied_message"><?php _e('Access denied message','protect-wordpress-files'); ?></label>
                        </th>
                        <td>
                            <input class="field" style="width: 100%;" type="text" name="PWPF_access_denied_message" placeholder="<?php _e('Access denied message','protect-wordpress-files'); ?>" value="<?php echo $access_denied_message; ?>">
                            <p class="small" style="font-size: 9px;">
                                <strong><?php _e('Shortags:','protect-wordpress-files'); ?></strong>
                                <br/>
                                <i><?php _e('Add a link:','protect-wordpress-files'); ?></i> {link} <?php _e('Return to homepage','protect-wordpress-files'); ?> {/link}
                                <br/>
                                <i><?php _e('Add a page break:','protect-wordpress-files'); ?></i> {br}
                            </p>
                        </td>
                    </tr>
                    <tr style="vertical-align:top;">
                        <th>
                            <label for="PWPF_access_denied_link"><?php _e('Access denied redirect link','protect-wordpress-files'); ?></label>
                        </th>
                        <td>
                            <input class="field" style="width: 100%;" type="text" name="PWPF_access_denied_link" placeholder="https://www.example.com" value="<?php echo $access_denied_link; ?>">
                            <br/>
                            <select name="PWPF_access_denied_link_target" style="margin-top: 10px;">
                                <option value="_self" <?php echo $access_denied_link_target == '_self' ? 'selected="selected"' : ''; ?>><?php _e('Same window','protect-wordpress-files'); ?></option>
                                <option value="_blank" <?php echo $access_denied_link_target == '_blank' ? 'selected="selected"' : ''; ?>><?php _e('New window','protect-wordpress-files'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php wp_nonce_field('pwpf-save-settings', 'pwpf-settings-nonce'); ?>
                            <input class="button button-primary" type="submit" value="<?php _e('Save','protect-wordpress-files'); ?>">
                        </td>
                        <td></td>
                    </tr>
                </table>
            </form>
            <!-- Downloads settings form -->
        <?php } ?>
    </div>
</div>
<?php
    wp_enqueue_style('protect-wordpress-files-main-css');
?>