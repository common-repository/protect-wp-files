<?php

/**
 * The actual WP functions and hooks.
 * 
 * @link       csorbamedia.com
 * @since      1.5
 *
 * @package    private_wordpress_files
 * @subpackage private_wordpress_files/includes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class PWPF_Hooks {

    const PAGE_IDENTIFIER   = 'pwpf_configurator';
    const START_IDENTIFIER  = 'protect-wordpress-files';
    
    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.5
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct() {
        // Nothing to construct
    }

    // This will register our admin page for this plugin
    public function PWPF_load_admin_pages(){
        $menu_title = __( 'Protect uploads', 'protect-wordpress-files' );

        add_menu_page(__('Protect uploads', 'protect-wordpress-files') , $menu_title, 'manage_pwpf_files', 'protect-wordpress-files', array(
            $this,
            'PWPF_admin_settings'
        ) , 'data:image/svg+xml;base64,' . base64_encode('<svg version="1.1" id="Layer_1" focusable="false" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.2 19.9" style="enable-background:new 0 0 15.2 19.9;" xml:space="preserve"><g><path fill="black" d="M8.6,6.4v-4H2.6c-0.4,0-0.7,0.3-0.7,0.7v13.7c0,0.4,0.3,0.7,0.7,0.7h9.9c0.4,0,0.7-0.3,0.7-0.7V7.1h-4C8.9,7.1,8.6,6.8,8.6,6.4z M11.2,13.5H8.8v3c0,0.3-0.3,0.6-0.6,0.6H7c-0.3,0-0.6-0.3-0.6-0.6v-3H4c-0.5,0-0.8-0.6-0.4-1L7.1,9C7.4,8.7,7.8,8.7,8,9l3.6,3.5C12,12.9,11.7,13.5,11.2,13.5z M13,5.2l-2.6-2.6c-0.1-0.1-0.3-0.2-0.4-0.2H9.8v3.4h3.4V5.6C13.2,5.5,13.2,5.3,13,5.2z"/></g></svg>') , 5);

        add_submenu_page( 'protect-wordpress-files', 
            __('Settings', 'protect-wordpress-files'), 
            __('Settings', 'protect-wordpress-files'),
            'manage_options', 
            PWPF_SETTINGS, 
            array($this, 'PWPF_settings')
        );

        add_submenu_page( 'protect-wordpress-files', 
            __('Introduction', 'protect-wordpress-files'), 
            __('Introduction', 'protect-wordpress-files'),
            'manage_options', 
            PWPF_INTRODUCTION, 
            array($this, 'PWPF_introduction')
        );

        add_submenu_page( 'protect-wordpress-files', 
            __('Support', 'protect-wordpress-files'), 
            __('Support', 'protect-wordpress-files'),
            'manage_options',
            PWPF_SUPPORT, 
            array($this, 'PWPF_support')
        );
        
    }

    // Load bulk settings template
    public function PWPF_admin_settings(){
        require_once ( PWPF_PLUGIN_DIR . '/includes/admin-templates/upload.php');
    }

    // Load bulk settings template
    public function PWPF_settings(){
        require_once ( PWPF_PLUGIN_DIR . '/includes/admin-templates/settings.php');
    }

    // Support page
    public function PWPF_support(){
        require_once ( PWPF_PLUGIN_DIR . '/includes/admin-templates/support.php');
    }

    // Introduction page
    public function PWPF_introduction(){
        require_once ( PWPF_PLUGIN_DIR . '/includes/admin-templates/wizard.php');
    }

    // This is a filter to upload content to another directory
    // @todo @test check if the directory creates
    public function PWPF_custom_upload_dir( $dir_data ) {
        // $dir_data already you might want to use
        $custom_dir = PWPF_UPLOAD_DIR ? PWPF_UPLOAD_DIR : 'private';
        
        // Check if the directory exists or create it
        if (!is_dir($dir_data[ 'basedir' ] . '/' . $custom_dir)) {
            mkdir($dir_data[ 'basedir' ] . '/' . $custom_dir, 0777, true);
        }
        
        return [
            'path' => $dir_data[ 'basedir' ] . '/' . $custom_dir,
            'url' => $dir_data[ 'url' ] . '/' . $custom_dir,
            'subdir' => '/' . $custom_dir,
            'basedir' => $dir_data[ 'error' ],
            'error' => $dir_data[ 'error' ],
        ];
    }

    // This will handle the upload of private files
    public function PWPF_upload_private_media(){
        // for the upload support
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }

        $msg            = '';
        $download_url   = '';
        $error          = false;
        $mime_allowed   = true;

        if(isset( $_POST['private_upload_media_nonce'] ) && current_user_can( 'manage_pwpf_files' )){
            
            // changing the directory
            add_filter( 'upload_dir', array($this, 'PWPF_custom_upload_dir'));
            // uploading
            foreach ($_FILES as $key => $file) {
                // Check for allowed mimetypes
                $file_type  =   wp_check_filetype($file['name']);
                $check_mime =   self::PWPF_check_mime($file_type['ext']);
                // If allowed mim we can upload it
                if($check_mime == true){
                    $attachment_id = media_handle_upload($key, 0, array(), array("test_form" => false));
                }else{
                    $mime_allowed   = false;
                }
            }
            // remove so it doesn't apply to all uploads
            remove_filter( 'upload_dir', array( $this, 'PWPF_custom_upload_dir' ));

            if ( $attachment_id ) {
                // There was an error uploading the image.
                update_post_meta($attachment_id, 'is_private', true);
                $download_url = sprintf('%s/download/file/%s/', get_bloginfo( 'url' ), $attachment_id);
                $msg = __('The file is uploaded.','protect-wordpress-files');
            } else {
                // The image was uploaded successfully!
                $error = 1;
                if($mime_allowed == false){
                    $msg = __('This type of file is not allowed by WordPress to upload.','protect-wordpress-files');
                }else{
                    $msg = __('The file is not uploaded for some reason','protect-wordpress-files');
                }
            }
        }else{
            $error = 1;
            $msg = __('Oops you tried something insecure!','protect-wordpress-files');
        }
        echo json_encode(array('message' => $msg, 'url' => $download_url, 'error' => $error));
        exit;
    }

    // This will change the url from public to private url by array
    public function PWPF_url_by_array($file){

        if ( !is_array($file) ) {
            return;
        }

        if ( !isset($file['ID']) ) {
            return $file;
        }

        // When the filter is called directly we also need to check if the file is private or not in order to return the right firl url
        $is_private     =   get_post_meta($file['ID'], 'is_private', true) == true ? true : false;

        return $is_private == true ? get_bloginfo( 'url' ) . '/download/file/'.$file['ID'].'/' : $file['url'];

    }

    // this will change the url from public to private url by url slug
    public function PWPF_url_by_slug($content){

        if ( empty($content) ) {
            return $content;
        }

        // Defines
        $original_urls = array();

        // Do the url checking
        if(class_exists('DOMDocument')){
            
            // New way to search something

            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($content);
            libxml_use_internal_errors(false);
            $xpath = new DOMXPath($doc);
            $nodeList = $xpath->query('//a/@href');

            for ($i = 0; $i < $nodeList->length; $i++) {
                # Xpath query for attributes gives a NodeList containing DOMAttr objects.
                # http://php.net/manual/en/class.domattr.php
                $url = $nodeList->item($i)->value;
                if(!empty(trim($url))){
                    if(strpos(trim($url),'/private/')){
                        $original_urls[] = array('origin' => $url, 'private' => $this->PWPF_link($url));
                    }
                }
            }

        }else{
            
            // Old fashion way to search something
            $regex = '/<a href="(.*?)"/s';
            $private_match = preg_match_all($regex, $content, $match);
            $original_urls = array();
            
            if($private_match){
                foreach($match[1] as $url){
                    if(!empty(trim($url))){
                        if(strpos(trim($url),'/private/')){
                            $original_urls[] = array('origin' => $url, 'private' => $this->PWPF_link($url));
                        }
                    }
                }
            }
        
        }
        
        if(count($original_urls) > 0){
            foreach($original_urls as $key => $urls){
                $content     = str_replace( $urls['origin'], $urls['private'], $content );
            }
        }

        return $content;

    }

    // This will be used to check if there are files which should be linked to our private media url instead of general wordpress url
    public function PWPF_content_filter($content){
        return apply_filters( 'private_media_url_by_slug', $content );
    }

    // Return the private link from slug
    public function PWPF_link($url){

        global $wpdb;

        if(empty($url)){
            return $url;
        }

        $file           = array();
        $file_url       = trim($url);
        $file_path      = ltrim(str_replace(wp_upload_dir()['baseurl'], '', $file_url), '/');
        $file['url']    = $file_url;
        $file['path']   = $file_path;

        $statement = $wpdb->prepare("SELECT `ID` FROM $wpdb->posts AS posts JOIN $wpdb->postmeta AS meta on meta.`post_id`=posts.`ID` WHERE posts.`guid`='%s' OR (meta.`meta_key`='_wp_attached_file' AND meta.`meta_value` LIKE '%%%s');",
            $file_url,
            $file_path);

        $attachment = $wpdb->get_var($statement);

        if($attachment){
            $file['ID'] = $attachment;
            return $this->PWPF_url_by_array($file);
        }else{
            return $url;
        }

    }

    // Media library hook change view linkage
    public function PWPF_medialib_view_link($actions, $page_object){
        // Check if the file is private
        $file           = array();
        $file['ID']     = $page_object->ID;
        $is_private     = get_post_meta($page_object->ID, 'is_private', true) == true ? true : false;
        $private_url    = $is_private == true ? $this->PWPF_url_by_array($file) : '';

        if($is_private && !empty($private_url)){
            $actions['view']            = sprintf('<a href="%s" target="_blank">'.__('View','protect-wordpress-files').'</a>', $private_url);
            $actions['unprotect']       = sprintf('<a href="?private_media_unprotect=%s">'.__('Unprotect','protect-wordpress-files').'</a>', $page_object->ID);
        }else{
            // This link is only available for administrators
            if(current_user_can('administrator')){
                $actions['protect'] = sprintf('<a href="?private_media_protect=%s">'.__('Protect','protect-wordpress-files').'</a>', $page_object->ID);
            }
        }

        return $actions;

    }

    // Media library add extra column
    public function PWPF_media_columns($columns){
        $columns['private_media_url'] = __('URL','protect-wordpress-files');
	    return $columns;
    }

    // Media library add extra column | link
    public function PWPF_media_custom_column($column_name, $post_id){
        
        switch ( $column_name )
        {
            case 'private_media_url' :
                
                $file           = array();
                $file['ID']     = $post_id;
                $is_private     = get_post_meta($post_id, 'is_private', true) == true ? true : false;
                $private_url    = $is_private == true ? $this->PWPF_url_by_array($file) : '';
        
                if($is_private && !empty($private_url)){
                    echo '<input type="text" style="background: red; color: white;" width="100%" onclick="jQuery(this).select();" value="' . $private_url . '" />';
                    echo sprintf("<script>
                                    (function($){ 
                                        var src = $('#post-%s span.media-icon img').attr('src');
                                        var srcset = $('#post-%s span.media-icon img').removeAttr('srcset');
                                        $('#post-%s span.media-icon img').attr('src', src.replace('wp-content/uploads/private','private/thumbs'));
                                        $('#post-%s span.media-icon').append('%s');
                                    })(jQuery);
                                  </script>", 
                                    $post_id, 
                                    $post_id, 
                                    $post_id, 
                                    $post_id, 
                                    __('<span style="color: red; border: 1px solid red; font-size: 9px; text-transform: uppercase;">Protected</span>','protect-wordpress-files'));
                }else{
                    echo '<input type="text" width="100%" onclick="jQuery(this).select();" value="' . wp_get_attachment_url() . '" />';
                }

            break;

            default :
            break;
        }
    
    }

    // Protect the file
    public function PWPF_protect_file(){

        global $wpdb;

        $current_screen = get_current_screen();

        if($current_screen->id == 'upload' && isset($_REQUEST['private_media_protect']) && !empty($_REQUEST['private_media_protect'])){
            
            if(!is_user_logged_in()){
                wp_die(__('You are not allowed to do this action.','protect-wordpress-files'));
            }

            if(!current_user_can('manage_pwpf_files')){
                wp_die(__('You are not allowed to do this action.','protect-wordpress-files'));
            }

            // XSS stripper
            foreach($_REQUEST as $key => $value){
                $_REQUEST[$key] = wp_kses_data($value);
            }
            
            // Get the attachement id
            $attachment_id = $_REQUEST['private_media_protect'];
            
            // Get and set our variables
            $upload_dir     = wp_upload_dir();
            $custom_dir     = PWPF_UPLOAD_DIR ? PWPF_UPLOAD_DIR : 'private';
            $private_dir    = $upload_dir['basedir'] . '/' . $custom_dir;
            $original_dir   = get_attached_file($attachment_id);
            $file_name      = basename(get_attached_file($attachment_id));
            $new_dir        = $private_dir.'/'.$file_name;
            $new_guid       = $upload_dir['baseurl'] . '/' . $custom_dir . '/' . $file_name;
            
            // Check if the directory exists or create it
            if (!is_dir($private_dir)) {
                mkdir($private_dir, 0777, true);
            }

            if(strpos(trim($original_dir),'/private/')){
                wp_die(__('This file is already protected.','protect-wordpress-files'));
            }

            // Copy the file to the new directory
            if(rename($original_dir, $new_dir)){
                // If the file has been moved update the post meta data
                update_post_meta($attachment_id, '_wp_attached_file', $new_dir);
                update_post_meta($attachment_id, 'is_private', true);
                wp_generate_attachment_metadata($attachment_id, $new_dir);
                $wpdb->update( $wpdb->posts, array('guid' => $new_guid), array('ID' => $attachment_id) );
                wp_redirect(admin_url( 'upload.php' ));
            }

        }

    }

    // Unprotect the file
    public function PWPF_unprotect_file(){

        global $wpdb;

        $current_screen = get_current_screen();

        if($current_screen->id == 'upload' && isset($_REQUEST['private_media_unprotect']) && !empty($_REQUEST['private_media_unprotect'])){
            
            if(!is_user_logged_in()){
                wp_die(__('You are not allowed to do this action.','protect-wordpress-files'));
            }

            if(!current_user_can('manage_pwpf_files')){
                wp_die(__('You are not allowed to do this action.','protect-wordpress-files'));
            }

            // XSS stripper
            foreach($_REQUEST as $key => $value){
                $_REQUEST[$key] = wp_kses_data($value);
            }
            
            // Get the attachement id
            $attachment_id = $_REQUEST['private_media_unprotect'];
            
            // Get and set our variables
            $upload_dir     = wp_upload_dir();
            $custom_dir     = PWPF_UPLOAD_DIR ? PWPF_UPLOAD_DIR : 'private';
            $private_dir    = $upload_dir['basedir'] . '/' . $custom_dir;
            $original_dir   = get_attached_file($attachment_id);
            $file_name      = basename(get_attached_file($attachment_id));
            $new_dir        = $upload_dir['path'].'/'.$file_name;
            $new_guid       = $upload_dir['url'] . '/' . $file_name;
            $sizes          = wp_get_attachment_metadata($attachment_id);

            // Check if there are WP sizes to be removes
            if(isset($sizes['sizes'])){
                foreach($sizes['sizes'] as $key => $size){
                    if($key != 'full'){
                        $delete_file = $private_dir . '/' . $size['file'];
                        if(file_exists($delete_file)){
                            unlink($delete_file);
                        }
                    }
                }
            }

            // Copy the file to the new directory
            if(rename($original_dir, $new_dir)){
                // If the file has been moved update the post meta data
                update_post_meta($attachment_id, '_wp_attached_file', $new_dir);
                delete_post_meta($attachment_id, 'is_private');
                wp_generate_attachment_metadata($attachment_id, $new_dir);
                $wpdb->update( $wpdb->posts, array('guid' => $new_guid), array('ID' => $attachment_id) );
                wp_redirect(admin_url( 'upload.php' ));
            }

        }

    }

    // This will add some backend style for the menu icon in the admin bar
    public function PWPF_backend_style(){
        ?>
<script>
// shorthand no-conflict safe document-ready function
jQuery(function($) {
    // Hook into the "notice-my-class" class we added to the notice, so
    // Only listen to YOUR notices being dismissed
    $(document).on('click', '.private-media .notice-dismiss', function() {
        // Read the "data-notice" information to track which notice
        // is being dismissed and send it via AJAX
        var type = $(this).closest('.private-media').data('notice');
        // Make an AJAX call
        // Since WP 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.ajax(ajaxurl, {
            type: 'POST',
            data: {
                action: 'private_media_notice_handler',
                type: type,
            }
        });
    });
});
</script>
<style>
#toplevel_page_private-media img {
    width: 16px;
}

.private_media_url input {
    width: 100% !important;
}
.wp-core-ui .private .attachment-preview:after{
    color: red; 
    border: 1px solid red; 
    font-size: 9px; 
    text-transform: uppercase;
    content: "<?php echo __('Protected','protect-wordpress-files'); ?>";
    display: block;
    position: absolute;
    right: 0px;
    bottom: 0px;
    background: #fff;
    padding-left: 5px;
    padding-right: 5px;
}
.compat-field-private_url input{
    background: red;
    color: white;
}
</style>
<?php
    }

    // This will dismiss the admin notices
    public function PWPF_notice_handler(){

        // XSS Filter
        foreach($_REQUEST as $key => $value){
            $_REQUEST[$key] = wp_kses_data($value);
        }

        // Pick up the notice "type" - passed via jQuery (the "data-notice" attribute on the notice)
        $type = $_REQUEST['type'];
        // Store it in the options table
        update_option( 'dismissed-' . $type, TRUE );

    }

    // This function will add our custom query vars
    public function PWPF_add_query_vars( $query_vars ){
        $query_vars[] = 'pwpf_file';
        $query_vars[] = 'pwpf_thumb';
        return $query_vars;
    }

    // This function will add our rewrite rule
    public function PWPF_add_rewrite_rule(){

        add_rewrite_rule('download/file/([^/]+)/?$','index.php?pwpf_file=$matches[1]','top');
        add_rewrite_tag('%pwpf_file%','[^&]+');

        add_rewrite_rule('private/thumbs/([^/]+)/?$','index.php?pwpf_thumb=$matches[1]','top');
        add_rewrite_tag('%pwpf_thumb%','[^&]+');

        // Flush rewrites
        flush_rewrite_rules();

    }

    // This will handle the download action
    public function PWPF_handle_private_download($query = ''){

        $file = '';
        if($query){
            $vars = $query->query_vars;
            if(array_key_exists('pwpf_file', $vars)){
                $file = $query->query_vars['pwpf_file'];
            }
        }

        if(is_user_logged_in() && !empty($file)){

                $mediaPath = get_attached_file(strip_tags(str_replace('/','',$file)));
                $file_name = basename ( get_attached_file( str_replace('/','',$file) ) );

                // Check if the file exists
                if(!is_file($mediaPath)){
                    wp_die(__('The file does not exists.','protect-wordpress-files'));
                }

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.$file_name.'"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($mediaPath));
                ob_clean();
                flush();
                if (ob_get_level()) ob_end_clean();
                readfile($mediaPath);
                exit();

        }else{
            if(!empty($file)){
                $access_denied_message = !empty(get_option('PWPF_access_denied_message')) ? $this->PWPF_short_tags(get_option('PWPF_access_denied_message')) : __('You need to be loggedin to access this file.','protect-wordpress-files');
                wp_die($access_denied_message);
            }
        }

    }

    // ACF private upload dir
    public function PWPF_acf_custom_upload_dir( $param ){
        $custom_dir          = PWPF_UPLOAD_DIR ? '/' . PWPF_UPLOAD_DIR : '/private';
        $param['path']  = $param['basedir'] . $custom_dir;
        $param['url']   = $param['baseurl'] . $custom_dir;
        return $param;
    }

    // ACF private upload filter
    public function PWPF_acf_upload_prefilter( $errors, $file, $field ){
        $pwpf   = new PWPF_Hooks;
        //this filter changes directory just for item being uploaded
        add_filter( 'upload_dir', array($this, 'PWPF_acf_custom_upload_dir'));
        return $errors;
    }

    // Attachment filter
    public function PWPF_acf_select_attachment($metadata, $attachment_id){

        $attachment = get_attached_file($attachment_id);

        if(strpos(trim($attachment),'/private/')){
            update_post_meta($attachment_id, 'is_private', true);
        }

        return $metadata;

    }

    /**
	 * This will add the plugin wizard page
	 */
	public function PWPF_add_wizard_page(){
        add_dashboard_page( __('Protect WordPress Uploads','protect-wordpress-files'), 
                            __('Protect WordPress Uploads','protect-wordpress-files'), 
                            'manage_options', 
                            self::PAGE_IDENTIFIER, 
                            array($this, 'PWPF_render_wizard_page')
                        );
	}

	/**
	 * This will add the plugin wizard page assets
	 */
	public function PWPF_enqueue_assets(){
		$minify_enabled = (PWPF_MINIFY == true) ? 'min.' : '';
		$minify_version = (PWPF_MINIFY == true) ? PWPF_CSS_JS_VERSION : time();
		wp_register_style( 'protect-wordpress-files-main-css', PWPF_ASSETS_DIR . sprintf('css/pwpf-admin.%scss', $minify_enabled), '', $minify_version, 'all' );
		wp_register_style( 'protect-wordpress-files-bootstrap-css', PWPF_ASSETS_DIR . 'css/bootstrap.min.css', '', '', 'all' );
        wp_register_script( 'protect-wordpress-files-admin-js', PWPF_ASSETS_DIR . sprintf('js/pwpf-admin.%sjs', $minify_enabled), '', $minify_version, true );
    }

	/**
	 * This render the wizard page
	 */
	public function PWPF_render_wizard_page(){
        require_once ( PWPF_PLUGIN_DIR . '/includes/admin-templates/wizard.php');
	}

	/**
	 * Redirect the user to the introduction page
	 * of the plugin.
	 */
	public function PWPF_redirect_admin(){
		if ( get_option( 'Activated_Protect_Wordpress_Files' ) ) {
			delete_option( 'Activated_Protect_Wordpress_Files' );
			if ( ! headers_sent() ) {
				exit(wp_redirect(sprintf('index.php?page=%s', self::PAGE_IDENTIFIER)));
			}
		}
    }
    
    /**
	 * Check if the mimetype is allowed
	 */
    public function PWPF_check_mime($file_ext) {
        $mimes = get_allowed_mime_types();
        $mime_allowed = 0;
        if ( !empty( $mimes ) ) {
            foreach ($mimes as $type => $mime ) {
                if ( false !== strpos( $type, $file_ext ) ) {
                    $mime_allowed = 1;
                }
            }
        }
        return $mime_allowed;
    }

    /*
     * This function checks the webserver
     */
    public function PWPF_check_webserver(){
        if(current_user_can( 'manage_options' )){
            if(strpos(strtolower($_SERVER['SERVER_SOFTWARE']),'nginx') !== false){
                if ( ! get_option('dismissed-private_media_nginx_message', false ) ) { 
                    $message = __('Protect WordPress Uploads! You are using NGINX please update your nginx config file. Please <a href="https://www.mauwen.com/docs/protect-wordpress-uploads/getting-started/installation-activation/" target="_blank">click here</a> to find the example rewrite.','protect-wordpress-files');
                    new PWPF_Message($message, 'error', true);
                }
            }
            if ( empty(get_option('permalink_structure')) ) {  
                $message = __('Protect WordPress Uploads! Switch your permalink structure to anything else then plain. <a href='.admin_url('/options-permalink.php').'>Click here</a> to change.','protect-wordpress-files');
                new PWPF_Message($message, 'error', true);
            }
        }
    }

     /*
     * Add user capability
     */
    public function PWPF_add_caps(){
        // Get role
        $administrator_role = get_role( 'administrator' );
        // Adding a new capability to role administrator
        $administrator_role->add_cap( 'manage_pwpf_files' );
        // Get role
        $editor_role = get_role( 'editor' );
        // Adding a new capability to role editor
        $editor_role->add_cap( 'manage_pwpf_files' );
    }

    /**
	 * This will add some links to the plugins page
	 */
    public function PWPF_plugin_links( $links ) {
		$tour      		= '<a href="'.admin_url( 'admin.php?page=' . PWPF_INTRODUCTION  ).'" title="'.__('Introduction','protect-wordpress-files').'">'.__('Introduction','protect-wordpress-files').'</a>';
        $upload  		= '<a href="'.admin_url( 'admin.php?page=' . self::START_IDENTIFIER  ).'">'.__('Upload','protect-wordpress-files').'</a>';
        $settings  		= '<a href="'.admin_url( 'admin.php?page=' . PWPF_SETTINGS  ).'">'.__('Settings','protect-wordpress-files').'</a>';
        $documentation  = '<a href="'.PWPF_DOCS.'" target="_blank">'.__('Documentation','protect-wordpress-files').'</a>';
        $support  		= '<a href="'.admin_url( 'admin.php?page=' . PWPF_SUPPORT  ).'">'.__('Support','protect-wordpress-files').'</a>';
        array_push( $links, $tour );
        array_push( $links, $upload );
        array_push( $links, $settings );
        array_push( $links, $documentation );
		array_push( $links, $support );
        return $links;
	}

    /**
	 * Add a dropdown filter to media library list view
	 */
    public function PWPF_add_dropdown_filter_list($this_screen_post_type){
        global $pagenow;
        if ( 'upload.php' == $pagenow ) {
            $selected = '';
            if(isset($_REQUEST['protect-wordpress-files'])){
                $selected = esc_attr(strip_tags($_REQUEST['protect-wordpress-files']));
            }
            $dropdown = '<label for="protect-wordpress-files" class="screen-reader-text">'.__('Filter by protected uploads','protect-wordpress-files').'</label>';
            $dropdown .='<select name="protect-wordpress-files" id="protect-wordpress-files">';
                $dropdown .= sprintf('<option value="all" %s>%s</option>', ($selected == 'all' ? 'selected' : ''), __('All files (protected/unprotected)','protect-wordpress-files'));
                $dropdown .= sprintf('<option value="protected" %s>%s</option>', ($selected == 'protected' ? 'selected' : ''), __('Protected','protect-wordpress-files'));
                $dropdown .= sprintf('<option value="unprotected" %s>%s</option>', ($selected == 'unprotected' ? 'selected' : ''), __('Unprotected','protect-wordpress-files'));
            $dropdown .= '</select>';
            echo $dropdown;
        }
    }

    /**
	 * Add a dropdown filter to media library grid view jquery
	 */
    public function PWPF_attachment_filter(){

        global $pagenow;
        global $pagenow, $plugin_version;

        $suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        if ( wp_script_is( 'media-editor' ) && 'upload.php' == $pagenow ) {

            $terms      = array();
            $terms[]    = sprintf('{"term_id":"protected","term_name":"%s"}', __('Protected','protect-wordpress-files'));
            $terms[]    = sprintf('{"term_id":"unprotected","term_name":"%s"}', __('Unprotected','protect-wordpress-files'));

            echo '<script type="text/javascript">';
            echo '/* <![CDATA[ */';
            echo sprintf('var pwpf') . ' = {"' . 'pwpf' . '":{"list_title":"' . html_entity_decode( sprintf(__( 'All files (protected/unprotected)', 'protect-wordpress-files' )), ENT_QUOTES, 'UTF-8' ) . '","term_list":[' . implode(',', $terms ) . ']}};';
            echo '/* ]]> */';
            echo '</script>';
            $minify_enabled = (PWPF_MINIFY == true) ? 'min.' : '';
            $minify_version = (PWPF_MINIFY == true) ? PWPF_CSS_JS_VERSION : time();
            wp_enqueue_script('protect-wordpress-files-filtering', PWPF_ASSETS_DIR . sprintf('js/pwpf-filter-admin.%sjs', $minify_enabled), array( 'media-views' ), $minify_version, true ); 
        
        }
        
    }

    /**
	 * This is used to filter by protected files on the list view 
	 */
    public function PWPF_query_filter($wp_query){

        global $pagenow;
        $sOrderby = '';

        if( ! in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
        return;

        // If we have something to filter on 
        if( isset($_REQUEST['protect-wordpress-files']) && $_REQUEST['protect-wordpress-files'] != 'all' ){
            $sOrderby = esc_attr(strip_tags($_REQUEST['protect-wordpress-files']));
        }else{
            return;
        }

        // Do query
        if($sOrderby == 'protected'){
            $wp_query->set( 'meta_query', array(
                array(
                    'key' => 'is_private',
                    'value' => 1,
                    'compare' => '='
                )
            ) );
        }else{
            $wp_query->set( 'meta_query', array(
                array(
                    'key' => 'is_private',
                    'value' => '1',
                    'compare' => 'NOT EXISTS'
                )
            ) );
        }

        return $wp_query;

    }

    /**
	 * This is used to filter by protected files on the grid view 
	 */
    public function PWPF_ajax_query_filter($query = array()){
        // If we have something to filter on 
        $request = isset( $_REQUEST['query'] ) ? (array) $_REQUEST['query'] : array();
        if( isset($request['protect-wordpress-files']) && $request['protect-wordpress-files'] != 'all' ){
            $sOrderby = esc_attr(strip_tags($request['protect-wordpress-files']));
            $query['meta_query'] = array( 'relation' => 'AND' );
            if($sOrderby == 'protected'){
                array_push( $query['meta_query'], array(
                    'key' => 'is_private',
                    'value' => 1,
                    'compare' => '='
                ) );
            }else{
                array_push( $query['meta_query'], array(
                    'key' => 'is_private',
                    'value' => '1',
                    'compare' => 'NOT EXISTS'
                ) );
            }
            unset($query['protect-wordpress-files']);
        }
        return $query;
    }

    /**
	 * This will handle the thumbnails
	 */
    public function PWPF_handle_private_thumb($query = ''){

        $thumbnail = '';
        if($query){
            $vars = $query->query_vars;
            if(array_key_exists('pwpf_thumb', $vars)){
                $thumbnail = $query->query_vars['pwpf_thumb'];
            }
        }

        if(current_user_can( 'upload_files' ) && is_user_logged_in() && !empty($thumbnail)){

            // Our upload dirctory
            $upload_dir     = wp_upload_dir();
            $custom_dir     = PWPF_UPLOAD_DIR ? PWPF_UPLOAD_DIR : 'private';
            $private_dir    = $upload_dir['basedir'] . '/' . $custom_dir;
            $thumb          = $private_dir.'/'.$thumbnail;

            // Check if thumbnail exists
            if(!file_exists($thumb)){
                // Get icons dir
                $icon_dir = apply_filters( 'icon_dir', ABSPATH . WPINC . '/images/media' );
                // Default thumbnail from WP
                $thumb_output = $icon_dir . '/default.png';
            }else{
                // Output the image or jpg
                $thumb_output = $thumb;
            }
            // Output the image or jpg
            $file_type = wp_check_filetype($thumb_output);                       
            header('Content-Type: ' . $file_type['ext']);
            header('Content-Length: '.filesize($thumb_output));
            header('Cache-Control: no-cache');
            readfile($thumb_output);
            exit;

        }

    }

    /**
	 * This will handle the link change for the icons
	 */
    public function PWPF_handle_image_url($url, $post_id = null){

        $is_private     = get_post_meta($post_id, 'is_private', true) == true ? true : false;

        // Is private change the url for the image
        if(current_user_can( 'upload_files' ) && is_user_logged_in() && $is_private){
            $upload_dir     = wp_upload_dir();
            $addslash       = !wp_doing_ajax() ? '/' : '';
            $url = str_replace($upload_dir['baseurl'], '', $url);
            $url = str_replace(PWPF_UPLOAD_DIR, 'private/thumbs', $url);
            $url = get_bloginfo( 'url' ) . $url . $addslash;
            return $url;
        }
        return $url;

    }

    /**
	 * This will add an extra class to private files in the grid view
	 */
    public function PWPF_prepare_attachment_for_js($response, $attachment, $meta){
        $attachment_id  = $attachment->ID;
        $is_private     = get_post_meta($attachment_id, 'is_private', true) == true ? true : false;
        if($is_private){
            $response['customClass'] = "private";
        }else{
            $response['customClass'] = "public";
        }
        return $response;
    }

    /**
	 * This will add an extra field to copy the protected link in grid view
	 */
    public function PWPF_attachment_field_to_edit($form_fields, $attachement){

        $is_private     = get_post_meta($attachement->ID, 'is_private', true) == true ? true : false;
        $private_url    = $is_private == true ? $this->PWPF_url_by_array( (array) $attachement) : '';

        if($is_private){
            $form_fields["private_url"] = array(
                "label" => __('Protected URL', 'protect-wordpress-files'),
                "input" => 'text', // this is default if "input" is omitted
                "value" => $private_url
            );
        }
        return $form_fields;

    }

    /**
	 * This will replace some short tags to html tags
	 */
    public function PWPF_short_tags($content){

        $link   = !empty(get_option('PWPF_access_denied_link')) && get_option('PWPF_access_denied_link') != '#' ? get_option('PWPF_access_denied_link') : '';
        $target = !empty(get_option('PWPF_access_denied_link_target')) ? get_option('PWPF_access_denied_link_target') : '_self';

        if(!empty($link)){
            $content = str_replace('{link}', sprintf('<a href="%s" target="%s">', $link, $target), $content);
            $content = str_replace('{/link}', '</a>', $content);
        }
        $content = str_replace('{br}', '<br/>', $content);
        return $content;
    }

}