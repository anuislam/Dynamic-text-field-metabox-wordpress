<?php

/*
Plugin Name: Anuislam Extend Quick Edit
Plugin URI: https://www.facebook.com/anuislam.shohag.3
Description: Extends the quick-edit interface to display additional post meta
Version: 1.0.0
Author: Anuislam
Author URI: https://www.facebook.com/anuislam.shohag.3
*/

add_action( 'plugins_loaded', 'as_quick_plugin_override' );

function as_quick_plugin_override() {
    add_action('manage_post_posts_columns', 'as_quick_add_custom_admin_column', 10, 1);
    add_action('quick_edit_custom_box', 'as_quick_display_quick_edit_custom', 10, 2);
    add_action('manage_posts_custom_column', 'as_quick_manage_custom_admin_columns', 10, 2);
    add_action('admin_enqueue_scripts', 'as_quick_enqueue_admin_scripts_and_styles'); 
    add_action('add_meta_boxes', 'as_quick_add_metabox_to_posts', 10, 2);
    add_action('admin_menu', 'as_quick_add_admin_menu', 10, 2);
    add_action('admin_head', 'as_quick_add_admin_head', 10, 2);
    add_action('save_post', 'as_quick_save_post', 10, 1);
}

function as_quick_add_admin_head(){
	$data = get_option('as_quick_meta_option');	
?>

<meta name="as_quick_json_data" content='<?php echo json_encode($data, true); ?>'>

<?php
}
function as_quick_add_admin_menu(){
	add_menu_page( 
		'Add meta box',
		'Add meta box',
		'manage_options',
		'add-meta-box', 
		'as_quick_add_admin_menu_page_func'
		);
}
function as_quick_add_metabox_to_posts(){
    add_meta_box(
        'as_quick_additional_meta_box',
        'Additional Info',
        'as_quick_display_metabox_output',
        'post'
    );
}

function as_quick_enqueue_admin_scripts_and_styles(){
	 wp_enqueue_style( 'as-quick-style', plugin_dir_url(__FILE__) . 'css/quick-edit-style.css' ); 
	 wp_register_script( 'quick-edit-js', plugin_dir_url(__FILE__) . 'js/quick-edit-script.js', array('jquery','inline-edit-post' ));
	 wp_enqueue_script('jquery');
	 wp_enqueue_script('quick-edit-js');

}
function as_quick_add_custom_admin_column($columns){
    $new_columns = array();
    $new_columns['as_quick_post_meta'] = 'Meta';
    return array_merge($columns, $new_columns);
}

function as_quick_manage_custom_admin_columns($column_name, $post_id){
	if($column_name == 'as_quick_post_meta'){
		$data = get_option('as_quick_meta_option');		
		?>
	<?php if (empty($data) === false): ?>
		<?php foreach ($data as $key => $value): ?>
			<span id="<?php echo $value; ?>_<?php echo $post_id; ?>" style="display: none;"><?php echo get_post_meta($post_id, $value, true); ?></span>
		<?php endforeach ?>
	<?php endif ?>

		<?php
	}
}

function as_quick_display_quick_edit_custom($column){
	wp_nonce_field('as_quick_post_metadata', 'as_quick_post_metadata');
	if($column == 'as_quick_post_meta'){	
	$data = get_option('as_quick_meta_option');	
?>

<div class="as-quick-container">
	<?php if (empty($data) === false): ?>
		<?php foreach ($data as $key => $value): ?>
			<div class="as-quick-quform-group">
				<label for="as_quick_<?php echo $value; ?>"><?php echo $value; ?></label>
				<input type="text" class="as-quick-form-control widefat" id="as_quick_<?php echo $value; ?>" name="<?php echo $value; ?>" value="" >
			</div>
		<?php endforeach ?>
	<?php endif ?>
</div>

<?php
	}
}

