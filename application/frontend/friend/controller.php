<?php

class application_frontend_friend_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_friend_model();
        $this->view = new application_frontend_friend_view();
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

                case 'all':
                    self::getFriendListAll();
                    $this->template = 'all';
                    break;
                case 'mutual':
                    self::getMutualFriendListAll();
                    $this->template = 'mutual';
                    break;
                case 'allRequest':
                    $this->view->friendRequest = $this->model->getAllRequest($this->user['myuid']);
                    $this->template = 'allRequest';
                    break;
                case 'request':
                    $this->template = 'request';
                    break;
                case 'deny':
                    $this->template = 'deny';
                    break;
                case 'confirm':
                    $this->template = 'confirm';
                    break;
                case 'delete':
                    $this->template = 'delete';
                    break;
                case 'invite':
                    $this->template = 'invite';
                    break;
                default:
                    parent::redirect('home');

            }

        } else {

            self::getFriendListBox();
            $this->template = 'box';

        }

    }

    private function handlePOST() {

        if(isset($_POST['request']) && $_POST['request'] == true) {
            $this->model->requestFriendship((int)$_POST['uid']);
            self::sendNotificationEmail('friendRequest', (int)$_POST['uid']);
        } elseif(isset($_POST['confirm'])) {

            $this->confirmUid = (int)$_POST['uid'];
            $this->model->deleteRequest($this->confirmUid);
            $this->model->addFriend($this->user['myuid'], $this->confirmUid);
            $this->model->addFriend($this->confirmUid, $this->user['myuid']);
            self::sendNotificationEmail('friendConfirm', (int)$_POST['uid']);

        } elseif(isset($_POST['deny'])) {
            $this->model->deleteRequest((int)$_POST['uid']);
        } elseif(isset($_POST['delete'])) {

            $this->deleteUid = (int)$_GET['uid'];
            $this->model->deleteFriend($this->user['myuid'], $this->deleteUid);
            $this->model->deleteFriend($this->deleteUid, $this->user['myuid']);
            parent::redirect('home?uid='.$this->deleteUid);

        } elseif(isset($_POST['friendInvite'])) {

            $this->input = array('name' => $this->user['myinfo']['name'], 'email' => $_POST['email'], 'content' => $_POST['content']);
            self::friendInvite();

        }

    }

    public function display() {

        switch($this->template) {

            case 'all':
                $this->view->show = 'all';
                echo $this->view->loadFriendAllTemplate();
                break;
            case 'mutual':
                $this->view->show = 'mutual';
                echo $this->view->loadFriendAllTemplate();
                break;
            case 'allRequest':
                $this->view->show = 'allRequest';
                echo $this->view->loadFriendAllTemplate();
                break;
            case 'box':
                return $this->view->loadFriendTemplate();
                break;
            case 'request':
                $this->view->actionTyp = 'request';
                echo $this->view->loadFriendActionTemplate();
                break;
            case 'deny':
                $this->view->actionTyp = 'deny';
                echo $this->view->loadFriendActionTemplate();
                break;
            case 'confirm':
                $this->view->actionTyp = 'confirm';
                echo $this->view->loadFriendActionTemplate();
                break;
            case 'delete':
                $this->view->actionTyp = 'delete';
                echo $this->view->loadFriendActionTemplate();
                break;
            case 'invite':
                echo $this->view->loadFriendInviteTemplate();
                break;

        }
        
    }

    private function getFriendListBox() {
        
        if($this->user['page']) {

            $this->friendArray = $this->model->getLimitedRandomFriends($this->user['myuid']);
            $this->view->friendInfoArray = $this->model->getFriendsInfo($this->friendArray);
            $this->view->friendCount = $this->model->getAllFriendsCount($this->user['myuid']);

        } else {

            $this->friendArray = $this->model->getLimitedRandomFriends($this->user['uid']);
            $this->view->friendInfoArray = $this->model->getFriendsInfo($this->friendArray);
            $this->view->friendCount = $this->model->getAllFriendsCount($this->user['uid']);

            $this->mutualFriendArray = $this->model->getRandomMutualFriends($this->user['myuid'], $this->user['uid']);
            $this->view->mutualFriendInfoArray = $this->model->getFriendsInfo($this->mutualFriendArray);
            $this->view->mutualFriendCount = $this->model->getAllMutualFriendsCount($this->user['myuid'], $this->user['uid']);

        }

    }

    private function getFriendListAll() {
        
        if($this->user['page']) {

            $this->friendArray = $this->model->getAllFriends($this->user['myuid']);
            $this->view->friendInfoArray = $this->model->getFriendsInfoWithEvent($this->friendArray);

        } else {

            $this->friendArray = $this->model->getAllFriends($this->user['uid']);
            $this->view->friendInfoArray = $this->model->getFriendsInfoWithEvent($this->friendArray);

        }

    }

    private function getMutualFriendListAll() {
        
        $this->mutualFriendArray = $this->model->getAllMutualFriends($this->user['myuid'], $this->user['uid']);
        $this->view->mutualFriendInfoArray = $this->model->getFriendsInfoWithEvent($this->mutualFriendArray);

    }

    private function friendInvite() {

        if(!empty($this->input['email'])) {

            $this->email = new application_frontend_email_controller();
            $emailContent['name'] = $this->user['myinfo']['name'];
            $emailContent['firstname'] = $this->user['myinfo']['firstname'];
            $emailContent['content'] = $this->input['content'];
            
            if(strrchr(';', $this->input['email']) == 'false') {
                $emailContent['hash'] = md5($this->user['myuid'].$this->input['email']);
                $this->email->sendEmail($this->input['email'], 'friendInvite', $emailContent);
                $this->model->saveEmailInvitation($this->user['myuid'], $this->input['email'], $emailContent);
            } else {

                $emailArray = explode(';', $this->input['email']);

                foreach($emailArray as $email) {

                    if(!empty($email)) {
                        $emailContent['hash'] = md5($this->user['myuid'].$email);
                        $this->email->sendEmail($email, 'friendInvite', $emailContent);
                        $this->model->saveEmailInvitation($this->user['myuid'], $email, $emailContent);
                    }

                }

            }

        }

    }

    public function checkFriendEmailInvitation($uid) {

        if(isset($_SESSION['friendInvite']) && !empty($_SESSION['friendInvite'])) {

            $isNotFriend = application_frontend_friend_model::checkFriendship($_SESSION['friendInvite']['uid'], $uid);

            if($isNotFriend == false && ($uid != $_SESSION['friendInvite']['uid'])) {
                application_frontend_friend_model::addFriend($_SESSION['friendInvite']['uid'], $uid);
                application_frontend_friend_model::addFriend($uid, $_SESSION['friendInvite']['uid']);                
            }

            application_frontend_friend_model::deleteFriendInvitation($_SESSION['friendInvite']['email'], $_SESSION['friendInvite']['uid']);
            unset($_SESSION['friendInvite']);

        }

    }

    private function sendNotificationEmail($content, $uid) {

        switch($content) {

            case 'friendRequest':

                $sendEmailTo = $this->model->getSendEmailUser($uid);
                if($sendEmailTo['friendRequest'] == 1) {

                    $emailContent['receiver'] = $sendEmailTo['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email = new application_frontend_email_controller();
                    $this->email->sendEmail($sendEmailTo['email'], 'newFriendRequest', $emailContent);

                }
                break;
            case 'friendConfirm':

                $sendEmailTo = $this->model->getSendEmailUser($uid);
                if($sendEmailTo['friendRequest'] == 1) {

                    $emailContent['receiver'] = $sendEmailTo['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email = new application_frontend_email_controller();
                    $this->email->sendEmail($sendEmailTo['email'], 'friendConfirm', $emailContent);

                }
                break;

        }

    }

}

?>