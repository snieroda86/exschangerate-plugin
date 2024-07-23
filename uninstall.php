<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

$option_name = 'cc_form_options';

delete_option( $option_name );

// for site options in Multisite
delete_site_option( $option_name );
