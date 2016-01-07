<?php

class application_frontend_index_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_index_model();
        $this->view = new application_frontend_index_view();
        $this->user = $this->view->user = $GLOBALS['user'];

    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::loadApplications();
        self::handleGET();
        self::checkLoginIndex();
        
        return self::display();

    }

    private function handleGET() {

        if(isset($_GET['action']) && !empty($_GET['action'])) {

            switch($_GET['action']) {

                case 'status':
                    if(isset($_GET['id']) && !empty($_GET['id'])) {
                        $this->view->setErrorMessageFromGet($_GET['id']);
                    }
                    break;

            }

        }

    }

    private function loadApplications() {

        $this->register = new application_frontend_register_controller();
        $this->view->application['register'] = $this->register->actionController();
        $this->login = new application_frontend_login_controller();
        $this->view->application['login'] = $this->login->actionController();

        if($this->login->view->loginMessage === NULL) {
            $this->view->errorMessage = $this->register->view->registerMessage;
        } else {
            $this->view->errorMessage = $this->login->view->loginMessage;
        }

    }

    private function display() {

        $this->view->eventRecommend = $this->model->getEventRecommend();
        echo $this->view->loadIndexTemplates();

    }

    private function checkLoginIndex() {

        if($this->user['login'] === true) {
            parent::redirect('home');
        }

    }

}

?>