<?php

class application_frontend_privatemsg_view extends library_default_classes_view {

    public function __construct() {

        $this->site['name'] = PROJECT.' | private messages';
        $this->body = '<body>';

    }

    public function loadPrivatemsgTemplate(){

        ob_start();

            include('public/templates/default/html/navi.phtml');
            $this->navi = ob_get_contents();

        ob_end_clean();
        ob_start();

            include('public/templates/default/html/privatemsg.phtml');
            $this->siteContent = ob_get_contents();

        ob_end_clean();

        ob_start();

            include('public/templates/default/html/main.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadPrivatemsgWriteTemplate(){

        ob_start();

            include('public/templates/default/html/privatemsgWrite.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadPrivatemsgDeleteTemplate(){

        ob_start();

            include('public/templates/default/html/privatemsgDelete.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>