<?php

class application_frontend_email_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_email_model();
        $this->view = new application_frontend_email_view();

        $this->header = 'From: info@'.PROJECT.'.com <info@'.PROJECT.'.com>'."\r\n";
        $this->header .= 'Reply-To: NoReply <noreply@'.PROJECT.'.com>'."\r\n";
        $this->header .= 'Return-Path: info@'.PROJECT.'.com'."\r\n";
        $this->header .= 'MIME-Version: 1.0'."\r\n";
        $this->header .= 'Content-Type: text/html; charset=iso-8859-1'."\r\n";
        $this->header .= 'Message-ID: <'.time().' info@'.PROJECT.'.com>'."\r\n";
        $this->header .= 'X-Mailer: PHP v'.phpversion()."\r\n";

    }

    public function sendEmail($email, $subject, $input = NULL) {

        $this->input = $input;
        $this->email = $email;
        
        self::display($subject);
        mail($this->email, $this->subjectTitle, $this->emailContentHtml, $this->header);
        
    }

    private function display($subject) {

        switch($subject) {
           
            case 'eventInvite':
                self::getContentForEventInvite();
                break;
            case 'friendInvite':
                self::getContentForFriendInvite();
                break;
            case 'newComment':
                self::getContentForNewComment();
                break;
            case 'newCommentSub':
                self::getContentForNewCommentSub();
                break;
            case 'newPrivateMessage':
                self::getContentForNewPrivateMessage();
                break;
            case 'newEventInvite':
                self::getContentForNewEventInvite();
                break;
            case 'newEventComment':
                self::getContentForNewEventComment();
                break;
            case 'newEventRequest':
                self::getContentForNewEventRequest();
                break;
            case 'eventUpdate':
                self::getContentForEventUpdate();
                break;
            case 'newFriendRequest':
                self::getContentForNewFriendRequest();
                break;
            case 'friendConfirm':
                self::getContentForFriendConfirm();
                break;
            case 'activation':
                self::getContentForActivation();
                break;
            case 'password':
                self::getContentForNewPassword();
                break;

        }

        $this->emailContentHtml = $this->view->loadEmailHtmlTemplate($subject);
        //$this->emailContentTxt = $this->view->loadEmailTxtTemplate($subject);

    }

    private function getContentForNewComment() {

        $this->subjectTitle = 'new comment';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];
        $this->view->comment = substr($this->input['comment'], 0, 100).'...';

    }

    private function getContentForNewCommentSub() {

        $this->subjectTitle = 'new comment';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];
        $this->view->comment = substr($this->input['comment'], 0, 100).'...';

    }

    private function getContentForNewEventInvite() {

        $this->subjectTitle = 'invitation to an event';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];
        $this->view->eventPublic = base64_encode($this->input['eid']);
        $this->view->eventName = $this->model->getEventName($this->input['eid']);

    }

    private function getContentForNewEventComment() {

        $this->subjectTitle = 'new comment on an event';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];
        $this->view->comment = substr($this->input['comment'], 0, 100).'...';
        $this->view->eventName = $this->model->getEventName($this->input['eid']);
        $this->view->eventPublic = base64_encode($this->input['eid']);

    }

    private function getContentForNewEventRequest() {

        $this->subjectTitle = 'request event invitation';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];
        $this->view->eventName = $this->model->getEventName($this->input['eid']);
        $this->view->eventPublic = base64_encode($this->input['eid']);

    }

    private function getContentForEventUpdate() {

        $this->subjectTitle = 'event update';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];
        $this->view->eventName = $this->model->getEventName($this->input['eid']);
        $this->view->eventPublic = base64_encode($this->input['eid']);
        $this->view->update = NULL;
        
        if(isset($this->input['name'])) {
            $this->view->update .= '<b>name: </b><i>'.$this->input['nameOld'].' </i><b>to</b><i> '.$this->input['name'].'</i><br>';
        }
        if(isset($this->input['address'])) {
            $this->view->update .= '<b>address: </b><i>'.$this->input['addressOld'].' </i><b>to</b><i> '.$this->input['address'].'</i><br>';
        }
        if(isset($this->input['location'])) {
            $this->view->update .= '<b>location: </b><i>'.$this->input['locationOld'].' </i><b>to</b><i> '.$this->input['location'].'</i><br>';
        }
        if(isset($this->input['dateFrom'])) {
            $this->view->update .= '<b>date: </b><i>'.$this->input['dateFromOld'].' </i><b>to</b><i> '.$this->input['dateFrom'].'</i><br>';
        }
        if(isset($this->input['timeFrom'])) {
            $this->view->update .= '<b>start time: </b><i>'.$this->input['timeFromOld'].' </i><b>to</b><i> '.$this->input['timeFrom'].'</i><br>';
        }
        if(isset($this->input['timeTo'])) {
            $this->view->update .= '<b>end time: </b><i>'.$this->input['timeToOld'].' </i><b>to</b><i> '.$this->input['timeTo'].'</i><br>';
        }

    }

    private function getContentForNewPrivateMessage() {

        $this->subjectTitle = 'new private message';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];
        $this->view->message = substr($this->input['privateMsg'], 0, 100).'...';

    }

    private function getContentForNewFriendRequest() {

        $this->subjectTitle = 'friend request';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];

    }

    private function getContentForFriendConfirm() {

        $this->subjectTitle = 'friend confirm';
        $this->view->receiver = $this->input['receiver'];
        $this->view->writer = $this->input['writer'];

    }

    private function getContentForActivation() {

        $this->subjectTitle = 'your activation code';
        $this->view->receiver = $this->model->getUserNameFromEmail($this->email);
        $this->view->hash = $this->input;

    }

    private function getContentForNewPassword() {

        $this->subjectTitle = 'your new password';
        $this->view->receiver = $this->model->getUserNameFromEmail($this->email);
        $this->view->password = $this->input;

    }

    private function getContentForEventInvite() {

        $this->subjectTitle = 'invitation to an event';
        $this->view->hash = $this->input['hash'];
        $this->view->writer = $this->input['name'];
        $this->view->eventName = $this->model->getEventName($this->input['eid']);
        $this->view->eventPublic = base64_encode($this->input['eid']);

    }

    private function getContentForFriendInvite() {

        $this->subjectTitle = 'friend invitation';
        $this->view->hash = $this->input['hash'];
        $this->view->writerFullName = $this->input['name'];
        $this->view->writer = $this->input['firstname'];
        $this->view->content = $this->input['content'];

    }

}

?>