function as_quick_display_metabox_output($post){
	wp_nonce_field('as_quick_post_metadata', 'as_quick_post_metadata');
	$medicaid 	= get_post_meta( (int)$post->ID, 'MEDICAID', true );
	$POOR 	  	= get_post_meta( (int)$post->ID, 'POOR', true );
	$SNAP 	  	= get_post_meta( (int)$post->ID, 'SNAP', true );
	$State 		= get_post_meta( (int)$post->ID, 'State', true );
	$TANF 		= get_post_meta( (int)$post->ID, 'TANF', true );
	$UI 		= get_post_meta( (int)$post->ID, 'UI', true );

	$data = get_option('as_quick_meta_option');
?>

<div class="as-quick-container">
	<?php if (empty($data) === false): ?>
		<?php foreach ($data as $key => $value): ?>
			<div class="as-quick-quform-group">
				<label for="<?php echo $value; ?>"><?php echo $value; ?></label>
				<input type="text" class="as-quick-form-control widefat" id="<?php echo $value; ?>" name="<?php echo $value; ?>" value="<?php echo get_post_meta( (int)$post->ID, $value, true ); ?>" >
			</div>
		<?php endforeach ?>
	<?php endif ?>	
</div>

<?php
}

function as_quick_save_post($post_id){	
	if (empty($_POST) === false) {
		$nonce = @$_REQUEST['as_quick_post_metadata'];
		if ( ! wp_verify_nonce( $nonce, 'as_quick_post_metadata' ) ) {
		     die( 'Security check' ); 
		} else {
		    if (current_user_can( 'edit_post', $post_id )) {
		    	$data = get_option('as_quick_meta_option');
		    	if (empty($data) === false) {
		    		foreach ($data as $key => $value) {
				    	if (empty($_POST[$value]) === false) {
				    		update_post_meta( (int)$post_id, $value, as_quick_sanitize($_POST[$value]) );
				    	}
		    		}
		    	}
		    }
		}
	}

}


function as_quick_sanitize($data){
	$data = trim($data);
	$data = htmlentities(strip_tags($data));
	return sanitize_text_field($data);
}

//get_post_meta( (int)$post->ID, 'MEDICAID', true );
//get_post_meta( (int)$post->ID, 'POOR', true );
//get_post_meta( (int)$post->ID, 'SNAP', true );
//get_post_meta( (int)$post->ID, 'State', true );
//get_post_meta( (int)$post->ID, 'TANF', true );
//get_post_meta( (int)$post->ID, 'UI', true );


function as_quick_add_admin_menu_page_func(){
	$as_save = false;
	if (empty($_POST['as_submit']) === false) {
		if (empty($_POST['as_name']) === false) {
			$as_save = as_quick_add_admin_menu_init($_POST['as_name']);
		}
	}

	$data = get_option('as_quick_meta_option');

?>
<div class="wrap">
	<h2>Add Meta Box</h2>
<?php if ($as_save === true): ?>
	<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
	<p><strong>Meta options saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

<?php endif ?>

	<div class="as_add_meta_box_container">
		<div class="as_add_meta_box_row">
			<form action="<?php echo home_url( 'wp-admin/admin.php?page=add-meta-box' ); ?>" id="as_add_meta_box_form" method="post">
				<ul>
					<?php if (empty($data) === false): ?>
						<?php foreach ($data as $key => $value): ?>
					<li>
						<span class="as_label">Name</span>
						<input type="text" class="widefat" name="as_name[]" value="<?php echo $value; ?>">
						<span class="button button-default" onclick="as_remove_meta_box_item(this)">Remove</span>
					</li>
						<?php endforeach ?>
					<?php else: ?>
					<li>
						<span class="as_label">Name</span>
						<input type="text" class="widefat" name="as_name[]" value="">
						<span class="button button-default" onclick="as_remove_meta_box_item(this)">Remove</span>
					</li>
					<?php endif ?>
				</ul>
				<input type="submit" class="button button-primary" id="as_add_meta_box_submit" value="Save" name="as_submit" >
				<span class="button button-primary" id="as_add_meta_box_add_new" >Add New</span>
			</form>			
		</div>
	</div>
</div>
<?php
}

function as_quick_add_admin_menu_init($as_name){
	$data = array();
	foreach ($as_name as $key => $value) {
		if (empty($value) === false) {
			$data[] = str_replace(array(' ', '-'), '_', as_quick_sanitize($value))  ;
		}				
	}
	update_option('as_quick_meta_option', $data );
	return true;
}