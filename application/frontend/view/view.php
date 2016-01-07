<?php

class application_frontend_view_view extends library_default_classes_view {

    public function loadViewEventTemplate(){

        if(isset($this->event['name']) && !empty($this->event['name'])) {
            $this->site['name'] = PROJECT.' | '.$this->event['name'];
        } else {
            $this->site['name'] = PROJECT.' | event';
        }

        if(isset($this->eid) && file_exists('data/users/'.md5($this->event['owner']).'/event/'.$this->eid.'/eventImage.jpg')) {
            $this->body = '<body style="background:url(data/users/'.md5($this->event['owner']).'/event/'.$this->eid.'/eventImage.jpg) repeat;">';
        } else {
            $this->body = '<body>';
        }

        ob_start();

            include('public/templates/default/html/navi.phtml');
            $this->navi = ob_get_contents();

        ob_end_clean();
        ob_start();

            include('public/templates/default/html/viewEvent.phtml');
            $this->siteContent = ob_get_contents();

        ob_end_clean();
        ob_start();

            include('public/templates/default/html/main.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>