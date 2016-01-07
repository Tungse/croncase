<?php

class application_frontend_user_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_user_model();
        $this->view = new application_frontend_user_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        parent::checkLogin($this->user['login']);
        
    }

    public function actionController() {

        $_GET = parent::filter($_GET);      
        self::handleGET();

        if(!empty($_POST)) {
            $_POST = parent::filter($_POST);
            self::handlePOST();
        }

        return self::display();

    }

    private function handleGET() {

        if(isset($_GET['action']) && !empty($_GET['action'])) {
            
            switch($_GET['action']) {

                case 'avatar':
                    $this->template = 'avatar';
                    break;
                default:
                    parent::redirect('home');

            }

        } else {
            parent::redirect('home');
        }

    }

    private function handlePOST() {

        if(isset($_POST['upload'])) {

            self::uploadAvatar();
            self::uploadBackground();
            sleep(2);
            parent::redirect('home');

        }

    }

    private function display() {

        switch($this->template) {

            case 'avatar':
                echo $this->view->loadUploadAvatarTemplate();
                break;

        }
        
    }

    public function getUser() {

        $user = array('login' => false, 'myuid' => NULL, 'uid' => NULL, 'md5' => 0, 'relation' => 0, 'page' => true, 'info' => NULL, 'myinfo' => NULL);
        $fingerPrint = md5($_SERVER['HTTP_USER_AGENT']);

        if(isset($_SESSION['uid']) && !empty($_SESSION['uid']) && $_SESSION['id'] == $fingerPrint) {
            $user['myuid'] = (int)parent::filter($_SESSION['uid']);
            $user['myinfo'] = application_frontend_user_model::getUser($user['myuid']);
            $user['login'] = true;
        }

        if(isset($_GET['uid']) && !empty($_GET['uid'])) {

            $user['uid'] = (int)parent::filter($_GET['uid']);

            if(application_frontend_user_model::checkUserExist($user['uid']) === false) {
                self::redirect('home');
            }

        }

        if($user['uid'] !== NULL && $user['myuid'] !== NULL && $user['myuid'] !== $user['uid']) {

            $user['md5'] = md5($user['uid']);
            $user['page'] = false;
            $user['info'] = application_frontend_user_model::getUser($user['uid']);
            $user['relation'] = self::getUserRelation($user['myuid'], $user['uid']);

        } else {

            $user['md5'] = md5($user['myuid']);
            $user['uid'] = $user['myuid'];
            $user['info'] = $user['myinfo'];
            
        }

        return $user;

    }

    private function getUserRelation($uid, $uidOfPage) {

        if(application_frontend_friend_model::checkFriendship($uid, $uidOfPage)) {
            $userRelation = 1;
        } elseif(application_frontend_friend_model::getAllMutualFriends($uid, $uidOfPage) !== NULL) {
            $userRelation = 2;
        } else {
            $userRelation = 3;
        }

        return $userRelation;

    }

    private function uploadAvatar() {

        $filename = $_FILES['avatar']['name'];
        $filesize = $_FILES['avatar']['size'];
        $filetype = $_FILES['avatar']['type'];

        if($filename == '') {
            $uploadCheck = false;
        } elseif($filesize > 1048576) {
            $uploadCheck = false;
        } elseif($filetype != 'image/jpeg' && $filetype != 'image/gif' && $filetype != 'image/png') {
            $uploadCheck = false;
        } else {

            $fileInfo = getimagesize($_FILES['avatar']['tmp_name']);

            if($fileInfo['mime'] != 'image/jpeg' && $fileInfo['mime'] != 'image/gif' && $fileInfo['mime'] != 'image/png') {
                $uploadCheck = false;
            } else {
                $uploadCheck = true;
            }

        }

        if($uploadCheck) {

            $resizeImage = new library_default_classes_resizeImage($this->user, $fileInfo, $_FILES['avatar']['tmp_name']);
            $resizeImage->resizeImage('avatar');
            $resizeImage->resizeImage('friend');
            $resizeImage->resizeImage('thumbnail');
            
        }

    }

    private function uploadBackground() {

        $filename = $_FILES['background']['name'];
        $filesize = $_FILES['background']['size'];
        $filetype = $_FILES['background']['type'];

        if($filename == '') {
            $uploadCheck = false;
        } elseif($filesize > 1048576) {
            $uploadCheck = false;
        } elseif($filetype != 'image/jpeg' && $filetype != 'image/gif' && $filetype != 'image/png') {
            $uploadCheck = false;
        } else {

            $fileInfo = getimagesize($_FILES['background']['tmp_name']);

            if($fileInfo['mime'] != 'image/jpeg' && $fileInfo['mime'] != 'image/gif' && $fileInfo['mime'] != 'image/png') {
                $uploadCheck = false;
            } else {
                $uploadCheck = true;
            }

        }

        if($uploadCheck) {

            $resizeImage = new library_default_classes_resizeImage($this->user, $fileInfo, $_FILES['background']['tmp_name']);
            $resizeImage->resizeImage('background');

        } elseif(isset($_POST['no_background']) && $_POST['no_background'] == 'true') {
            unlink('data/users/'.$this->user['md5'].'/home/background.jpg');
        }

    }

}

?>