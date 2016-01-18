<?php
/*
Plugin Name: WC Shipping
Plugin URI: http://nabtron.com/wc-shipping/
Description: Removes shipping options from cart total when free shipping threshold is achieved. 
Tags: woocommerce, shipping, free, threshold, hide, options
Version: 1.0
Author: Nabtron
Author URI: http://nabtron.com
Min WP Version: 4.2
Max WP Version: 4.4.1
*/
?>
<?php

// Update routines
if ('insert' == $_POST['action_nabwcshipping']) {
        update_option("nabwcshipping_show",$_POST['nabwcshipping_show']);
}

if (!class_exists('nabwcshipping_main')) {
	class nabwcshipping_main {
		function __construct(){
			add_action('admin_menu', 'nabwcshipping_description_add_menu');
		}
	}


	function nabwcshipping_description_option_page() {
	$nabwcshipping_urltosubmit = str_replace('&updated=true', '', $_SERVER["REQUEST_URI"]);
	?>

	<!-- Start Options Admin area -->
	<div class="wrap">
		<h2>WooCommerce Shipping Options</h2>
		<div style="margin-top:20px;">
			<div style="width:45%;float:left;padding-left:10px;">
			<form method="post" action="<?php echo $nabwcshipping_urltosubmit; ?>&amp;updated=true">
				<p><strong>Settings</strong></p>
				<br>
				<input type="radio" name="nabwcshipping_show" value="0" id="0" <?php checked(0,get_option( 'nabwcshipping_show' ));?>>
				<label for="0">Disable (Show shipping options)</label>
				<br /><br />
				<input type="radio" name="nabwcshipping_show" value="1" id="1" <?php checked(1,get_option( 'nabwcshipping_show' ));?>>
				<label for="1">Enable (hide shipping options from cart when free shipping threshold met)</label>
				<br /><br />
				<br>
				<p class="submit_nabwcshipping">
					<input name="submit_nabwcshipping" class="button-primary" type="submit" id="submit_nabwcshipping" value="Save changes">
					<input class="submit" name="action_nabwcshipping" value="insert" type="hidden" />
				</p>
			</form>
			</div>
			<div style="width: 48%;float:right;text-align:right;padding-top: 50px;">
				<p>
									<span style="color: #F00;font-weight:bold;font-size:1.2em;">Need a WordPress Developer?</span>
<br /><br /><br /><br />
					<a href="http://nabtron.com/hire-me/"><span style="padding:40px;font-weight:bold;color:white;background-color:#06C;font-size: 2em;">Hire Me</span></a>
				</p>
			</div>
		</div>
		<div style="clear:both;"></div>
		<br />
		<br />
		<hr />
		<center>
			<h4>Developed by <a href="http://nabtron.com/" target="_blank">Nabtron</a>.</h4>
		</center>
	</div>

<?php
	} // End function nabwcshipping_description_option_page()

	// Admin menu Option
	function nabwcshipping_description_add_menu() {
		add_options_page('WC Shipping Options', 'WC Shipping', 'manage_options', __FILE__, 'nabwcshipping_description_option_page');
	}
}
//instantiate the class
if (class_exists('nabwcshipping_main')) {
	$nabwcshipping_main = new nabwcshipping_main();
}

$show_wc_shipping_status = get_option( 'nabwcshipping_show' );

// 0 means off
if($show_wc_shipping_status == '0'){

}

// 1 means on
if($show_wc_shipping_status == '1'){
	add_filter( 'woocommerce_package_rates', 'nabwcshipping_hide_shipping_when_free_is_available', 10, 2 );
}
 
/**
 * Hide shipping rates when free shipping is available
 *
 * @param array $rates Array of rates found for the package
 * @param array $package The package array/object being shipped
 * @return array of modified rates
 */
function nabwcshipping_hide_shipping_when_free_is_available( $rates, $package ) {
 	
 	// Only modify rates if free_shipping is present
  	if ( isset( $rates['free_shipping'] ) ) {
  	
  		// To unset a single rate/method, do the following. This example unsets flat_rate shipping
  		unset( $rates['flat_rate'] );
  		
  		// To unset all methods except for free_shipping, do the following
  		$free_shipping          = $rates['free_shipping'];
  		$rates                  = array();
  		$rates['free_shipping'] = $free_shipping;
	}
	
	return $rates;
}
?>
