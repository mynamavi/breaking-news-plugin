<?php
/*
 * Breaking News Setting Page
 *
 * @BreakingNews
 */
if (isset( $_POST['bn_options']) && wp_verify_nonce( $_POST['bn_options'], 'bn_settings_nonce' ) ){
	/*********Saving Setting page Options**********/
	$bn_title = (isset($_POST['bn_title'])? $_POST['bn_title']: "Breaking News:");
	update_option("bn_title",$bn_title);
	$bn_bcolor = ( isset( $_POST['bn_bcolor'] ) ? sanitize_html_class( $_POST['bn_bcolor'] ) : '000000' );
	update_option("bn_bcolor",$bn_bcolor);
	$bn_tcolor = ( isset( $_POST['bn_tcolor'] ) ? sanitize_html_class( $_POST['bn_tcolor'] ) : 'ffffff' );
	update_option("bn_tcolor",$bn_tcolor);
	/***Padding Save***/
	$bnews_padding_top = ( isset( $_POST['bnews_padding_top'] ) ? sanitize_html_class( $_POST['bnews_padding_top'] ) : '0' );
	update_option("bnews_padding_top",$bnews_padding_top);
	$bnews_padding_left = ( isset( $_POST['bnews_padding_left'] ) ? sanitize_html_class( $_POST['bnews_padding_left'] ) : '0' );
	update_option("bnews_padding_left",$bnews_padding_left);
	$bnews_padding_down = ( isset( $_POST['bnews_padding_down'] ) ? sanitize_html_class( $_POST['bnews_padding_down'] ) : '0' );
	update_option("bnews_padding_down",$bnews_padding_down);
	$bnews_padding_right = ( isset( $_POST['bnews_padding_right'] ) ? sanitize_html_class( $_POST['bnews_padding_right'] ) : '0' );
	update_option("bnews_padding_right",$bnews_padding_right);
	/***Margin  Save***/
	$bnews_margin_top = ( isset( $_POST['bnews_margin_top'] ) ? sanitize_html_class( $_POST['bnews_margin_top'] ) : '0' );
	update_option("bnews_margin_top",$bnews_margin_top);
	$bnews_margin_left = ( isset( $_POST['bnews_margin_left'] ) ? sanitize_html_class( $_POST['bnews_margin_left'] ) : '0' );
	update_option("bnews_margin_left",$bnews_margin_left);
	$bnews_margin_down = ( isset( $_POST['bnews_margin_down'] ) ? sanitize_html_class( $_POST['bnews_margin_down'] ) : '0' );
	update_option("bnews_margin_down",$bnews_margin_down);
	$bnews_margin_right = ( isset( $_POST['bnews_margin_right'] ) ? sanitize_html_class( $_POST['bnews_margin_right'] ) : '0' );
	update_option("bnews_margin_right",$bnews_margin_right);

	
	echo '<div class="wrap">
		<div class="updated notice notice-success is-dismissible" id="message"><p>Settings updated</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
	</div>';
}

$bn_title = get_option( 'bn_title' );
$bn_tcolor = get_option( 'bn_tcolor' );
$bn_bcolor = get_option( 'bn_bcolor' );
$bnews_post = get_option("bnews_post");
$bnews_post_title = get_option("bnews_post_title");

$bnews_padding_top = get_option( 'bnews_padding_top' );
$bnews_padding_left = get_option( 'bnews_padding_left' );
$bnews_padding_down = get_option( 'bnews_padding_down' );
$bnews_padding_right = get_option( 'bnews_padding_right' );

$bnews_margin_top = get_option( 'bnews_margin_top' );
$bnews_margin_left = get_option( 'bnews_margin_left' );
$bnews_margin_down = get_option( 'bnews_margin_down' );
$bnews_margin_right = get_option( 'bnews_margin_right' );
wp_enqueue_script("jquery");	
wp_enqueue_style( 'wp-color-picker');
wp_enqueue_script( 'wp-color-picker');

