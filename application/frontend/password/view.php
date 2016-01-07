<?php

class application_frontend_password_view extends library_default_classes_view {

    public function loadPasswordTemplate(){

        ob_start();

            include('public/templates/default/html/password.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>