<?php

class application_frontend_password_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_password_model();
        $this->view = new application_frontend_password_view();

    }

    public function actionController() {

        $_GET = parent::filter($_GET);

        if(!empty($_POST)) {
            $_POST = parent::filter($_POST);
            self::handlePOST();
        }
        
        return self::display();

    }

    private function handlePOST() {

        if(isset($_POST['newPassword'])) {
            self::checkPassword($_POST['email']);
        }

    }

    private function display() {
        echo $this->view->loadPasswordTemplate();
    }

    private function checkPassword($email) {

        if($this->model->checkEmailExist($email)) {

            $this->email = new application_frontend_email_controller();
            $newPassword = self::createNewPassword();
            $this->email->sendEmail($email, 'password', $newPassword);
            $this->model->setNewPassword(md5($newPassword), $email);

        } else {
            parent::redirect('index?action=status&id=newPasswordError');
        }

    }
    
    private function createNewPassword() {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        
        $i = 0;
        $password = NULL ;

        while ($i <= 7) {

            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $password = $password.$tmp;
            $i++;
            
        }

        return $password;

    }

}

?>