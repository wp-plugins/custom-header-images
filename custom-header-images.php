<?php
/*
Plugin Name: Custom Header Images
Plugin URI: http://www.blackbam.at/blog/
Description: A very simple and lightweight Plugin for managing custom header images for pages, posts, archive-pages, and all other possible.
Author: David Stöckl
Version: 1.0.0
Author URI: http://www.blackbam.at/blog/
*/

/* 1. Version check */
global $wp_version;

$exit_msg='The Ultra Simple Shop Plugin requires WordPress version 3.0 or higher. <a href="http://codex.wordpress.org/Upgrading_Wordpress">Please update!</a>';

if(version_compare($wp_version,"3.0","<")) {
	exit ($exit_msg);
}

// check if at least PHP 5.2.0
if (!(strnatcmp(phpversion(),'5.2.0') >= 0)) {
	exit('The Ultra Simple Shop Plugin requires at least PHP 5.2.0.');
}


/* 2. Install / Uninstall */
register_activation_hook(__FILE__,"chi_activate");

function chi_activate() {
	$header_images = array(
		'chi_width' =>960,
		'chi_height' => 250
	);
	add_option('chi_data',$header_images);
	
	register_uninstall_hook(__FILE__,"chi_uninstall");
}

function chi_uninstall() {
	// delete all options, tables, ...
	delete_option('chi_data');
}

/*************** Administration ****************/

// add the backend menu page
add_action('admin_menu','chi_options');

// add the options page
function chi_options() {
	add_options_page('Header Images','Header Images','manage_options',__FILE__,'chi_backend_page');
}

