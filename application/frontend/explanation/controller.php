<?php

class application_frontend_explanation_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_explanation_model();
        $this->view = new application_frontend_explanation_view();

    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::handleGET();
        self::display();

    }

    private function handleGET() {

        if(isset($_GET['action']) && !empty($_GET['action'])) {

            $this->input = $_GET['action'];
            $this->view->explanation = $this->model->getExplanation($this->input);

        }

    }

    private function display() {

        echo $this->view->loadExplanationTemplate();

    }

}

?>