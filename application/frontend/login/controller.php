<?php

class application_frontend_login_controller extends library_default_classes_controller {

    public function __construct() {

        $this->model = new application_frontend_login_model();
        $this->view = new application_frontend_login_view();

    }

    public function actionController() {

        $_POST = parent::filter($_POST);

        self::handlePOST();
        return self::display();

    }

    private function handlePOST() {

        if(isset($_POST['login'])) {

            $this->input = array('loginEmail' => $_POST['loginEmail'], 'loginPassword' => self::getEncryption($_POST['loginPassword']));
            $this->view->email = $this->input['loginEmail'];
            self::login();

        }

    }

    private function display() {

        return $this->view->loadLoginTemplate();
        
    }

    private function login() {

        if(!empty($this->input['loginEmail']) && !empty($this->input['loginEmail'])) {

            if(self::validEmail($this->input) === false) {
                $this->view->showLoginMessage('email');
            } else {

                $this->uid = $this->model->getUsersId($this->input);

                if($this->uid === NULL) {
                    $this->view->showLoginMessage('register');
                } elseif($this->model->checkPasswordAccount($this->input) === false) {
                    $this->view->showLoginMessage('password');
                } elseif($this->model->checkUserActivation($this->uid) === false) {
                    $this->view->showLoginMessage('activation');
                } else {

                    self::setUserStatus($this->uid);
                    $this->model->saveLoginAttempt($this->uid);
                    application_frontend_event_controller::checkEventEmailInvitation($this->uid);
                    application_frontend_friend_controller::checkFriendEmailInvitation($this->uid);
                    parent::redirect('home');

                }

            }

        }

    }

    private function getEncryption($input) {

        if(empty($input)) {
            $output = NULL;
        } else {
            $output = md5($input);
        }

        return $output;

    }

    private function setUserStatus($uid) {

        if($this->model->checkAdmin($uid)) {
            $_SESSION['admin'] = 1;
        } else {
            $_SESSION['admin'] = 0;
        }

        $_SESSION['uid'] = $uid;
        $_SESSION['id'] = md5($_SERVER['HTTP_USER_AGENT']);

    }

    private function validEmail($input) {

        if(preg_match("^[a-zA-Z0-9_.]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]^", $input['loginEmail']) > 0) {
            return true;
        } else {
            return false;
        }

    }

}

?>