<?php

class application_frontend_email_view extends library_default_classes_view {

    public function loadEmailHtmlTemplate($subject){

        ob_start();

            include('templates/html/'.$subject.'.html');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEmailTxtTemplate($subject){

        ob_start();

            include('templates/txt/'.$subject.'.txt');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>