function chi_backend_page() { ?>
		<div class="wrap">
			<div><?php screen_icon('options-general'); ?></div>
			<h2>Settings: Header Images</h2>
			<?php
			if(isset($_POST['chi_backend_update']) && $_POST['chi_backend_update']!="") {
				
				$header_images = array();
				
				$header_images['chi_width'] = $_POST['chi_width'];
				$header_images['chi_height'] = $_POST['chi_height'];
				
				$header_images['chi_display_nothing'] = 0;
				if($_POST['chi_display_nothing'] == 1) {
					$header_images['chi_display_nothing'] = 1;
				}
				
				$header_images['chi_url_front'] = $_POST['chi_url_front'];
				$header_images['chi_url_home'] = $_POST['chi_url_home'];
				$header_images['chi_url_404'] = $_POST['chi_url_404'];
				$header_images['chi_url_search'] = $_POST['chi_url_404'];
				$header_images['chi_url_archive_default'] = $_POST['chi_url_archive_default'];
				$header_images['chi_url_single_default'] = $_POST['chi_url_single_default'];
				$header_images['chi_url_page_default'] = $_POST['chi_url_page_default'];
				$header_images['chi_url_global_default'] = $_POST['chi_url_global_default'];
				
				$header_images = array_map('trim',$header_images);
				
				update_option('chi_data',$header_images);
				?>
					<div id="setting-error-settings_updated" class="updated settings-error"> 
						<p><strong><?php _e('Settings saved successfully.'); ?></strong></p>
					</div>
			<?php
			} 
			
			// get the data
			$data = get_option('chi_data');
			?>
			<form name="improved_user_search_in_backend_update" method="post" action="">
				<p>Please consider <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DX9GDC5T9J9AQ">donating</a>. Thank you.</p>
				<p><strong>Note:</strong> If you host your images on your site (recommended), than you upload these using the media library:</p>
				<ol>
					<li>Go to the <a href="<?php bloginfo('wpurl'); ?>/wp-admin/upload.php">media library</a> and upload your images</li>
					<li>Copy the file-URL(s) of your image(s) and copy it to the desired position in this page</li>
					<li>Save the settings</li>
				</ol>
				<p><strong>Note:</strong> Just leave the fields blank to display the global default image or no image.</p>
				<p>Post, Page, Category and Taxonomy Images are set in the posts menu.</p>
				<h2>General Settings</h2>
				<div>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">Default Header Image Width</th>
							<td><input type="text" size="6" name="chi_width" value="<?php echo $data['chi_width']; ?>" /></td>
							<td class="description">The image displayed on the article overview page.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Default Header Image Height</th>
							<td><input type="text" size="6" name="chi_height" value="<?php echo $data['chi_height']; ?>" /></td>
							<td class="description">The image displayed on a static frontpage.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Display nothing by default?</th>
							<td><input type="checkbox" name="chi_display_nothing" value="1" <?php if($data['chi_display_nothing']==1) {?>checked="checked"<?php }; ?> /></td>
							<td class="description">If this option is on, the Plugin displayes nothing, if no concrete image is specified.</td>
						</tr>
					</table>
				</div>
				<p>&nbsp;</p>
				<h2>Image Settings</h2>
				<div>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">Home/Blog Image URL</th>
							<td><input type="text" size="100" name="chi_url_home" value="<?php echo $data['chi_url_home']; ?>" /></td>
							<td class="description">The image displayed on the article overview page.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Frontpage Image URL</th>
							<td><input type="text" size="100" name="chi_url_front" value="<?php echo $data['chi_url_front']; ?>" /></td>
							<td class="description">The image displayed on a static frontpage.</td>
						</tr>
						<tr valign="top">
							<th scope="row">404 Image URL</th>
							<td><input type="text" size="100" name="chi_url_404" value="<?php echo $data['chi_url_404']; ?>" /></td>
							<td class="description">The image displayed on error pages.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Search Image URL</th>
							<td><input type="text" size="100" name="chi_url_search" value="<?php echo $data['chi_url_search']; ?>" /></td>
							<td class="description">The image displayed on search pages.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Archive Default Image URL</th>
							<td><input type="text" size="100" name="chi_url_archive_default" value="<?php echo $data['chi_url_archive_default']; ?>" /></td>
							<td class="description">The image displayed on archive pages by default.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Single Post Default Image URL</th>
							<td><input type="text" size="100" name="chi_url_single_default" value="<?php echo $data['chi_url_single_default']; ?>" /></td>
							<td class="description">The image displayed on single posts by default.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Page Image Default URL</th>
							<td><input type="text" size="100" name="chi_url_page_default" value="<?php echo $data['chi_url_page_default']; ?>" /></td>
							<td class="description">The image displayed on pages by default.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Global Image Default URL</th>
							<td><input type="text" size="100" name="chi_url_global_default" value="<?php echo $data['chi_url_global_default']; ?>" /></td>
							<td class="description">The image displayed on all pages, which have no set default.</td>
						</tr>
					</table>
					<p></p>
					<p><input type="hidden" name="chi_backend_update" value="doit" />
					<input type="submit" name="Save" value="Save Settings" class="button-primary" /></p>
				</div>
			</form>
		</div>
<?php } 

/************** Post/Page/Post-Type Options *******/
add_action('admin_init', 'chi_init');
add_action('save_post', 'save_chi_post');

// füge Post/Page/Post-Type Meta-Boxen hinzu
function chi_init(){
    add_meta_box("chi_post_settings", __("Custom Header Image",'custom-header-images'), "chi_post_settings", "post", "normal", "high");
	add_meta_box("chi_post_settings", __("Custom Header Image",'custom-header-images'), "chi_post_settings", "page", "normal", "high");
}

function chi_post_settings(){
    global $post;
    $custom = get_post_custom($post->ID);
?>
	<div class="inside">
		<table class="form-table">
			<tr>
				<th><label for="chi_post_setting_1">Custom Header image URL</label></th>
				<td>
					<input type="text" size="50" name="chi_post_setting_1" value="<?php echo $custom["chi_post_setting_1"][0]; ?>" />
				</td>
			</tr>
		</table>
	</div>
<?php
}

function save_chi_post(){
	global $post;
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}
	
	//if($post->post_type == "post" || $post->post_type == "page") {
		update_post_meta($post->ID, "chi_post_setting_1", trim($_POST["chi_post_setting_1"]));
	//}
}

/************** End Post/Page/Post-Type Options *******/

/******** Category options ***********/
///////////// Category custom Thumbnail
//add extra fields to category edit form hook
add_action ( 'category_add_form_fields', 'extra_category_fields_add');
add_action ( 'edit_category_form_fields', 'extra_category_fields_edit');

