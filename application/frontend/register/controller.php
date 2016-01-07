<?php

class application_frontend_register_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_register_model();
        $this->view = new application_frontend_register_view();
        $this->email = new application_frontend_email_controller();

    }

    public function actionController() {

        if(!empty($_POST)) {
            $_POST = parent::filter($_POST);
            self::handlePOST();
        }
        
        return self::display();

    }

    private function handlePOST() {

        if(isset($_POST['register'])) {

            $this->input = array('firstname' => $_POST['firstname'], 'lastname' => $_POST['lastname'], 'gender' => $_POST['gender'], 'email' => $_POST['email'], 'password' => self::getEncryption($_POST['password']));
            $this->view->input = $this->input;
            self::registerData();

        }

    }

    private function display() {
        return $this->view->loadRegisterTemplate();
    }

    private function registerData() {

        if(self::checkForEmptyInput($this->input)) {
            $this->view->showRegisterMessage('empty');
        } elseif(self::validEmail($this->input) === false) {
            $this->view->showRegisterMessage('email');
        } elseif($this->model->checkRegisterData($this->input)) {
            $this->view->showRegisterMessage('exist');
        } else {

            $this->model->createUser($this->input);
            $uid = $this->model->getUid();
            self::createUsersFolder($uid);
            $this->email->sendEmail($this->input['email'], 'activation', $this->model->getHash());
            application_frontend_event_controller::checkEventEmailInvitation($uid);
            $this->view->showRegisterMessage('success');
            
        }

    }

    private function createUsersFolder($uid) {

        $userFolder = md5($uid);

        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder, 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/avatar', 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/album', 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/event', 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/home', 0777, true);
        umask($oldUmask);

	copy('public/templates/default/images/avatar.jpg', 'data/users/'.$userFolder.'/avatar/avatar.jpg');
        chmod('data/users/'.$this->user['md5'].'/avatar.jpg', 0755);
        copy('public/templates/default/images/friend.jpg', 'data/users/'.$userFolder.'/avatar/friend.jpg');
        chmod('data/users/'.$this->user['md5'].'/friend.jpg', 0755);
        copy('public/templates/default/images/thumbnail.jpg', 'data/users/'.$userFolder.'/avatar/thumbnail.jpg');
        chmod('data/users/'.$this->user['md5'].'/thumbnail.jpg', 0755);

    }

    private function getEncryption($input) {

        if(empty($input)) {
            $output = NULL;
        } else {
            $output = md5($input);
        }

        return $output;

    }

    private function validEmail($input) {

        if(preg_match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]^", $input['email']) > 0) {
            return true;
        } else {
            return false;
        }

    }

    private function checkForEmptyInput($input) {

        foreach($input as $key => $element) {

            if(empty($element)) {
                return true;
            }

        }

        return false;

    }

}

?>