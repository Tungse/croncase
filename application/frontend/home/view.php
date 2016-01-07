<?php

class application_frontend_home_view extends library_default_classes_view {

    public function __construct() {

        $this->application['comment'] = NULL;
        $this->application['status'] = NULL;
        $this->application['shortInfo'] = NULL;
        $this->application['friend'] = NULL;
        $this->showCalender = false;

    }

    public function loadHomeTemplates() {

        if(file_exists('data/users/'.$this->user['md5'].'/home/background.jpg')) {
            $this->body = '<body style="background:url(data/users/'.$this->user['md5'].'/home/background.jpg) repeat;">';
        } else {
            $this->body = '<body>';
        }

        ob_start();

            include('public/templates/default/html/navi.phtml');
            $this->navi = ob_get_contents();

        ob_end_clean();

        ob_start();

            include('public/templates/default/html/home.phtml');
            $this->siteContent = ob_get_contents();

        ob_end_clean();

        ob_start();

            include('public/templates/default/html/main.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadHomeShortInfoTemplates() {

        ob_start();

            include('public/templates/default/html/homeShortInfo.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>