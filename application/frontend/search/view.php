<?php

class application_frontend_search_view extends library_default_classes_view {

    public function __construct() {

        $this->site['name'] = PROJECT.' | search';
        $this->searchKeyword = NULL;
        $this->searchAdvanceKeyword = NULL;
        $this->userSearch = array();
        $this->eventSearch = array();
        $this->body = '<body>';
        
    }

    public function loadSearchTemplate(){

        ob_start();

            include('public/templates/default/html/search.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadSearchResultTemplate() {

        ob_start();

            include('public/templates/default/html/navi.phtml');
            $this->navi = ob_get_contents();

        ob_end_clean();
        ob_start();

            include('public/templates/default/html/searchResult.phtml');
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