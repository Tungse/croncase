<?php

class application_frontend_default_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_default_model();
        $this->view = new application_frontend_default_view();

    }

    public function actionController() {

        self::prepareInput();

        

        self::display();

    }

    private function display() {

        echo $this->view->loadDefaultTemplate();

    }

    private function prepareInput() {

    }

}

?>

