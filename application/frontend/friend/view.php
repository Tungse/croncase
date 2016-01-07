<?php

class application_frontend_friend_view extends library_default_classes_view {

    public function __construct() {

        $this->friendArray = array();
        $this->mutualFriendInfoArray = array();
        $this->friendInfoArray = array();
        $this->friendRequest = array();

    }

    public function loadFriendTemplate(){

        ob_start();

            include('public/templates/default/html/friend.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadFriendAllTemplate(){

        ob_start();

            include('public/templates/default/html/friendAll.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadFriendActionTemplate(){

        ob_start();

            include('public/templates/default/html/friendAction.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadFriendInviteTemplate(){

        ob_start();

            include('public/templates/default/html/friendInvite.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?>