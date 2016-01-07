<?php

class application_frontend_index_view extends library_default_classes_view {

    public function __construct() {

        $this->site['name'] = 'Welcome to '.ucfirst(PROJECT);
        $this->errorMessage = NULL;
        $this->body = '<body>';

    }

    public function loadIndexTemplates() {

        ob_start();

            include('public/templates/default/html/navi.phtml');
            $this->navi = ob_get_contents();

        ob_end_clean();

        ob_start();

            include('public/templates/default/html/index.phtml');
            $this->siteContent = ob_get_contents();

        ob_end_clean();

        ob_start();

            include('public/templates/default/html/main.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function setErrorMessageFromGet($error) {

        switch($error) {

            case 'hashError':
                $this->errorMessage = 'something went wrong with your account activation<br>please check your activation link again';
                break;
            case 'noHashExist':
                $this->errorMessage = 'please login with your email address';
                break;
            case 'newPasswordError':
                $this->errorMessage = 'something is wrong with your email address, please try again';
                break;
            case 'eventInvitationEmpty':
                $this->errorMessage = 'there is no event assocating to this invitation';
                break;
            case 'eventInvitationHash':
                $this->errorMessage = 'something ist wrong with your invitation link';
                break;
            case 'friendInvitationEmpty':
                $this->errorMessage = 'please create an account if you don\'t have an account yet or login with your existing account';
                break;
            case 'friendInvitationHash':
                $this->errorMessage = 'something ist wrong with your invitation link';
                break;
            case 'friendInviteSuccess':
                $this->errorMessage = 'please create an account if you don\'t have an account yet or login with your existing account';
                break;
            case 'eventInviteSuccess':
                $this->errorMessage = 'please create an account if you don\'t have an account yet or login with your existing account';
                break;


        }

    }

}

?>