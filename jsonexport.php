<?php
/*
Plugin Name: GF JSON Export
Plugin URI: http://www.fissionstrategy.com
Description: Adds support for exporting Gravity Forms entries as JSON
Version: 1.0
Author: Fission Strategy
License: MIT
*/

// Make sure Gravity Forms is active and already loaded.
if ( ! class_exists( 'GFForms' ) ) {
    die();
}

add_action( 'gform_loaded', array( 'GF_Json_Export_Bootstrap', 'load' ), 5 );

class GF_Json_Export_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
        require_once( 'class-gf-json-export-admin.php' );
        require_once( 'class-gf-json-export-output.php' );
        GFAddOn::register( 'GFJsonExportAdmin' );
        new GFJsonExportOutput();
    }

}

?>