<?php

/*
Plugin Name: OBDIY Custom Shortcodes
Plugin URI: https://github.com/JasonDodd511/obdiy-custom-shortcodes.git
Description: A collection of useful custom shortcodes.
Version: 1.0.0
Author: Jason Dodd
Author URI:
License: GPL2
GitHub Plugin URI: https://github.com/JasonDodd511/obdiy-custom-shortcodes.git
GitHub Branch:     master
GitHub Languages:
*/

/**
 * Login/Logout Link
 *
 * Meant to be used in conjuction with the "Shortcode In Menus" plugin
 * to create a WP Account longin/logout link that you place in one
 * or more menus.
 *
 * use: [login-logout-link]
 */

function loginLogoutLink(){
	if ( is_user_logged_in() ) {
		$link = "<a href='/my-account/customer-logout'>Log Out</a>";
	} else {
		$link = "<a href='/my-account'>Log In</a>";
	}
	return $link;
}

add_shortcode( 'login-logout-link' , 'loginLogoutLink' );

/**
 * 'My Account' Admin Link
 *
 * Meant to be used in conjuction with the "Shortcode In Menus" plugin
 * to create a WooCommerce My Account link that you place in one
 * or more menus.
 *
 * use: [my-account-link]
 */

function myAccountLink(){
	global $current_user;
	get_currentuserinfo();

	if ( is_user_logged_in() ) {
		$link = "<a href='/my-account'>Logged in as: " . $current_user->user_firstname . " " . $current_user->user_lastname . "</a>";
	} else {
		$link = '';
	}

	return $link;
}

add_shortcode( 'my-account-link' , 'myAccountLink' );

/**
 * Hide content from members who own specific memberships
 *
 * Used to hide content from members who have access to a
 * particular membership.
 *
 * use:[wcm_hide plans="membership1-slug,membership2-slug"]content to hide goes here[/wcm_hide]
 * Note: you can add multiple memberships separated by a comma
 */
function obdiy_wcmHide($atts, $content = null ) {

	extract(shortcode_atts(array(
		'plans' => null,
	), $atts));

	$plans = explode(",",$atts['plans']);
	$has_access = false;

	foreach($plans as $plan){
		if(wc_memberships_is_user_active_member($user_id, $plan)) {$has_access = true;}
	}

	if(!$has_access){
		return do_shortcode($content);
	}
	return '';
}

add_shortcode( 'wcm_hide' , 'obdiy_wcmHide' );

/**
 * Display information for logged in user
 *
 * Use these shortcodes to customize messages and links that are built using the front end editor.
 */
function obdiy_user_shortcode($atts, $content){

	if (!is_user_logged_in()) return '';

	$current_user = wp_get_current_user();
	return $current_user->user_login;
}
add_shortcode ('currentuser_username' , 'obdiy_user_shortcode');

function obdiy_email_shortcode ($atts, $content){

	if (!is_user_logged_in()) return '';

	$current_user = wp_get_current_user();
	return $current_user->user_email;
}
add_shortcode ('currentuser_email' , 'obdiy_email_shortcode');

function obdiy_firstname_shortcode ($atts, $content){

	if (!is_user_logged_in()) return '';

	$current_user = wp_get_current_user();
	return $current_user->user_firstname;
}
add_shortcode ('currentuser_firstname' , 'obdiy_firstname_shortcode');

function obdiy_lastname_shortcode ($atts, $content){

	if (!is_user_logged_in()) return '';

	$current_user = wp_get_current_user();
	return $current_user->user_lastname;
}
add_shortcode ('currentuser_lastname' , 'obdiy_lastname_shortcode');

function obdiy_displayname_shortcode ($atts, $content){

	if (!is_user_logged_in()) return '';

	$current_user = wp_get_current_user();
	return $current_user->display_name;
}
add_shortcode ('currentuser_displayname' , 'obdiy_displayname_shortcode');

function obdiy_id_shortcode ($atts, $content){

	if (!is_user_logged_in()) return '';

	$current_user = wp_get_current_user();
	return $current_user->ID;
}
add_shortcode ('currentuser_id' , 'obdiy_id_shortcode');

/**
 * Display get variables using shortcode
 *
 * Use these shortcodes to customize messages and links that are built using the front end editor.
 */
function obdiy_get_ep_id_shortcode ($atts, $content){
	$ep_id = $_GET['ep_id'];

	if($ep_id) {
		return '#' . $ep_id;
	}
	else {
		return '#';
	}
}
add_shortcode ('ep_id' , 'obdiy_get_ep_id_shortcode');

function obdiy_get_ep_name_shortcode ($atts, $content){
	$ep_name = $_GET['ep_name'];

	if($ep_name) {
		return urldecode($ep_name);
	}
	else {
		return '';
	}
}
add_shortcode ('ep_name' , 'obdiy_get_ep_name_shortcode');

/**
 * Display Qty Remaining In Stock
 *
 * Displays the number of remaining items in stock given a product_id.
 *
 * Use: [show_qty id="product_id"]
 */
function obdiy_show_qty( $atts ) {
	$attributes = shortcode_atts( array(
		'id' => 0
	), $atts );

	$pf = new WC_Product_Factory();
	$product = $pf->get_product($attributes['id']);

	$quantity = $product->get_stock_quantity( );
	return $quantity;
}
add_shortcode( 'show_qty', 'obdiy_show_qty' );