//add extra fields to category edit form callback function
function extra_category_fields_edit( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "chi_term_setting_1_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Category Image Url'); ?></label></th>
<td>
<input type="text" name="chi_term_setting_1_[img]" id="chi_term_setting_1_[img]" size="40" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
            <span class="description"><?php _e('Die URL zum Kategorie-Thumbnail - bitte den relativen Pfad benutzen i.d.R. /wp-content/..pfad_zum_bild/bild.jpg'); ?></span>
        </td>
</tr>
<?php
}

// add extra fields to the category add function
function extra_category_fields_add($tag) {
    $t_id = $tag->term_id;
    $cat_meta = get_option( "chi_term_setting_1_$t_id");
	?>
	<div class="form-field">
	<label for="cat_Image_url"><?php _e('Category Image Url'); ?></label>
	
	<input type="text" name="chi_term_setting_1_[img]" id="chi_term_setting_1_[img]" size="40" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
	            <span class="description"><?php _e('Die URL zum Kategorie-Thumbnail - bitte den relativen Pfad benutzen i.d.R. /wp-content/..pfad_zum_bild/bild.jpg'); ?></span>
	        
	</div>
		<?php
}

// save extra category extra fields hook
add_action ( 'edited_category', 'save_extra_category_fields');
add_action ( 'create_category', 'save_extra_category_fields');

   // save extra category extra fields callback function
function save_extra_category_fields( $term_id ) {
	
    if ( isset( $_POST['chi_term_setting_1_'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "chi_term_setting_1_$t_id");
        $cat_keys = array_keys($_POST['chi_term_setting_1_']);
            foreach ($cat_keys as $key){
            if (isset($_POST['chi_term_setting_1_'][$key])){
                $cat_meta[$key] = $_POST['chi_term_setting_1_'][$key];
            }
        }
        //save the option array
        update_option( "chi_term_setting_1_$t_id", $cat_meta );
    }
}



/************** Display functions **********/
add_action('wp_head','chi_css');

function chi_css() {?>
	<style type="text/css">
		.chi_display_header {
			background-repeat:no-repeat;
			background-position:center center;
		}
	</style>
<?php }

function chi_display_header($width=-1,$height=-1) {
	
	$urls = get_option('chi_data');
	
	$header_image_url = "";
	
	if($width==-1) {
		$width = $urls['chi_width'];
	}
	
	if($height==-1) {
		$height = $urls['chi_height'];
	}

	
	if(is_front_page()) {
		$header_image_url = $urls['chi_url_front'];
	} else if(is_home()) {
		$header_image_url = $urls['chi_url_home'];
	} else if(is_404()) {
		$header_image_url = $urls['chi_url_404'];
	} else if(is_search()) {
		$header_image_url = $urls['chi_url_search'];
	} else if(is_archive()) {
		$cid = get_query_var('cat');
		$category_image_url = get_option( "chi_term_setting_1_$cid");
		
		if($category_image_url!="") {
			$header_image_url = $category_image_url;
		} else {
			$header_image_url = $urls['chi_url_single_default'];
		}
		
		$header_image_url = $urls['chi_url_archive_default'];
	} else if(is_single()) {
		global $post;
		$single_image_url=get_post_meta($post->ID,"chi_post_setting_1",true);
		
		if($single_image_url!="") {
			$header_image_url = $single_image_url;
		} else {
			$header_image_url = $urls['chi_url_single_default'];
		}
	} else if(is_page()) {
		global $post;
		$single_image_url=get_post_meta($post->ID,"chi_post_setting_1",true);
		
		if($single_image_url!="") {
			$header_image_url = $single_image_url;
		} else {
			$header_image_url = $urls['chi_url_page_default'];
		}
	}

	if($header_image_url=="" && $urls['chi_display_nothing']!=1) {
		$header_image_url=$urls['chi_url_global_default'];
	}
	
	if($header_image_url!="") { ?>
		<div class="chi_display_header" style="height:<?php echo $height;?>px; width:<?php echo $width;?>px; background-image:url('<?php echo $header_image_url; ?>');"></div>
	<?php
		}
	}

?>