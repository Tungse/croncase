<?php

class application_frontend_view_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_view_model();
        $this->view = new application_frontend_view_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        $this->view->event = NULL;

    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::getApplications();
        self::handleGET();        

    }

    private function handleGET() {

        if(isset($_GET['eid']) && !empty($_GET['eid'])) {

            $this->eid = $this->view->eid = (int)base64_decode($_GET['eid']);
            if(is_int($this->eid)) {
                $this->view->event = $this->model->getEvent($this->eid);
            }
            
            return self::display('event');

        } else {
            parent::redirect('index');
        }

    }

    private function display($template) {

        switch($template) {

            case 'event':
                echo $this->view->loadViewEventTemplate();
                break;

        }

    }

    private function getApplications() {

        if($this->user['login']) {

            $this->search = new application_frontend_search_controller();
            $this->view->application['search'] = $this->search->display();

        } else {

            $this->register = new application_frontend_register_controller();
            $this->view->application['register'] = $this->register->actionController();
            $this->login = new application_frontend_login_controller();
            $this->view->application['login'] = $this->login->actionController();

        }

    }

}

?>