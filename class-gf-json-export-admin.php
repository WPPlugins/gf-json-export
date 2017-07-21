<?php

// Make sure Gravity Forms is active and already loaded.
if ( ! class_exists( 'GFForms' ) ) {
    die();
}


GFForms::include_addon_framework();

class GFJsonExportAdmin extends GFAddOn {

    protected $_version = 1.0;
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'gravityforms-json-export';
    protected $_path = 'gravityforms-json-export/jsonexport.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms JSON Export';
    protected $_short_title = 'JSON Export';

    private static $_instance = null;

    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFJsonExportAdmin();
        }

        return self::$_instance;
    }

    public function init() {
        parent::init();
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );

    }

    public function enqueue () {
        wp_enqueue_script("jquery-ui-datepicker");  
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    }

    public function plugin_page() {
    ?>
        <div class="wrap">
            <p>
                <?php _e("Select a form and download a JSON file of all of its entries.", "gf_json_export"); ?>
            </p>
            
            <form target="_blank" method="POST">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label><?php _e("Choose form", "gf_json_export"); ?></label></th>
                            <td>
                                <select name="form_id">
                                    <?php
                                    $forms = GFAPI::get_forms();
                                    foreach($forms as $form)
                                    {
                                        echo '<option value="' . $form['id'] . '">' . $form['title'] .'</option>';      
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php _e("Start Date", "gf_json_export"); ?></label></th>
                            <td><input type="text" id="export_date_start" name="export_date_start"></td>
                        </tr>
                       
                        <tr>
                            <th scope="row"><label><?php _e("End Date", "gf_json_export"); ?></label></th>
                            <td><input type="text" id="export_date_end" name="export_date_end"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php _e("Output Type", "gf_json_export"); ?></label></th>
                            <td>
                                <select name="output_type">
                                    <option value="download"><?php _e("Download", "gf_json_export"); ?></option>
                                    <option value="browser"><?php _e("View in browser", "gf_json_export"); ?></option>
                                </select>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>
                <button class="button button-primary button-large" type="submit"> <?php _e("Export JSON", "gf_json_export"); ?> </button>
                <input type="hidden" name="gf_json_export" />
                <?php wp_nonce_field( 'verify_gf_json_export', 'verify_gf_json_export' ); ?>
            </form>
            
        </div>
        <script>
        (function ($) {
            $(function () {
                $.datepicker.formatDate( "yy-mm-dd");
                $("#export_date_start, #export_date_end").datepicker({
                    dateFormat : "yy-mm-dd"
                });
            });
        })(jQuery);
        </script>

    <?php
    }

}


?>