<?php

class application_frontend_default_view extends library_default_classes_view {

    public function __construct() {
        
    }

    public function loadDefaultTemplate(){

        ob_start();

            include('public/templates/default/html/default.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>