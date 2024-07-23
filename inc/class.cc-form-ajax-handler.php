<?php

if(!class_exists('CC_Form_Ajax_Handler')){
	class CC_Form_Ajax_Handler{

		const ACTION = 'cc_form_api';

		const NONCE = 'cc_form_ajax_nonce';
		
		public function __construct(){
			add_action('wp_enqueue_scripts' , array($this , 'register_scripts'),999 );
			add_action('wp_ajax_' . self::ACTION, array($this, 'handle_ajax_request'));
            add_action('wp_ajax_nopriv_' . self::ACTION, array($this, 'handle_ajax_request'));
		}
		public function register_scripts(){
			wp_register_script( 'cc-form-api-request', CC_SN_URL.'assets/js/cc-form-api-request.js' , array('jquery'), CC_SN_VERSION , true );
			wp_localize_script('cc-form-api-request', 'wp_ajax_data', $this->get_ajax_data());
		}

		private function get_ajax_data()
	    {
	        return array(
	        	'ajax_url' => admin_url('admin-ajax.php'),
	            'action' => self::ACTION,
	            'nonce' => wp_create_nonce(self::NONCE)
	        );
	    }

	    public function handle_ajax_request() {
            
                if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], self::NONCE)) {
			        wp_send_json_error(array('message' => 'Nieprawidłowy nonce.'));
			        
			    }

			    if (!isset($_POST['value'])) {
			        wp_send_json_error(array('message' => 'Brak przesłanej wartości.'));
			        
			    }

			    $currencyAmount = floatval($_POST['value']); 

			    // Endpoint API
			    $base_url = 'http://api.exchangeratesapi.io/v1/';
			    
			    $options = get_option('cc_form_options');

				if (is_array($options) && isset($options['cc_form_api_key'])) {
				    $api_key = $options['cc_form_api_key'];
				} else {
				    $api_key = '';
				}
			    $endpoint = 'latest';
			    $base = 'EUR';
			    $symbols = 'PLN';
			    $api_url = $base_url . $endpoint.'?access_key='.$api_key.'&base='.$base.'&symbols='.$symbols;

			    $response = wp_remote_get($api_url);

			    if (is_wp_error($response)) {
			        wp_send_json_error(array('message' => 'Błąd połączenia z API.'));
			        
			    }

			    $body = wp_remote_retrieve_body($response);
			    $data = json_decode($body, true);

			    if (!isset($data['rates']['PLN'])) {
			        wp_send_json_error(array('message' => 'Brak kursu wymiany PLN.'));
			        
			    }

			    $exchange_rate = $data['rates']['PLN'];
			    $value_in_eur = $currencyAmount / $exchange_rate;

			    $response = array(
			        'success' => true,
			        'value_in_eur' => $value_in_eur
			    );

			    wp_send_json($response);
			    wp_die();
        }
	}
}