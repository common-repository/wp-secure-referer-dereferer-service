<?php
/*
Plugin Name: WP Secure Referer | Dereferer Service
Plugin URI: http://secure-referrer.biz/wp-plugin.html
Description: Automatically changes all external links on the blog to redirect through various anonymization services, used to hide the source (referer) of traffic.
Version: 1.0.1
Author: Secure-Referrer.biz
Author URI: http://secure-referrer.biz/
*/

$__secure_referer = new secure_referer();

add_action('wp_footer', array($__secure_referer,'add_secure_referer_js'));
add_action('admin_menu', array($__secure_referer,'secure_referer_menu'));
add_action('wp_enqueue_scripts', array($__secure_referer, 'secure_referer_scripts'));
add_action('init', array($__secure_referer, 'secure_referer_init'));
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain('wpbf_trans', 'wp-content/plugins/'.$plugin_dir);

register_activation_hook(__FILE__, array($__secure_referer,'secure_referer_activate'));
register_deactivation_hook(__FILE__, array($__secure_referer,'secure_referer_deactivate'));

final class secure_referer {
	
	public function secure_referer_init(){
		wp_register_script('secure_referer-secure_referer', WP_PLUGIN_URL . '/wp-secure-referer-dereferer-service/scripts/url-replace-a.js');
	}
	
	public function secure_referer_activate(){
		add_option("secure_referer_service", '1', '', 'yes');
	}
	
	public function secure_referer_deactivate(){
		delete_option("secure_referer_service");
	}
	
	public function secure_referer_menu(){
		add_options_page('WP Blank Referer', 'Secure Referer', 'administrator', 'secure_referer-options', array($this,'secure_referer_options_page'));
	}
	
	public function secure_referer_options_page(){
		if(!empty($_POST['refer_service'])){
			echo '<div class="updated"><p><strong> '. __('Options saved.', 'wpbf_trans'). '</strong></p></div>';	
			update_option("secure_referer_service", $_POST['refer_service']);
		}
			
		echo '<div class="wrap">';
		echo '<h1>'. __('Secure Referer Options', 'wpbf_trans') .'</h1><hr />';
		?>
		<form method="POST" action="">
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="refer_service"><strong><?php _e('Choose Referer Service:', 'wpbf_trans' ); ?></strong></label>
				</th>
				<td>
					<select id="refer_service" name="refer_service">
						<option value="1" <?php if(get_option("secure_referer_service") == 1){ echo "selected=selected"; } ?>>Secure-Referrer.biz</option>
					</select>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wpbf_trans' ); ?>" />
		</p>
		<?php
		echo '</div>';
	}
	
	public function secure_referer_scripts(){
		if(get_option("secure_referer_service") == 1){
			wp_enqueue_script('secure_referer-secure_referer');
		}
	}
	
	public function add_secure_referer_js(){
		echo '<script type="text/javascript"><!--
		protected_links = "";

		auto_anonyminize();
		//--></script>';
	}
}
?>