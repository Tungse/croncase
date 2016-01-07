<?php

class application_frontend_activation_controller extends library_default_classes_controller {

    public function  __construct() {
        $this->model = new application_frontend_activation_model();
    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::handleGET();

    }

    private function handleGET() {

        switch($_GET['action']) {

            case 'activation':

                if(isset($_GET['h']) && !empty($_GET['h'])) {

                    $uid = $this->model->checkHashExist($_GET['h']);

                    if($uid !== false) {

                        $this->model->deleteHash($_GET['h']);
                        $this->model->activateUser($uid);
                        self::createUsersFolder($uid);
                        self::setUserStatus($uid);
                        application_frontend_event_controller::checkEventEmailInvitation($uid);
                        application_frontend_friend_controller::checkFriendEmailInvitation($uid);
                        
                        parent::redirect('home');

                    } else {
                        parent::redirect('index?action=status&id=noHashExist');
                    }

                } else {
                    parent::redirect('index?action=status&id=hashError');
                }               
                break;

            case 'eventInvite':
                
                if(isset($_GET['h']) && !empty($_GET['h'])) {

                    $invitation = $this->model->getEventInvitationFromHash($_GET['h']);

                    if(!empty($invitation)) {
                        $_SESSION['eventInvite']['inviter'] = $invitation['inviter'];
                        $_SESSION['eventInvite']['ueid'] = $invitation['ueid'];
                        $_SESSION['eventInvite']['eid'] = $invitation['eid'];
                        $_SESSION['eventInvite']['receiver'] = $invitation['receiver'];
                        parent::redirect('index?action=status&id=eventInviteSuccess');
                    } else {
                        parent::redirect('index?action=status&id=eventInvitationEmpty');
                    }

                } else {
                    parent::redirect('index?action=status&id=eventInvitationHash');
                }
                break;

            case 'friendInvite':

                if(isset($_GET['h']) && !empty($_GET['h'])) {

                    $invitation = $this->model->getFriendInvitationFromHash($_GET['h']);

                    if(!empty($invitation)) {
                        $_SESSION['friendInvite']['uid'] = $invitation['uid'];
                        $_SESSION['friendInvite']['email'] = $invitation['email'];
                        parent::redirect('index?action=status&id=friendInviteSuccess');
                    } else {
                        parent::redirect('index?action=status&id=friendInvitationEmpty');
                    }

                } else {
                    parent::redirect('index?action=status&id=friendInvitationHash');
                }
                break;

        }

    }

    private function createUsersFolder($uid) {
        
        $userFolder = md5($uid);

        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder, 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/avatar', 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/album', 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/event', 0777, true);
        umask($oldUmask);
        $oldUmask = umask(0);
        mkdir('data/users/'.$userFolder.'/home', 0777, true);
        umask($oldUmask);

	copy('public/templates/default/images/avatar.jpg', 'data/users/'.$userFolder.'/avatar/avatar.jpg');
        chmod('data/users/'.$this->user['md5'].'/avatar.jpg', 0755);
        copy('public/templates/default/images/friend.jpg', 'data/users/'.$userFolder.'/avatar/friend.jpg');
        chmod('data/users/'.$this->user['md5'].'/friend.jpg', 0755);
        copy('public/templates/default/images/thumbnail.jpg', 'data/users/'.$userFolder.'/avatar/thumbnail.jpg');
        chmod('data/users/'.$this->user['md5'].'/thumbnail.jpg', 0755);

    }

    private function setUserStatus($uid) {

        $_SESSION['uid'] = $uid;
        $_SESSION['admin'] = 0;
        $_SESSION['id'] = md5($_SERVER['HTTP_USER_AGENT']);

    }

}

?>