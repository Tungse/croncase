<?php

class application_frontend_content_view extends library_default_classes_view {

    public function loadContentDefaultTemplate(){

        ob_start();

            include('public/templates/default/html/content.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>