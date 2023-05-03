<?php
/*
Plugin Name: CTA Box Button
Plugin URI: https://github.com/ritikasharma0294
Text Domain: cta box button
Description: This is cta box button.
Version: 1.0
Author: Ritika
Author URI: https://github.com/ritikasharma0294
License: GPL2
*/	
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CTAbox{
	
	public function __construct() {		
		$this->define_constants();		
		$this->init_CTA();		
	}
	
	public function define_constants() {
		define( 'CTA_VERSION', '1.0' );
		define( 'CTA_DIR_PATH', plugin_dir_path( __FILE__ ) );
		define( 'CTA_URL', plugins_url( '', __FILE__ )  );
		
	}
	
	public function init_CTA() {	
		// Set text domain for multilangiage support
		add_filter( 'locale', array( $this, 'my_cta_localized') );	
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 20 );
		add_shortcode( 'cta_box', array( $this, 'cta_box_button' ));

		//enqueue scripts and styles
		add_action( 'wp_enqueue_scripts', array($this, 'cta_enqueue_scripts'), 15 );
		//add ajax file path
		add_action( 'wp_enqueue_scripts', array($this, 'cta_load_admin_ajax'), 15);
		
			
	}
	
	/**
	*
	Localizing text domain 
	*
	**/
	public function my_cta_localized( $locale )
	{
		if ( isset( $_GET['lang'] ) )
			{
				return sanitize_key( $_GET['lang'] );
			}
		return $locale;
	}

	/**
	*
	Loading text domain 
	*
	**/
	public function load_textdomain() {
								 
		load_plugin_textdomain( 'cta-lite', false, dirname(plugin_basename(__FILE__)).'/languages/' );
	}

	/**
	*
	Including scripts sand styles to frontend 
	*
	**/
	public function cta_enqueue_scripts() {
		// Load the stylesheet.
		wp_enqueue_style( 'cta_style_frontend', CTA_URL.'/assets/css/style.css' );		
		wp_register_script('cta_script_frontend', CTA_URL.'/assets/js/scripts.js', array('jquery'), CTA_VERSION, true);
		wp_enqueue_script('cta_script_frontend');
	}

	/**
	*
	LOCALIZE SCRIPT 
	*
	**/
	public function cta_load_admin_ajax() {
			wp_localize_script( 'cta_script_frontend', 'ajax_login_object', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
		));
	}	
	 
	/**
	*
	Shortcode to show the CTA section
	*
	**/
	public function cta_box_button( $atts, $content = "" ) {		 
		$ctab = shortcode_atts( array(
			'title'=>'',
			'message'=>'',
			'button_label'=>'',
			'button_url'=>'',					
			), $atts ); 
			
			ob_start();?>
				<div class="main-cta-box">
				
					<h2><?php echo __($ctab['title'],'cta-lite'); ?></h2>
					<p><?php echo __($ctab['message'],'cta-lite'); ?></p>
					<a href="<?php echo __($ctab['button_url'],'cta-lite'); ?>" target="_blank" id="ctabox_button" onclick="create_storage()"><?php echo __($ctab['button_label'],'cta-lite'); ?></a>				
				</div>
			
			<?php 
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
/***end class*****/
}	


//create objects to initialize class constructor
$Ctab = new CTAbox();