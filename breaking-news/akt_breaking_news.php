<?php
/*
 * Plugin Name:Breaking News
 * Plugin URI:mailto:avinash.tripathi7@gmail.com
 * Description:This Plugin is used for showing breaking news on the header of website.
 * Author:Avinash AKT
 * Author URI:mailto:avinash.tripathi7@gmail.com
 * Version: 1.0
*/

if ( ! class_exists( 'BreakingNews' ) ) :

class BreakingNews {
	private static $instance = null;
	public $version = '1.0';
	
	public function __construct() {
		$this->define_constants();
		register_activation_hook( __FILE__,  array($this,'bnews_activation' ));
		add_filter("plugin_action_links_".BN_PLUGIN_BASENAME, array($this,'bnews_settings_link') );
		add_action('admin_menu' , array( $this, 'akt_bnews_setting_page'));
		add_action( 'add_meta_boxes', array( $this, 'add_bnews_meta_box' ) );
	 	add_action( 'save_post', array( $this, 'save_bnews_meta_data' ) );
	 	add_action( 'akt_scripts_styles', array( $this, 'register_bnews_scripts' ) );
	 	add_action( 'wp_head',array($this,'show_bnews_box'),30);
	}
	/**** Defining Constants ***/
	private function define_constants() {
		define( 'BN_PLUGIN_FILE', __FILE__ );
		define( 'BN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'BN_VERSION', $this->version );
		define( 'BN_PLUGIN_PATH', plugin_dir_url( __FILE__ ));		
	}
	public function bnews_activation() {
		/***Default Options Value ***/
		update_option("bn_title","Breaking News");
		update_option("bn_tcolor","ffffff");
		update_option("bn_bcolor","000000");
		update_option("bnews_post","");
		update_option("bnews_post_title","");
		update_option("bnews_expiry_date","");
		update_option("bnews_padding_top","0");
		update_option("bnews_padding_left","0");
		update_option("bnews_padding_down","0");
		update_option("bnews_padding_right","0");
		update_option("bnews_margin_top","0");
		update_option("bnews_margin_left","0");
		update_option("bnews_margin_down","0");
		update_option("bnews_margin_right","0");
	}
	/****Plugin page Settings link***/
	public function bnews_settings_link($links) { 
	  $settings_link = '<a href="options-general.php?page=breakingnews-settings.php">Settings</a>'; 
	  array_unshift($links, $settings_link); 
	  return $links; 
	}
	/****Setting Page Menu ****/
	public function akt_bnews_setting_page(){
		add_submenu_page('options-general.php','View Setting', 'Breaking News Settings', 'manage_options', 'breakingnews-settings', array($this,'bnews_setting_page'));
	}
	/*** Setting page *****/
	public function bnews_setting_page(){
		require_once ("breakingnews-settings.php");
	}
	/*****Adding Meta box ******/
	public function add_bnews_meta_box(){
		add_meta_box(   'cruise-information', 'Breaking News Information',  array( $this,'add_bnews_box'), "post", 'normal', 'high');
		
	}	 
	/***** Registering the required scripts and style*******/	 
	public function register_bnews_scripts() {
		wp_register_style( 'jquery-ui-style', BN_PLUGIN_PATH.'assets/css/jquery-ui.min.css');
		wp_enqueue_style('jquery-ui-style');
		//~ wp_enqueue_script('jquery-ui-datepicker');
		wp_register_style('jquery-timepicker-style' , BN_PLUGIN_PATH. 'assets/css/jquery-ui-timepicker-addon.min.css');
		wp_enqueue_style('jquery-timepicker-style');
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui' ,  BN_PLUGIN_PATH. 'assets/js/jquery-ui.min.js',  array('jquery' ));	
		wp_enqueue_script('jquery-time-picker' ,  BN_PLUGIN_PATH. 'assets/js/jquery-ui-timepicker-addon.min.js',  array('jquery' ));	
		wp_enqueue_script('jquery-time-picker-slider' ,  BN_PLUGIN_PATH. 'assets/js/jquery-ui-sliderAccess.js',  array('jquery' ));			
	}
	/******* Meta box in Post*******/
	public function add_bnews_box($post){
		$bnews_post = get_option("bnews_post");
		$bnews_post_title = get_option("bnews_post_title");
		$bnews_expiry_date = get_option("bnews_expiry_date");
		if($bnews_expiry_date)
			$bnews_expiry_date = date("d-m-Y H:m",$bnews_expiry_date);
		wp_nonce_field( 'bnews_nonce', 'add_bnews_nonce' );
		do_action("akt_scripts_styles");
		?>
		<div class="inside">
			<p>
				<label for="bnews_post">Make this post breaking news <input type="checkbox" id="bnews_post" name="bnews_post" value="<?= $post->ID ?>" <?php if($post->ID==$bnews_post) echo "checked='checked'"; ?> /> </label>
			</p>
			<p>
				<label for="bnews_post_title">Custom Title</label>
				<input type="text" class="large-text" name="bnews_post_title" <?php if($post->ID==$bnews_post) echo "value='".$bnews_post_title."'"; ?> placeholder="Custom Title" /><br>
				<span class="howto">If this empty, then post title is used.</span>
			</p>
			<p>
				<label for="bnews_post_title">Expiry Date</label>
				<input type="text" name="bnews_expiry_date" id="bnews_expiry_date" <?php if($post->ID==$bnews_post) echo "value='".$bnews_expiry_date."'"; ?> autocomplete="off" readonly /><br>
				<span class="howto">After this date and time breaking news will not be shown.</span>
			</p>
		</div>	
		<script>  
			jQuery(document).ready(function() {	
				jQuery('#bnews_expiry_date').datetimepicker({
					changeMonth: true,
					changeYear: true,
					dateFormat : 'dd-mm-yy',
					minDate: "-1",
				});	
			});
		</script>
		<?php
	}
	/******** Saving Meta Data ********/
	public function save_bnews_meta_data($post_id){
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;

		if ( !current_user_can( 'edit_post', $post_id ) )
			  return $post_id;

		if (isset( $_POST['add_bnews_nonce']) && wp_verify_nonce( $_POST['add_bnews_nonce'], 'bnews_nonce' ) ){
			$bnews_post = (isset($_POST['bnews_post'])? ($_POST['bnews_post']): "");
			$bnews_post_title = (isset($_POST['bnews_post_title'])? ($_POST['bnews_post_title']): "");
			$bnews_expiry_date = (isset($_POST['bnews_expiry_date'])? (strtotime($_POST['bnews_expiry_date'])): "");
			update_option("bnews_post",$bnews_post);
			update_option("bnews_post_title",$bnews_post_title);
			update_option("bnews_expiry_date",$bnews_expiry_date);
		}
		return;
	}
	/******* Showing Breaking News on Frontend*********/
	public function show_bnews_box(){		
		$bnews_post = get_option("bnews_post");
		$bnews_expiry_date = get_option("bnews_expiry_date");
		if($bnews_post ){
			if(!empty($bnews_expiry_date) && time() > $bnews_expiry_date){
				return;
			}
			$bn_title = get_option( 'bn_title' );
			$bn_tcolor = get_option( 'bn_tcolor' );
			$bn_bcolor = get_option( 'bn_bcolor' );
			$bnews_post_title = get_option("bnews_post_title");
			?>
			<style>
				.breaking-news-box{float:left;background-color:#<?php echo $bn_bcolor ?>;color:#<?php echo $bn_tcolor ?>; width:100%;padding:<?php echo $bnews_padding_top ?>px <?php echo $bnews_padding_left ?>px <?php echo $bnews_padding_down ?>px <?php echo $bnews_padding_right ?>px;margin:<?php echo $bnews_margin_top ?>px <?php echo $bnews_margin_left ?>px <?php echo $bnews_margin_down ?>px <?php echo $bnews_margin_right ?>px;position: absolute;z-index: 99;}
				.bn-inner {padding: 8px;}
				.bn-inner .title-news {font-weight: bold;}
			</style>
			<div class="breaking-news-box" >
				<div class="container">
					<div class="row">
						<div class="bn-inner">
							<span class="title-news">
								<?php if($bn_title) echo $bn_title  ?> : 
							</span>
							<span class="post-news" >
								<?php 
									echo "<a href='".get_permalink($bnews_post)."' title='".(($bnews_post_title)? $bnews_post_title : get_the_title($bnews_post))."' >".(($bnews_post_title)? $bnews_post_title : get_the_title($bnews_post))."</a>";
								 ?>
							</span>	
						</div>
					</div>
				</div>
				<div style="clear:both"></div>			
			</div>
			<?php				
		}		
	}
}
	
endif;

return new BreakingNews();
