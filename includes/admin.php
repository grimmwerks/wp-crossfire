<?php
if ( !class_exists('WP_Crossfire_Admin') ) :

class WP_Crossfire_Admin{
	function __construct(){
		add_action( 'add_meta_boxes', array(&$this, 'post_meta_boxes') );

		add_action( 'init', array(&$this, 'register_ricochet') );

		//add filter to ensure the text Ricochet, or ricochet, is displayed when user updates a ricochet
		add_filter( 'post_updated_messages', array(&$this, 'ricochet_updated_messages') );
	}

	function register_ricochet(){
		$labels = array(
			'name'                  => _x('Ricochets', 'post type general name', 'wp-crossfire'),
			'singular_name'         => _x('Ricochet', 'post type singular name', 'wp-crossfire'),
			'add_new'               => __('Add New', 'wp-crossfire'),
			'add_new_item'          => __('Add New Ricochet', 'wp-crossfire'),
			'edit_item'             => __('Edit Ricochet', 'wp-crossfire'),
			'new_item'              => __('New Ricochet', 'wp-crossfire'),
			'view_item'             => __('View Ricochet', 'wp-crossfire'),
			'search_items'          => __('Search Ricochets', 'wp-crossfire'),
			'not_found'             => __('No ricochets found', 'wp-crossfire'),
			'not_found_in_trash'    => __('No ricochets found in Trash', 'wp-crossfire'),
			'parent_item_colon'     => '',
			'menu_name'             => 'Ricochets'
		);

		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'register_meta_box_cb'  => array(&$this, 'ricochet_meta_boxes'),
			'rewrite'               => false,
			'capability_type'       => 'post',
			'hierarchical'          => false,
			'menu_position'         => 5,
			'menu_icon'             => WPCROSSFIRE_URL . '/images/menu_icon.png'
		);

		register_post_type( 'ricochet', $args );

		remove_post_type_support( 'ricochet', 'editor' );
		remove_post_type_support( 'ricochet', 'title' );
	}

	function ricochet_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['ricochet'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __('Ricochet updated. <a href="%s">View post</a>'), esc_url( get_permalink($post_ID) ) ),
			2  => __('Custom field updated.'),
			3  => __('Custom field deleted.'),
			4  => __('Ricochet updated.'),
			5  => isset($_GET['revision']) ? sprintf( __('Ricochet restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __('Ricochet published. <a href="%s">View post</a>'), esc_url( get_permalink($post_ID) ) ),
			7  => __('Ricochet saved.'),
			8  => sprintf( __('Ricochet submitted. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9  => sprintf( __('Ricochet scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview post</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Ricochet draft updated. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		return $messages;
	}

	function ricochet_meta_boxes() {
		
	}

	function post_meta_boxes() {
		add_meta_box(
			'create_ricochet',
			__('Crossfire Ricochet', 'wp-crossfire'),
			array(&$this, 'crossfire_ricochet_meta_box'),
			'post',
			'side',
			'core'
		);
	}

	function crossfire_ricochet_meta_box(){
		
	}
}

$wp_crossfire_admin = new WP_Crossfire_Admin();

endif;
?>