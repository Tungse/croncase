<?php

class application_frontend_comment_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_comment_model();
        $this->view = new application_frontend_comment_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        parent::checkLogin($this->user['login']);

    }

    public function actionController() {

        $_GET = parent::filter($_GET);

        if(!empty($_POST)) {
            $_POST = parent::filter($_POST);
        }
        if(isset($_GET['event'])) {
            return self::prozess('event');
        } else {
            return self::prozess('home');
        }

    }

    private function display($template) {

        switch($template) {

            case 'sub':
                echo $this->view->loadCommentSubTemplate();
                break;
            case 'delete':
                echo $this->view->loadCommentDeleteTemplate();
                break;
            case 'commentMore':
                echo $this->view->loadCommentMoreTemplate();
                break;
            case 'deletesub':
                echo $this->view->loadCommentDeleteSubTemplate();
                break;
            case 'home':
                return $this->view->loadCommentTemplate();
                break;
            case 'status':
                return $this->view->loadCommentStatusTemplate();
                break;
            case 'event':
                return $this->view->loadCommentEventTemplate();
                break;
            case 'commentEventNew':
                return $this->view->loadCommentEventNewTemplate();
                break;
            case 'deleteEvent':
                echo $this->view->loadCommentDeleteEventTemplate();
                break;

        }
        
    }

    private function prozess($name) {

        switch($name) {

            case 'home':

                if(isset($_GET['sub'])) {
                    self::display('sub');
                } elseif(isset($_GET['delete'])) {
                    self::display('delete');
                } elseif(isset($_GET['deletesub'])) {
                    self::display('deletesub');
                } elseif(isset($_GET['app']) && $_GET['app'] === 'home') {

                    $this->view->comments = $this->model->getComment();
                    $this->view->lastComment = $this->model->getLastComment();
                    return self::display('home');

                }

                if(isset($_POST['saveComment'])) {

                    $this->input = array('comment' => $_POST['comment'], 'uid' => (int)$_GET['uid']);
                    self::checkError();

                    if(!$this->error) {
                        $this->model->saveComment($this->input);
                        if($this->user['page'] === false) {
                            self::sendNotificationEmail('comment', $this->input);
                        }
                    }

                    parent::redirect('home?uid='.$this->input['uid']);

                } elseif(isset($_POST['saveSubComment'])) {

                    $this->input = array('comment' => $_POST['comment'], 'uid' => (int)$_GET['uid'], 'cid' => (int)$_GET['cid']);
                    self::checkError();

                    if(!$this->error) {
                        $this->model->saveCommentSub($this->input);
                        self::sendNotificationEmail('commentSub', $this->input);
                    }

                    parent::redirect('home?uid='.$this->input['uid']);

                } elseif(isset($_POST['deleteComment']) && $_POST['deleteComment'] == true) {

                    $this->input = array('cid' => (int)$_POST['cid']);
                    $this->model->deleteComment($this->input);

                } elseif(isset($_POST['deleteSubComment']) && $_POST['deleteSubComment'] == true) {

                    $this->input = array('csid' => (int)$_POST['csid']);
                    $this->model->deleteCommentSub($this->input);

                } elseif(isset($_POST['showCommentMore']) && $_POST['showCommentMore'] == true) {

                    $this->view->comments = $this->model->getCommentMore((int)$_POST['lastComment']);
                    $this->view->lastComment = $this->model->getLastComment();
                    self::display('commentMore');

                } elseif(isset($_POST['commentStatus'])) {
                    echo self::getStatus(3);
                }

                break;

            case 'event':

                if(isset($_GET['delete'])) {
                    self::display('deleteEvent');
                } elseif(isset($_GET['app']) && $_GET['app'] === 'event') {

                    $this->view->commentEvent = $this->model->getCommentEvent((int)$_GET['eid']);
                    return self::display('event');

                }

                if(isset($_POST['saveEventComment']) && $_POST['saveEventComment'] == true) {

                    $this->input = array('comment' => $_POST['comment'], 'eid' => (int)$_POST['eid'], 'uid' => (int)$_POST['uid']);
                    self::checkError();

                    if(!$this->error) {
                        $this->model->saveCommentEvent($this->input);
                        self::sendNotificationEmail('eventComment', $this->input);
                    }

                } elseif(isset($_POST['deleteEventComment']) && $_POST['deleteEventComment'] == true) {

                    $this->input = array('ecid' => (int)$_POST['ecid']);
                    $this->model->deleteCommentEvent($this->input);

                } elseif(isset($_POST['readedEventComment']) && $_POST['readedEventComment'] == true) {
                    $this->model->readedEventComment($this->user['myuid'], (int)$_POST['eid']);
                } elseif(isset($_POST['showEventComment'])) {
                    $this->view->commentEvent = $this->model->getCommentEventNew((int)$_GET['eid']);
                    echo self::display('commentEventNew');
                }
                
                break;

        }

    }

    private function checkError() {

        if(empty($this->input['comment'])) {
            $this->error = true;
        } else {
            $this->error = false;
        }
        
    }

    public function getStatus($statusPrivacy) {

        $this->view->notificationExist = false;
        
        if($statusPrivacy >= $this->user['relation']) {
            $this->view->status = $this->model->getStatusMessage($this->user['uid']);
        } else {
            $this->view->status = NULL;
        }
        if($this->user['page']) {
            self::getNotifications();
        }
        
        return self::display('status');

    }

    private function getNotifications() {

        $this->view->notification['friendRequestCount'] = application_frontend_friend_model::getRequestCount($this->user['myuid']);
        $this->view->notification['eventInvitationCount'] = application_frontend_event_model::getInvitationCount($this->user['myuid']);
        $this->view->notification['eventCommentCount'] = application_frontend_event_model::getUnreadEventCommentCount($this->user['myuid']);
        $this->view->notification['privatemgsAllCount'] = application_frontend_privatemsg_model::getUnreadPrivatemsgCount($this->user['myuid']);
        $this->view->notification['requestEventInvitation'] = application_frontend_event_model::getRequestEventInvitationCount($this->user['myuid']);

        if($this->view->notification['friendRequestCount'] > 0 ||
            $this->view->notification['eventInvitationCount'] > 0 ||
            $this->view->notification['privatemgsAllCount'] > 0 ||
            $this->view->notification['eventCommentCount'] > 0 ||
            $this->view->notification['requestEventInvitation'] > 0)
        {
            $this->view->notificationExist = true;
        }

    }

    private function sendNotificationEmail($content, $input) {

        switch($content) {

            case 'comment':
                if($this->model->checkNotificationAllow('comment', $this->user['uid'])) {

                    $emailContent['comment'] = $input['comment'];
                    $emailContent['receiver'] = $this->user['info']['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email = new application_frontend_email_controller();
                    $this->email->sendEmail($this->user['info']['email'], 'newComment', $emailContent);

                }
                break;
            case 'commentSub':

                $this->email = new application_frontend_email_controller();
                $sendEmailToArray = $this->model->getEmailCommentUser($input['cid'], $this->user['myuid']);

                foreach($sendEmailToArray as $sendEmailTo) {

                    $emailContent['comment'] = $input['comment'];
                    $emailContent['receiver'] = $sendEmailTo['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email->sendEmail($sendEmailTo['email'], 'newCommentSub', $emailContent);

                }
                break;
            case 'eventComment':

                $this->email = new application_frontend_email_controller();
                $sendEmailToArray = $this->model->getEmailEventUser($input['eid'], $this->user['myuid']);

                foreach($sendEmailToArray as $sendEmailTo) {

                    $emailContent['comment'] = $input['comment'];
                    $emailContent['eid'] = $input['eid'];
                    $emailContent['receiver'] = $sendEmailTo['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email->sendEmail($sendEmailTo['email'], 'newEventComment', $emailContent);

                }
                break;

        }

    }

}

?>