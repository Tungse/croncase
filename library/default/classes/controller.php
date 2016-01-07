<?php

class library_default_classes_controller {

    protected function filter($input) {

        $filter = new library_default_classes_inputFilter();
        $input = $filter->filter($input);

        return $input;

    }

    protected function redirect($url) {

        if((getenv('HTTPS') == 'on' || getenv('HTTPS') == '1')) {
            $HTTP = 'https://';
        } else {
            $HTTP = 'http://';
        }

    	header('Location: '.$HTTP.$_SERVER['SERVER_NAME'].'/project/'.$url);

    }

    public function checkLogin($status) {

        if($status === false) {
            self::redirect('index');
        }

    }

}

?>