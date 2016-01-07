<?php

class application_frontend_content_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_content_model();
        $this->view = new application_frontend_content_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        parent::checkLogin($this->user['login']);

    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::handleGET();

    }

    private function handleGET() {

        if(isset($_GET['action']) && !empty($_GET['action'])) {

            switch($_GET['action']) {

                case 'aboutMe':
                    $this->view->content = $this->model->getAboutMe($this->user['uid']);
                    return self::display('default');
                    break;
                case 'status':
                    $this->view->content = $this->model->getStatus($this->user['uid']);
                    return self::display('default');
                    break;

            }

        }

    }

    private function display($template) {

        switch($template) {

            case 'default':
                echo $this->view->loadContentDefaultTemplate();
                break;

        }
        
    }

}

?>