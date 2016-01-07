<?php

class application_frontend_search_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_search_model();
        $this->view = new application_frontend_search_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];

    }

    public function actionController() {

        if(!empty($_POST)) {
            $_POST = parent::filter($_POST);
            self::handlePOST();
        }

        self::loadNavi();
        return self::displayResult();

    }

    private function handlePOST() {

        if(isset($_POST['search'])) {

            $this->view->searchKeyword = $_POST['searchKeyword'];

            if(!empty($this->view->searchKeyword)) {

                $this->view->userSearch = $this->model->getUserSearchResult($this->view->searchKeyword);
                $this->view->eventSearch = $this->model->getEventSearchResult($this->view->searchKeyword);

            }

        } elseif(isset($_POST['searchAdvance'])) {

            $this->view->searchAdvanceKeyword = array('from' => $_POST['from'], 'location' => $_POST['location'], 'owner' => $_POST['owner'], 'eventName' => $_POST['eventName']);

            if(self::checkForNotEmptyInput($this->view->searchAdvanceKeyword)) {

                $this->view->userSearch = array();
                $this->view->eventSearch = $this->model->getEventSearchAdvanceResult($this->view->searchAdvanceKeyword);

            }

        } elseif(isset($_POST['searchEvent'])) {

            $this->view->searchKeyword = $_POST['searchKeyword'];

            if(!empty($this->view->searchKeyword)) {
                $this->view->eventSearch = $this->model->getEventSearchResult($this->view->searchKeyword);
            }

        }

    }

    public function display() {
        return $this->view->loadSearchTemplate();
    }

    private function loadNavi() {
        $this->view->application['search'] = self::display();
    }

    private function displayResult() {
        echo $this->view->loadSearchResultTemplate();
    }

    private function checkForNotEmptyInput($input) {

        foreach($input as $key => $element) {

            if(!empty($element)) {
                return true;
            }

        }

        return false;

    }

}

?>