<?php

class application_frontend_login_view extends library_default_classes_view {

    public function __construct() {
       
        $this->loginMessage = NULL;
        $this->email = 'your email address here';

    }

    public function loadLoginTemplate() {

        ob_start();

            include('public/templates/default/html/login.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function showLoginMessage($content) {

        switch($content) {

            case 'password':
                $this->loginMessage = 'this is not the correct password for your account, please try again';
                break;
            case 'email':
                $this->loginMessage = 'this is not the way an email looks like, please try again with a valid email address';
                $this->input = NULL;
                break;
            case 'empty':
                $this->loginMessage = 'you must fill in all of the fields';
                break;
            case 'register':
                $this->loginMessage = 'there is no account associated with this email address, please create an account first and then login with your created account';
                break;
            case 'activation':
                $this->loginMessage = 'an activation link has beed send to your email address, please activate your account with that link';
                break;

        }

    }

}

?>