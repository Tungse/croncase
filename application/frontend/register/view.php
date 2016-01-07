<?php

class application_frontend_register_view extends library_default_classes_view {
    
    public function __construct() {

        $this->registerMessage = NULL;
        $this->input = array('firstname' => 'your firstname', 'lastname' => 'your lastname', 'email' => 'your email address here');
        
    }

    public function loadRegisterTemplate(){

        ob_start();

            include('public/templates/default/html/register.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function showRegisterMessage($content) {

        switch($content) {

            case 'success':
                $this->registerMessage = 'now please login with your new '.PROJECT.' account<br>an activation link has beed send to your email address, your may activate your account with that link';
                break;
            case 'exist':
                $this->registerMessage = 'there is already an account associated with '.$this->input['email'];
                break;
            case 'email':
                $this->registerMessage = 'this is not the way an email looks like, please try again with a valid email address';
                $this->input['email'] = NULL;
                break;
            case 'empty':
                $this->registerMessage = 'you must fill in all of the fields';
                break;

        }

    }

}

?>