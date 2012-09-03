<?php
/*
Plugin Name: Custom Header Images
Plugin URI: http://www.blackbam.at/blog/
Description: A very simple and lightweight Plugin for managing custom header images for pages, posts, archive-pages, and all other possible.
Author: David Stöckl
Version: 1.0.2
Author URI: http://www.blackbam.at/blog/
 *
 * Note: This Plugins is GPLv2 licensed. This Plugin is released without any warranty. 
 * 
@Upcoming Features:
1. Exclude certain post_types / taxonomies from header image functionality
2. Header Image Links
3. Il8n

/* 1. Version check */
global $wp_version;

$exit_msg='The Custom Header Images Plugin requires WordPress version 3.0 or higher. <a href="http://codex.wordpress.org/Upgrading_Wordpress">Please update!</a>';

if(version_compare($wp_version,"3.0","<")) {
	exit ($exit_msg);
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
				
				$header_images['chi_url_global_default'] = $_POST['chi_url_global_default'];
				
				$header_images['chi_url_front'] = $_POST['chi_url_front'];
				$header_images['chi_url_home'] = $_POST['chi_url_home'];
				$header_images['chi_url_404'] = $_POST['chi_url_404'];
				$header_images['chi_url_search'] = $_POST['chi_url_search'];
				
				$header_images['chi_url_single_default'] = $_POST['chi_url_single_default'];
				$header_images['chi_url_page_default'] = $_POST['chi_url_page_default'];
				
				$header_images['chi_url_archive_default'] = $_POST['chi_url_archive_default'];
				$header_images['chi_url_date'] = $_POST['chi_url_date'];
				$header_images['chi_url_author_default'] = $_POST['chi_url_author_default'];
				$header_images['chi_url_category_default'] = $_POST['chi_url_category_default'];
				$header_images['chi_url_tag_default'] = $_POST['chi_url_tag_default'];
				$header_images['chi_url_tax_default'] = $_POST['chi_url_tax_default'];
				
				
				$header_images = array_map('trim',$header_images);
				
				update_option('chi_data',$header_images);
				?>
					<div id="setting-error-settings_updated" class="updated settings-error"> 
						<p><strong><?php _e('Settings saved successfully.','customheaderimages'); ?></strong></p>
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
					<li>Go to the <a href="<?php bloginfo('wpurl'); ?>/wp-admin/upload.php">media library</a> and upload your images (or use an external absolute image URL)</li>
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
							<th scope="row">Global Image Default URL</th>
							<td><input type="text" size="100" name="chi_url_global_default" value="<?php echo $data['chi_url_global_default']; ?>" /></td>
							<td class="description">The image displayed on all pages, which have no set default.</td>
						</tr>
						<tr>
							<th colspan="3"><strong>Special Pages</strong></th>
						</tr>
						<tr valign="top">
							<th scope="row">Home Image URL</th>
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
						<tr>
							<th colspan="3"><strong>Posts &amp; Pages</strong></th>
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
						<tr>
							<th colspan="3"><strong>Archive Pages</strong></th>
						</tr>
						<tr valign="top">
							<th scope="row">Archive Default Image URL</th>
							<td><input type="text" size="100" name="chi_url_archive_default" value="<?php echo $data['chi_url_archive_default']; ?>" /></td>
							<td class="description">The image displayed on archive pages by default.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Date Image URL</th>
							<td><input type="text" size="100" name="chi_url_date" value="<?php echo $data['chi_url_date']; ?>" /></td>
							<td class="description">The image displayed on date archive pages.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Author Image Default URL</th>
							<td><input type="text" size="100" name="chi_url_author_default" value="<?php echo $data['chi_url_author_default']; ?>" /></td>
							<td class="description">The image displayed on author pages by default.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Category Image Default URL</th>
							<td><input type="text" size="100" name="chi_url_category_default" value="<?php echo $data['chi_url_category_default']; ?>" /></td>
							<td class="description">The image displayed on category pages by default.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Tag Image Default URL</th>
							<td><input type="text" size="100" name="chi_url_tag_default" value="<?php echo $data['chi_url_tag_default']; ?>" /></td>
							<td class="description">The image displayed on tag pages by default.</td>
						</tr>
						<tr valign="top">
							<th scope="row">Taxonomy Image Default URL</th>
							<td><input type="text" size="100" name="chi_url_tax_default" value="<?php echo $data['chi_url_tax_default']; ?>" /></td>
							<td class="description">The image displayed on taxonomy pages by default.</td>
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
	
	$post_types = get_post_types(array('public'=>'true'));
	
	foreach($post_types as $pt) {
		add_meta_box("chi_post_settings", __("Custom Header Image",'custom-header-images'), "chi_post_settings", $pt, "normal", "default");
	}
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
			<tr>
				<th><label for="chi_post_setting_2">Display nothing?</label></th>
				<td>
					<input type="checkbox" size="50" name="chi_post_setting_2" value="1" <?php if($custom["chi_post_setting_2"][0]==1) {?>checked="checked"<?php } ?> />
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
		update_post_meta($post->ID, "chi_post_setting_2", intval($_POST["chi_post_setting_2"]));
	//}
}

