<?php

if (function_exists('register_sidebar'))
{
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
}

function is_tree($pid) {      // $pid = The ID of the page we're looking for pages underneath
	global $post;         // load details about this page
	if(is_page()&&($post->post_parent==$pid||is_page($pid))) 
               return true;   // we're at the page or at a sub page
	else 
               return false;  // we're elsewhere
};

function remove_more_jump_link($link) { 
$offset = strpos($link, '#more-');
if ($offset) {
$end = strpos($link, '"',$offset);
}
if ($end) {
$link = substr_replace($link, '', $offset, $end-$offset);
}
return $link;
}
add_filter('the_content_more_link', 'remove_more_jump_link');

function remove_dashboard_widgets(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_addthis']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['cms_tpv_dashboard_widget']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['yst_db_widget']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['cms_tpv_dashboard_widget_page']);
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

add_theme_support( 'post-thumbnails' );

add_image_size( 'single-post-picture', 400, 9999 ); // For zoom thumbnails
add_image_size( 'featured-slider-picture', 630, 246, true); // For featured slider
add_image_size( 'zoom_full_size', 3000, 999999); // For zoom full size

function my_init() {
 
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		//wp_deregister_script('jquery');
		//wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', false, '1.3.2');
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'mailingListSignup', get_bloginfo('template_url') . '/js/mailingListSignup.js');
		wp_enqueue_script( 'validate', get_bloginfo('template_url') . '/js/jquery.validate.min.js');
		wp_enqueue_script( 'colortip', get_bloginfo('template_url') . '/colortip-1.0/colortip-1.0-jquery.js');
		wp_enqueue_script( 'tutorialSignup', get_bloginfo('template_url') . '/js/tutorialSignup.js');
		wp_enqueue_script( 'multTutSignup', get_bloginfo('template_url') . '/js/multTutSignup.js');
		wp_enqueue_script( 'jqzoom', get_bloginfo('template_url') . '/js/cloud-zoom.1.0.2.min.js');
	} else if (is_admin()) {
		wp_enqueue_script( 'admin_script', get_bloginfo('template_url') . '/js/admin_script.js');
		wp_enqueue_style( 'adminCSS', get_bloginfo('template_url') . '/css/adminCSS.css',false,'1.1','all');
	}
}
add_action('init', 'my_init');

function enqueueMyScripts(){
  // Darkroom Page
    if( is_page('63') ) {
        wp_enqueue_script( 'jQuery UI', get_bloginfo('template_url') . '/js/jquery-ui-1.8.5.custom.min.js');
        wp_enqueue_script( 'darkroomForm', get_bloginfo('template_url') . '/js/darkroomForm.js');
   }
   
   if( is_page('699') ) {
        wp_enqueue_script( 'jQuery UI', get_bloginfo('template_url') . '/js/jquery-ui-1.8.5.custom.min.js');
        wp_enqueue_script( 'darkroomForm', get_bloginfo('template_url') . '/js/darkroomForm.js');
   } 
   
   if( is_page('892') ) {
        wp_enqueue_script( 'memberList', get_bloginfo('template_url') . '/js/memberList.js');
   }
   
   if( is_page('744') ) {
        wp_enqueue_script( 'inductionList', get_bloginfo('template_url') . '/js/inductionList.js');
   }
   
   // Welcome Page
   if( is_page('18') ) {
		wp_enqueue_script( 'nivoSlider', get_bloginfo('template_url') . '/nivoslider/jquery.nivo.slider.pack.js');
		wp_enqueue_script( 'nivoSliderScript', get_bloginfo('template_url') . '/js/nivoSliderScript.js');
   }

  //You can use pretty much any of the WP conditional tags to determine whether to enqueue or not
}

add_action('wp_print_scripts', 'enqueueMyScripts'); 

add_action( 'init', 'film_register' );

function film_register() {
	register_post_type( 'film',
		array(
			'labels' => array(
				'name' => __( 'Films' ),
				'singular_name' => __( 'Film' ),
				'add_new_item' => __( 'Add New Film' )
			),
			'query_var' => "film", // This goes to the WP_Query schema
			'rewrite' => true, 
			'hierarchical' => false, 
			'capability_type' => 'post',
			'public' => true,
			'show_ui' => true,
			'supports' => array( 'title', 'editor', 'comments', 'revisions', 'thumbnail' ),
		)
	);
}

register_taxonomy("format", array("film"), array("hierarchical" => true, "label" => "Formats", "singular_label" => "Format", "rewrite" => true)); 
register_taxonomy("type", array("film"), array("hierarchical" => true, "label" => "Types", "singular_label" => "Type", "rewrite" => true)); 
register_taxonomy("brand", array("film"), array("hierarchical" => true, "label" => "Brands", "singular_label" => "Brand", "rewrite" => true)); 
register_taxonomy("iso", array("film"), array("hierarchical" => true, "label" => "ISO", "singular_label" => "ISO", "rewrite" => true)); 