?>
<style>
.bn_setting_page p label {
	float: left;
	font-weight: bold;
	width: 200px;
}
.bn_setting_page p select,.bn_setting_page p input,.bn_setting_page p textarea {
	width: 300px;
}
.bg-news {
    padding: 8px;
}
.bg-news .title-news {
    font-weight: bold;
}
.check-preview {
    border: 2px solid #000;
    padding: 6px;
    width:	60%;
	margin-top: 20px;
}
</style>
<div class="wrap inside bn_setting_page">
	<h3>Breaking News Settings</h3>
	<form action="" method="POST" id="bn-settings-form">
		
		<?php wp_nonce_field( 'bn_settings_nonce', 'bn_options' ); ?>
		<p>
			<label for="bn_title">Title:</label>
			<input type="text" name="bn_title" id="bn_title" value="<?php echo $bn_title ?>" placeholder="Breaking News:" /><br>
			<span class="description">Change the title of breaking news area. If its empty, by default "Breaking News:" will be shown.</span>				
		</p>
		<p>
			<label  for="bn_bcolor">Select Color for Background: </label>
			<input class="color-field" type="text" id="bn_bcolor" name="bn_bcolor" value="<?php echo '#'.$bn_bcolor; ?>"/>
		</p>
		<p>
			<label  for="bn_tcolor">Select Color for Text: </label>
			<input class="color-field" type="text" name="bn_tcolor" id="bn_tcolor" value="<?php echo '#'.$bn_tcolor; ?>"/>
		</p>
		<p>
			<label  for="bnews_post"><strong>Active Breaking News: </strong></label>
			<?php if($bnews_post){ ?>
				<input type="text" name="bnews_post" id="bnews_post" value="<?php echo get_the_title($bnews_post); ?>" readonly />
				<a href="<?php echo get_edit_post_link( $bnews_post ); ?>" class="button-primary" title="Edit" >Edit</a>
			<?php }else{ ?>
				<input type="text" name="bnews_post" id="bnews_post" value="" placeholder="No Post Selected" readonly />
				<a href="<?php echo admin_url( 'edit.php' ); ?>" class="button-primary" title="Select" >Select Post</a>
			<?php }?>
		</p>
		<hr>
		<p><strong>Css Settings:</strong></p>
		<p><strong>Padding: </strong></p>
		<p>			
			<label for="bnews_padding_top">Top <input type="number" value="<?= $bnews_padding_top ?>" name="bnews_padding_top" id="bnews_padding_top" class="small-text"/>px</label>
			<label for="bnews_padding_left">Left <input type="number" value="<?= $bnews_padding_left ?>" name="bnews_padding_left" id="bnews_padding_left"  class="small-text" />px</label>
			<label for="bnews_padding_down">Down <input type="number" value="<?= $bnews_padding_down ?>" name="bnews_padding_down" id="bnews_padding_down" class="small-text" />px</label>
			<label for="bnews_padding_right">Right <input type="number" value="<?= $bnews_padding_right ?>" name="bnews_padding_right" id="bnews_padding_right" class="small-text" />px</label>
		</p>
		<p style="clear: both;"><strong>Margin: </strong></p>
		<p>
			<label for="bnews_margin_top">Top <input type="number" value="<?= $bnews_margin_top ?>" name="bnews_margin_top" id="bnews_margin_top" class="small-text"/>px</label>
			<label for="bnews_margin_left">Left <input type="number" value="<?= $bnews_margin_left ?>" name="bnews_margin_left" id="bnews_margin_left"  class="small-text" />px</label>
			<label for="bnews_margin_down">Down <input type="number" value="<?= $bnews_margin_down ?>" name="bnews_margin_down" id="bnews_margin_down" class="small-text" />px</label>
			<label for="bnews_margin_right">Right <input type="number" value="<?= $bnews_margin_right ?>" name="bnews_margin_right" id="bnews_margin_right" class="small-text" />px</label>
		</p>
		<hr style="clear: both;">
		<div class="check-preview">
			<strong>Preview of Breaking News: </strong>
			<span class="description">First Save, then check preivew.</span>
			<div class="bg-news" style="background-color:#<?php echo $bn_bcolor ?>;color:#<?php echo $bn_tcolor ?>;padding:<?php echo $bnews_padding_top ?>px <?php echo $bnews_padding_left ?>px <?php echo $bnews_padding_down ?>px <?php echo $bnews_padding_right ?>px;margin:<?php echo $bnews_margin_top ?>px <?php echo $bnews_margin_left ?>px <?php echo $bnews_margin_down ?>px <?php echo $bnews_margin_right ?>px; ">
				<span class="title-news">
					<?php if($bn_title) echo $bn_title  ?> : 
				</span>
				<span class="post-news" >
					<?php if($bnews_post){
						echo (($bnews_post_title)? $bnews_post_title : get_the_title($bnews_post));
					} ?>
				</span>					
			</div>
		</div>
		
		<p>
			<input type="submit" class="button-primary" value="<?php echo  __(  'Save Changes', 'akt-management' )?>" name="save" />
		</p>
	</form>
	<hr>
	<strong>Information</strong>
	<ul>
		<li>Default Value of margin and padding is 0px.</li>
		<li>Default Background Color is Black. </li>
		<li>Default Text Color is White. </li>
		<li>When you make any changes, you have to save the settings then preview will be shown. </li>
		<li>After Reset just save the settings. </li>
	</ul>
</div>
<script>
	jQuery(document).ready(function(){
		jQuery('.color-field').wpColorPicker(); 
	});
</script>
