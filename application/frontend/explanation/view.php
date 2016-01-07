<?php

class application_frontend_explanation_view extends library_default_classes_view {

    public function loadExplanationTemplate(){

        ob_start();

            include('public/templates/default/html/explanation.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>