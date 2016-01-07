<?php

class application_frontend_default_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_default_model();
        $this->view = new application_frontend_default_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        parent::checkLogin($this->user['login']);

    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::handleGET();
        
        if(!empty($_POST)) {
            $_POST = parent::filter($_POST);
            self::handlePOST();
        }

    }

    private function handleGET() {

        if(isset($_GET['action']) && !empty($_GET['action'])) {

            switch($_GET['action']) {

                case '':

                    return self::display('default');
                    break;

            }

        }

    }

    private function handlePOST() {

        if(isset($_POST[''])) {
            
        }

    }

    private function display($template) {

        switch($template) {

            case 'default':
                echo $this->view->loadDefaultTemplate();
                break;

        }

    }

}

?>