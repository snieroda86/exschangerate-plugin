<?php 

/*
 * Plugin Name:       Currency convertert SN
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Convert crrency easly.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sebastian Nieroda
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       currency-converter-sn
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


if( ! class_exists('Currency_Converter_SN')){
    class Currency_Converter_SN{
        public function __construct(){
            $this->define_constants();

            // Admin menu
            add_action('admin_menu' , array($this , 'create_admin_menu') );

            // cc form shortcode
            require_once(CC_SN_PATH.'/shortcodes/class.cc-form-shortcode.php');
            $CC_Form_Shortcode = new CC_Form_Shortcode();

            // Plugin options
            require_once(CC_SN_PATH.'/inc/class.cc-form-settings.php');
            $CC_Form_Settings = new CC_Form_Settings();
            

            // Ajax handler
            require_once(CC_SN_PATH.'/inc/class.cc-form-ajax-handler.php');
            $CC_Form_Ajax_Handler = new CC_Form_Ajax_Handler();            

            // Enqueue scripts
            add_action('wp_enqueue_scripts' , array($this , 'register_scripts'),999 );
  
        }

        // Constans
        public function define_constants(){
            define('CC_SN_PATH' , plugin_dir_path( __FILE__ ));
            define('CC_SN_URL' , plugin_dir_url(__FILE__));
            define('CC_SN_VERSION' , '1.0.0');
        }

        // Admin menu
        public function create_admin_menu(){
            add_menu_page(
                __( 'Currency Converter SN', 'currency-converter-sn' ),
                'Currency Converter SN',
                'manage_options',
                'cc_form_admin',
                array($this , 'cc_form_settings_page'),
                'dashicons-money-alt',
                6
            );
        }

        public function cc_form_settings_page(){
            if( ! current_user_can( 'manage_options' )){
                return;
            }
            // Messages
            if(isset($_GET['settings-updated'])){
                add_settings_error( 'cc_form_options', 'cc_form_message' , 'Ustawienia zapisane' , 'success' );
            }
            // Display messages
            settings_errors('cc_form_options');
            require (CC_SN_PATH.'/views/settings-page.php');
        }

        // Regitser scripts
        public function register_scripts(){
            // CSS files
            wp_register_style( 'cc-form-css', CC_SN_URL.'assets/css/cc-form-style.css' , array() , CC_SN_VERSION , 'all' );
            // JS files
            
          
        }

    }
}

if( class_exists('Currency_Converter_SN')){
    $sn_slider = new Currency_Converter_SN();
}