add_filter("manage_edit-film_columns", "film_edit_columns");
add_action("manage_posts_custom_column",  "film_custom_columns");

function film_edit_columns($columns){
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Product Title",
			"format" => "Format",
			"type" => "Type",
			"brand" => "Brand", 
			"iso" => "ISO"
		);

		return $columns;
}

function film_custom_columns($column){
		global $post;
		switch ($column)
		{
			case "format":
				echo get_the_term_list($post->ID, 'format', '', ', ','');
				break;
			case "type":
				echo get_the_term_list($post->ID, 'type', '', ', ','');
				break;
			case "brand":
				echo get_the_term_list($post->ID, 'brand', '', ', ','');
				break;
			case "iso":
				echo get_the_term_list($post->ID, 'iso', '', ', ','');
				break;
		}
}

function get_user_role() {
	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	return $user_role;
}

function form_shortcode( $atts ) {
	extract(shortcode_atts(array(
	    'name' => '', //Name, email and cid is not included by default as included in login data
	    'email' => '',
	    'cid' => '',
		'notes' => '',
	    'type' => '',
	    'date' => '',
		'time' => '',
		'title' => '',
	), $atts));
	
	$date = strtotime($date);
	
	global $current_user;
	get_currentuserinfo();
	
	if ( is_user_logged_in() ) { 
		$output = '<div id="tutorialSignup" class="signupBox">';

		$output .= '<h3 style="margin-top: 0px; margin-right: 0px; margin-bottom: 5px; margin-left: 0px; text-transform: lowercase; width: 485px; text-decoration: none; font-size: 20px; text-align: left; padding: 0px;"><span style="color: #800000;">'.$title.'</span></h3>';
		
		if ($date){
			$output .= '<div id="inductionInfo">Time: '.date("l jS M", $date).' at '.$time.'. To find the darkroom see the <a href="http://www.union.ic.ac.uk/media/photosoc/darkroom2/map">map</a></div>';
		}
		
		$output .= '<p>Logged in as  <a href="'.get_bloginfo('url').'/author/'.$current_user->user_login.'">'.$current_user->user_firstname.' '.$current_user->user_lastname.'</a></p>';
		$output .= '<form id="tutorialSignupForm" class="signupForm" action="'.get_bloginfo('template_url').'/tutorialSignup.php" method="post">';
		
		if ($name) {
			$output .= '<label for="nameInput">Full Name: </label><input id="nameInput" class="required inputName" name="name" size="22" type="text" /><em>*</em>';
		}
		if ($email) {
			$output .= '<label for="emailInput">Email Address: </label><input id="emailInput" class="required email inputEmail" name="email" size="22" type="text" /><em>*</em>';
		}
		if ($cid) {
			//CID input
		}
		if ($notes){
			$output .= '<label for="formNotes">Comments:  </label><textarea rows="5" cols="80" id="formNotes" name="formNotes" class="required inputForm" minlength="10"></textarea>';
		}
		if ($date){
			$output .= '<input id="date" name="date" type="hidden" value="'.date("Y-m-d" , $date).'" />';
		}
		
		$output .= '<input id="tutorialType" name="tutorialType" type="hidden" value="'.$type.'" />';
		$output .= '<input id="userLogin" name="userLogin" type="hidden" value="'.$current_user->user_login.'" />';
		$output .= '<input id="userEmail" name="userEmail" type="hidden" value="'.$current_user->user_email.'" />';
		$output .= '<input id="userName" name="userName" type="hidden" value="'.$current_user->user_firstname.' '.$current_user->user_lastname.'" />';
		$output .= '<input class="submitButton" type="submit" value="Submit" /><span id="signupLoading"><img src="http://www.union.ic.ac.uk/media/photosoc/wp-content/themes/cupofcoffee/images/ajax-loader.gif" alt="" /> Loading...</span>';
		$output .= '</form>';
	} else {
		$output .= '<i>Sorry you need to be logged in first. Please log in with your Imperial College username password using the form on the left.</i>'; 
	}
	
	$output .= '</div>';
	return $output;

}

add_shortcode('form', 'form_shortcode');

add_filter( 'widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');			

function excerpt($num) {
	$limit = $num+1;
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt)."...";
	echo $excerpt;
}

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {

  //add_options_page('My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options');
	add_submenu_page('edit.php?post_type=film', 'Price+Stock', 'Price+Stock', 'manage_options', 'film-options', 'my_plugin_options' );
  
}

function my_plugin_options() {

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  echo '<div class="wrap">';
  include('filmListDisp.php');
  echo '</div>';

}

add_action('admin_menu', 'jsTemplateDir');

function jsTemplateDir(){
	$template = get_bloginfo('template_directory');
	
	$output = '<script type="text/javascript">';
	$output .= 'templateDir = "'.$template.'";';
	$output .= '</script>';
	
	echo $output;
}

?>
