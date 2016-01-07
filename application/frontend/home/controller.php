<?php

class application_frontend_home_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_home_model();
        $this->view = new application_frontend_home_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        $this->view->site['name'] = PROJECT.' | '.$this->user['info']['name'];
        parent::checkLogin($this->user['login']);
        
        application_frontend_event_controller::checkEventEmailInvitation($this->user['myuid']);
        self::updatePageView();

    }

    public function actionController() {

        $_GET = parent::filter($_GET);

        self::loadApplications();
        self::loadNavi();
        self::getFriendshipStatus();
        
        return self::display();

    }

    private function display() {
        echo $this->view->loadHomeTemplates();
    }

    private function loadApplications() {

        $this->comment = new application_frontend_comment_controller();
        $this->friend = new application_frontend_friend_controller();

        $this->privacy = application_frontend_profile_model::getUserPrivacy($this->user['uid']);

        $this->view->application['shortInfo'] = $this->getShortInfo();
        $this->view->application['status'] = $this->comment->getStatus($this->privacy['status']);

        if($this->privacy['comment'] >= $this->user['relation']) {
            $this->view->application['comment'] = $this->comment->actionController();
        }
        if($this->privacy['friend'] >= $this->user['relation']) {
            $this->view->application['friend'] = $this->friend->actionController();
        }
        if($this->privacy['calendar'] >= $this->user['relation']) {
            $this->view->showCalender = true;
        }

    }

    private function loadNavi() {
        
        $this->search = new application_frontend_search_controller();
        $this->view->application['search'] = $this->search->display();

    }

    private function getShortInfo() {

        $this->view->shortInfo = $this->model->getShortInfo($this->user['uid']);
        $this->view->mutualEvents = $this->model->getMutualEvents($this->user['myuid'], $this->user['uid']);
        return $this->view->loadHomeShortInfoTemplates();

    }

    private function getFriendshipStatus() {

        if($this->user['page'] === false) {
            $this->view->isFriend = application_frontend_friend_model::checkFriendship($this->user['myuid'], $this->user['uid']);
            $this->view->requestSend = application_frontend_friend_model::checkRequestSend($this->user['myuid'], $this->user['uid']);
        }

    }

    private function updatePageView() {

        if(($this->user['myuid'] !== $this->user['uid'])) {
            $this->model->updatePageView($this->user['uid']);
        }

    }

}

?>