<?php

if(!class_exists('CC_Form_Shortcode')){
	class CC_Form_Shortcode{
		public function __construct(){
			add_shortcode( 'cc_form_shortcode', array($this , 'cc_form_add_shortcode') );
		}

		public function cc_form_add_shortcode(){
		
			ob_start();
			require(CC_SN_PATH.'views/cc-form_shortcode.php');
			wp_enqueue_script('cc-form-api-request');
			wp_enqueue_style('cc-form-css');
			return ob_get_clean();
		}
	}
}