/************** End Post/Page/Post-Type Options *******/

/******** Category options ***********/
///////////// Category custom Thumbnail
//add extra fields to category edit form hook
add_action('init','chi_taxonomy_fields',101);

function chi_taxonomy_fields() {
	$taxes = get_taxonomies(array('public'=>'true'));
	
	foreach($taxes as $tax) {
		add_action ( $tax.'_add_form_fields', 'extra_taxonomy_fields_add');
		add_action ( $tax.'_edit_form_fields', 'extra_taxonomy_fields_edit');
		add_action ( 'edited_'.$tax, 'save_extra_taxonomy_fields',10,2);
		add_action ( 'create_'.$tax, 'save_extra_taxonomy_fields',10,2);
	}
}


//add extra fields to taxonomy edit form callback function
function extra_taxonomy_fields_edit( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
	$tax = $tag->taxonomy;
    $cat_meta = get_option( "chi_term_setting_1_".$tax."_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Taxonomy Image Url'); ?></label></th>
<td>
<input type="text" name="chi_term_setting_1_[img]" id="chi_term_setting_1_[img]" size="40" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>">
            <p><span class="description"><?php _e('The Taxonomy Thumbnail URL - please use relative path like /wp-content/..path_to_image/image.jpg'); ?></span></p>
        </td>
</tr>
<tr class="form-field">
		<th scope="row"><label><?php _e('Display no header image?'); ?></th>
		<td>
		<input style="width:20px;" type="checkbox" size="50" name="chi_term_setting_1_[dpn]" value="1" <?php if($cat_meta['dpn']==1) {?>checked="checked"<?php } ?> /></p>
		<p><span class="description"><?php _e('If this is checked, no header image will be displayed.'); ?></span></p></td>
</tr>
<?php
}

// add extra fields to the taxonomy add function
function extra_taxonomy_fields_add($tag) {
    $t_id = $tag->term_id;
	$tax = $tag->taxonomy;
    $cat_meta = get_option( "chi_term_setting_1_".$tax."_$t_id");
	?>
	<div class="form-field">
		<label><?php _e('Taxonomy Image Url'); ?></label>
		<input type="text" name="chi_term_setting_1_[img]" size="40" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
		<p><span class="description"><?php _e('The Taxonomy Thumbnail URL - please use relative path like /wp-content/..path_to_image/image.jpg'); ?></span></p>
	</div>
	
	<div class="form-field">
		<p><label><?php _e('Display no header image?'); ?></label>
		<input style="width:20px;" type="checkbox" size="50" name="chi_term_setting_1_[dpn]" value="1" <?php if($cat_meta['dpn']==1) {?>checked="checked"<?php } ?> /></p>
		<p><span class="description"><?php _e('If this is checked, no header image will be displayed.'); ?></span></p>
	</div>
	
		<?php
}
   // save taxonomy extra fields callback function
function save_extra_taxonomy_fields( $term_id, $tt_id ) {
	
	// get the taxonomy of this term
	global $wpdb;
	$tax = $wpdb->get_var("SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_taxonomy_id=$tt_id");
	
    if ( isset( $_POST['chi_term_setting_1_'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "chi_term_setting_1_".$tax."_$t_id");
        $cat_keys = array_keys($_POST['chi_term_setting_1_']);
		
        foreach ($cat_keys as $key){
        	if (isset($_POST['chi_term_setting_1_'][$key])){
                $cat_meta[$key] = $_POST['chi_term_setting_1_'][$key];
            }
        }
		
		if($_POST['chi_term_setting_1_']['dpn']!=1) {
			$cat_meta['dpn'] = 0;
		}
		
        //save the option array
        update_option( "chi_term_setting_1_".$tax."_$t_id", $cat_meta );
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
	$display_nothing = false;
	$final = false;
	
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
		
		if(is_category()) {
			$cat_image_settings = get_option('chi_term_setting_1_category_'.get_query_var('cat'));
			if($cat_image_settings["dpn"]==1) {
				$display_nothing = true;
			} else {
				$header_image_url = $cat_image_settings["img"];
			}
			if($header_image_url=="") {
				$header_image_url = $urls["chi_url_category_default"];
			}
			
		} else if(is_tag()) {
			$cat_image_settings = get_option('chi_term_setting_1_post_tag_'.get_query_var('tag_id'));
			if($cat_image_settings["dpn"]==1) {
				$display_nothing = true;
			} else {
				$header_image_url = $cat_image_settings["img"];
			}
			if($header_image_url=="") {
				$header_image_url = $urls["chi_url_tag_default"];
			}
		} else if(is_date()) {
			$header_image_url= $urls["chi_url_date"];
		} else if(is_author()) {
			$header_image_url = $urls["chi_url_author_default"];
		} else if(is_tag()) {
			$header_image_url = $urls["chi_url_tag_default"];
		} else if(is_tax()) {
			$taxonomy = get_query_var('taxonomy');
			$term = get_query_var($taxonomy);
			$term_info = get_term_by('slug',$term,$taxonomy);
			
			$cat_image_settings = get_option('chi_term_setting_1_'.get_query_var('taxonomy').'_'.$term_info->term_id);
			
			if($cat_image_settings["dpn"]==1) {
				$display_nothing = true;
			} else {
				$header_image_url = $cat_image_settings["img"];
			}
			if($header_image_url=="") {
				$header_image_url = $urls["chi_url_tax_default"];
			}
		}
		if($header_image_url=="") {
			$header_image_url = $urls['chi_url_archive_default'];
		}
	} else if(is_single()) {
		global $post;
		$single_image_url=get_post_meta($post->ID,"chi_post_setting_1",true);
		
		if(get_post_meta($post->ID,"chi_post_setting_2",true)==1) {
			$display_nothing = true;
		}
		
		if($single_image_url!="") {
			$header_image_url = $single_image_url;
		} else {
			$header_image_url = $urls['chi_url_single_default'];
		}
	} else if(is_page()) {
		global $post;
		$single_image_url=get_post_meta($post->ID,"chi_post_setting_1",true);
		
		if(get_post_meta($post->ID,"chi_post_setting_2",true)==1) {
			$display_nothing = true;
		}
		
		if($single_image_url!="") {
			$header_image_url = $single_image_url;
		} else {
			$header_image_url = $urls['chi_url_page_default'];
		}
	}

	if($header_image_url=="" && $urls['chi_display_nothing']!=1) {
		$header_image_url=$urls['chi_url_global_default'];
	}
	
	if($display_nothing===true) {
		$header_image_url="";
	}
	
	if($header_image_url!="") { ?>
		<div class="chi_display_header" style="height:<?php echo $height;?>px; width:<?php echo $width;?>px; background-image:url('<?php echo $header_image_url; ?>');"></div>
	<?php
		}
	}
?>