<?php

// Make sure Gravity Forms is active and already loaded.
if ( ! class_exists( 'GFForms' ) ) {
    die();
}

class GFJsonExportOutput {

    protected $output_type;
    protected $form_title;

    public function __construct() {

        if (isset($_POST['gf_json_export']))
        {
            if (!wp_verify_nonce( $_POST['verify_gf_json_export'], 'verify_gf_json_export' ) )
            {
                add_action( 'admin_notices', array($this, 'show_verify_nonce_error' ));
                return;
            }

            $this->output_type = sanitize_text_field($_POST['output_type']);
            $entry_data = $this->create_json_export();
            $this->output_json($entry_data);
            
        }
        
    }

    public function show_verify_nonce_error() {
        $class = 'notice notice-error';
        $message = __( 'There was an error processing the form. Please try again.', 'gf_json_export' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }


    public function output_json($entry_data ) {
        $json = json_encode($entry_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($this->output_type == "download"):
            $filename = $this->form_title . '-export.json';
        
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename=' . $filename);
            header("Content-Transfer-Encoding: binary");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo $json;
            exit();
        else:
            header ('Content-Type: text/html'); 
            header("Pragma: no-cache");

            ?>
            <!doctype html>
            <html>
            <head>
                <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
                <title><?php echo $this->form_title; ?> <?php _e("JSON Export", "gf_json_export"); ?></title>
                <style>
                    textarea {
                        width: 100%;
                        min-height: 400px;
                        position: absolute;
                        left: 0;
                        top: 0;
                        height: 100%;
                    }
                    body {
                        overflow: hidden;
                    }
                </style>
            </head>
            <body>
                <div style="display: none;" id="json"></div>
                <textarea id="json-textarea" disabled><?php echo $json; ?></textarea>
            </body>
            </html>
            <?php
            exit();

        endif;
    }

    public function create_json_export () {
        $data = array();
        $search_criteria = array(
            "status" => "active"
        );

        $export_date_start = sanitize_text_field($_POST['export_date_start']);
        $export_date_end = sanitize_text_field($_POST['export_date_end']);

        if (!empty($export_date_start ))
        {
            $search_criteria['start_date'] = $export_date_start;
        }

        if (!empty($export_date_end))
        {
            $search_criteria['end_date'] = $export_date_end;
        }

        $form_id = intval($_POST['form_id']);

        $entries = GFAPI::get_entries($form_id, $search_criteria, NULL, array( 'offset' => 0, 'page_size' => 10000 ));
        
        $form = GFAPI::get_form($form_id);
        $this->form_title = $form['title'];
        
        foreach ($entries as $entry):
            $entry_data = array();

            // Check if this is a partial entry
            if (array_key_exists('partial_entry_id', $entry)) {
                if ($entry["partial_entry_id"] != false)
                    continue;
            }
            
            // Loop through all form fields
            foreach ( $form['fields'] as &$field ) {
                
                // If this is a complex field...
                if ($field->inputs != NULL) 
                {
                    // Loop through inputs for this complex field
                    foreach($field->inputs as &$input) {
                        
                        // If input isn't hidden proceed...
                        if (!$input['isHidden'] == true)
                        {

                            // Get name of field
                            $name = $input['label'];

                            // Get value of field
                            $value = $entry[$input['id']];

                            // Add key/value
                            $entry_data[$name] = ($value);
                        }
                    }
                    
                }
                else
                {
                    if ($field['type'] != "section" && $field['type'] != "page")
                    {
                        // Get name of field
                        $name = $field['label'];

                        // Get value of field
                        // If field type is list join array of values with comma
                        if ($field->type == "list")
                        {
                            
                            $value = join(", ", unserialize($entry[$field->id]));
                        }
                        else
                        {
                            $value = $entry[$field->id];
                        }
                        
                        // Add key/value
                        $entry_data[$name] = ($value); // conver unicode to ascii
                    }

                }
            }

            $data[] = $entry_data;

        endforeach;

        return $data;
    }

}


?>