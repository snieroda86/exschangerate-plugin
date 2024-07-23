<?php

if(!class_exists('CC_Form_Settings')){
	class CC_Form_Settings{

		public static $options;

		public function __construct(){
			self::$options = get_option('cc_form_options');
			add_action('admin_init' , array($this , 'admin_init'));
		}

		public function admin_init(){

			register_setting('cc_form_group' , 'cc_form_options' , array($this , 'cc_form_validate'));

			add_settings_section(
		        'cc_form_main_section',
		        'Exchange rates API key', 
		        NULL,
		        'cc_form_page1'
		    );

		    add_settings_field(
			    'cc_form_api_key',
			    'Klucz API',
			    array($this , 'cc_form_api_key_callback'),
			    'cc_form_page1',
			    'cc_form_main_section'
			);

			// Display shortocde
			add_settings_field(
			    'cc_form_shortcode',
			    'Shortcode',
			    array($this , 'cc_form_shortcode_callback'),
			    'cc_form_page1',
			    'cc_form_main_section'
			);
		}

		public function cc_form_api_key_callback(){ ?>
			<input 
			type="text" 
			name="cc_form_options[cc_form_api_key]"
			id="cc_form_api_key"
			value="<?php echo isset( self::$options['cc_form_api_key'] ) ? esc_attr( self::$options['cc_form_api_key'] ) : ''; ?>"
			>
		<?php }

		// Display shortcode
		public function cc_form_shortcode_callback(){ ?>
			<input type="text" readonly value="[cc_form_shortcode]">
		<?php }

		// Validate data
		public function cc_form_validate($input){
			$new_input = array();

			foreach ($input as $key => $value) {
				$new_input[$key] = sanitize_text_field($value);
			}
			return $new_input;
		}
		
	}
}