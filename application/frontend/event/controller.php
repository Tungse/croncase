<?php

class application_frontend_event_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_event_model();
        $this->view = new application_frontend_event_view();
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

    }

    private function handleGET() {

        if(isset($_GET['action']) && !empty($_GET['action'])) {

            switch($_GET['action']) {

                case 'invite':
                    $this->view->ueid = (int)$_GET['ueid'];
                    $this->friendArray = application_frontend_friend_model::getAllFriends($this->user['myuid']);
                    $this->joinedArray = $this->model->getJoinedFriends($this->view->ueid);
                    $this->invitedArray = $this->model->getInvitedFriends($this->user['myuid'], $this->view->ueid);
                    $this->friendArray = self::checkInvitedFriend($this->friendArray, $this->invitedArray, $this->joinedArray);
                    $this->view->friendInfoArray = application_frontend_friend_model::getFriendsInfo($this->friendArray);
                    return self::display('invite');
                    break;
                case 'view':
                    if(isset($_GET['confirm']) && !empty($_GET['confirm'])) {
                        $this->view->iid = (int)$_GET['confirm'];
                    }
                    if(isset($_GET['ueid']) && !empty($_GET['ueid'])) {
                        $this->view->ueid = (int)$_GET['ueid'];
                        $this->view->event = $this->model->getUserEvent($this->view->ueid);
                    } else {
                        $this->view->eid = (int)$_GET['eid'];
                        $this->view->ueid = $this->model->getUserAttendingId($this->user['myuid'], (int)$_GET['eid']);
                        if($this->view->ueid !== NULL) {
                            $this->view->event = $this->model->getUserEvent($this->view->ueid);
                        } else {
                            $this->view->event = $this->model->getUserEventByEid((int)$_GET['eid']);
                            $this->view->ueidExist = false;
                        }
                        
                    }                   
                    
                    $this->view->attenders = $this->model->getAttenders((int)$_GET['eid']);
                    $this->view->joined = $this->model->checkEventAttendingStatus($this->user['myuid'], (int)$_GET['eid']);
                    $this->commentEvent = new application_frontend_comment_controller();
                    $this->view->commentEvent = $this->commentEvent->actionController();
                    return self::display('view');
                    break;
                case 'showInvitation':
                    $this->view->invitationList = $this->model->getInvitation($this->user['myuid']);
                    return self::display('showInvitation');
                    break;
                case 'inviteUser':                   
                    return self::display('inviteUser');
                    break;
                case 'viewUnreadComment':
                    $this->view->unreadComment = $this->model->getUnreadEventComment($this->user['myuid']);
                    return self::display('viewUnreadComment');
                    break;
                case 'eventInviteAutoComplete':
                    echo $this->model->getEventNameListAutoComplete($this->user['myuid'], $_GET['q']);
                    break;
                case 'create':
                    return self::display('create');
                    break;
                case 'edit':
                    $this->view->eid = (int)$_GET['eid'];
                    $this->view->event = $this->model->getEvent($this->view->eid);
                    return self::display('edit');
                    break;
                case 'delete':
                    $this->view->id = (int)$_GET['ueid'];
                    return self::display('delete');
                    break;
                case 'all':
                    $this->view->pastEventCreated = $this->model->getPastEventCreatedOfUser($this->user);
                    $this->view->futureEventCreated = $this->model->getFutureEventCreatedOfUser($this->user);
                    $this->view->pastEventAttending = $this->model->getPastEventAttendingOfUser($this->user);
                    $this->view->futureEventAttending = $this->model->getFutureEventAttendingOfUser($this->user);
                    return self::display('all');
                    break;
                case 'mutual':
                    $this->view->mutualEvents = $this->model->getMutualEvents($this->user['myuid'], $this->user['uid']);
                    return self::display('mutual');
                    break;
                case 'search':
                    return self::display('search');
                    break;
                case 'viewEventInvitationRequest':
                    $this->view->eventRequest = $this->model->getEventRequestInfo($this->user['myuid']);
                    return self::display('viewEventInvitationRequest');
                default:
                    parent::redirect('home');

            }

        }

    }

    private function handlePOST() {

        if(isset($_POST['deleteEvent']) && $_POST['deleteEvent'] == true) {
            $this->model->deleteEvent((int)$_POST['ueid']);
        } elseif(isset($_POST['move']) && $_POST['move'] == true) {

            $this->input = array('ueid' => $_POST['ueid'], 'top' => $_POST['top'], 'height' => $_POST['height'], 'left' => $_POST['left']);

            self::prepareEventMoveData();
            $this->model->saveEventMovesData($this->input);
            echo '<small>'.$this->input['top'].' - '.$this->input['bottom'].'</small>';

        } elseif(isset($_POST['create'])) {

            $this->input = array('attend' => $_POST['attend'],
                                    'name' => $_POST['name'],
                                    'repeating' => $_POST['repeating'],
                                    'days' => $_POST['days'],
                                    'weeks' => $_POST['weeks'],
                                    'timeFrom' => $_POST['timeFrom'],
                                    'timeTo' => $_POST['timeTo'],
                                    'address' => $_POST['address'],
                                    'location' => $_POST['location'],
                                    'website' => $_POST['website'],
                                    'description' => $_POST['description'],
                                    'joinable' => $_POST['joinable'],
                                    'privacy' => $_POST['privacy'],
                                    'searchable' => $_POST['searchable']);

            self::prepareInput();
            self::checkError();

            if(!$this->error) {

                $this->eid = $this->model->saveEvent($this->input);
                self::createEventFolder($this->eid);
                self::uploadEventImage($this->eid);

            }

            parent::redirect('home');

        } elseif(isset($_POST['edit'])) {

            $this->input = array('name' => $_POST['name'],
                                    'timeFrom' => $_POST['timeFrom'],
                                    'timeTo' => $_POST['timeTo'],
                                    'address' => $_POST['address'],
                                    'location' => $_POST['location'],
                                    'website' => $_POST['website'],
                                    'description' => $_POST['description'],
                                    'joinable' => $_POST['joinable'],
                                    'searchable' => $_POST['searchable']);

            self::prepareInput();
            self::checkError();

            if(!$this->error) {

                $this->eid = (int)$_GET['eid'];
                self::checkEventUpdate($this->input, $this->eid);
                $this->model->updateEvent($this->input, $this->eid);
                self::uploadEventImage($this->eid);

            }

            parent::redirect('home?uid='.(int)$_GET['uid']);

        } elseif(isset($_POST['updateEvent'])) {

            $this->input = array('attend' => $_POST['attend'], 'timeFrom' => $_POST['timeFrom'], 'timeTo' => $_POST['timeTo'], 'privacy' => $_POST['privacy']);

            self::prepareInput();
            if(!empty($this->input['dateFrom'])) {
                $this->model->changeEvent($this->input, (int)$_GET['ueid']);
            }

        } elseif(isset($_POST['duplicate'])) {

            $this->input = array('attend' => $_POST['attend'], 'timeFrom' => $_POST['timeFrom'], 'timeTo' => $_POST['timeTo'], 'privacy' => $_POST['privacy']);

            self::prepareInput();
            $this->model->duplicateEvent($this->input, (int)$_GET['ueid'], (int)$_GET['eid']);

        } elseif(isset($_POST['inviteUser'])) {

            if(isset($_POST['ueid']) && !empty($_POST['ueid']))  {

                $this->input = array('eid' => $_POST['eid'], 'ueid' => $_POST['ueid'], 'inviter' => $this->user['myuid'], 'receiver' => $_POST['receiver']);
                if($this->model->checkInvited($this->input) === false) {
                    $this->model->inviteEvent($this->input);
                    self::sendNotificationEmail('eventInvitation', $this->input);
                }
                
            }
            
        } elseif(isset($_POST['join'])) {

            $this->input = array('attend' => $_POST['attend'], 'timeFrom' => $_POST['timeFrom'], 'timeTo' => $_POST['timeTo'], 'privacy' => $_POST['privacy']);

            self::prepareInput();
            if(!empty($this->input['dateFrom'])) {
                $this->model->deleteEventInvitation((int)$_GET['eid']);
                $this->model->joinEvent($this->input, (int)$_GET['eid']);
            }

        } elseif(isset($_POST['reject']) && $_POST['reject'] == true) {
            $this->model->rejectInvitation((int)$_POST['iid']);
        } elseif(isset($_POST['sendInvite']) && $_POST['sendInvite'] == true) {

            if(!empty($_POST['friends'])) {
                $friendInvitedArray = $_POST['friends'];
            } else {
                $friendInvitedArray = NULL;
            }

            $this->input = array('eid' => (int)$_POST['eid'], 'ueid' => (int)$_POST['ueid'], 'email' => $_POST['email'], 'friends' => $friendInvitedArray);
            
            self::sendEmailInvitation();
            self::inviteFriend();

        } elseif(isset($_POST['showEventMap'])) {
            $this->view->event = array('name' => $_POST['name'], 'date' => $_POST['date']);
            $this->view->geoData = library_default_classes_googleMaps::getCoordinates($_POST['address']);
            return self::display('eventMap');
        } elseif(isset($_POST['requestEventInvitation'])) {
            $this->model->saveRequestEventInvitation($_POST['eid'], $_POST['ueid']);
        } elseif(isset($_POST['acceptEventInvitationRequest'])) {
            $input = array('eid' => $_POST['eid'], 'ueid' => $_POST['ueid'], 'inviter' => $this->user['myuid'], 'receiver' => $_POST['request']);
            $this->model->inviteEvent($input);
            self::sendNotificationEmail('eventInvitation', $input);
            $this->model->deleteEventInvitationRequest((int)$_POST['id']);
        } elseif(isset($_POST['denyEventInvitationRequest'])) {
            $this->model->deleteEventInvitationRequest((int)$_POST['id']);
        }

    }

    private function display($template) {

        switch($template) {

            case 'view':
                echo $this->view->loadEventViewTemplate();
                break;
            case 'edit':
                echo $this->view->loadEventEditTemplate();
                break;
            case 'create':
                echo $this->view->loadEventCreateTemplate();
                break;
            case 'delete':
                echo $this->view->loadEventDeleteTemplate();
                break;
            case 'viewUnreadComment':
                echo $this->view->loadEventUnreadCommentTemplate();
                break;
            case 'invite':
                echo $this->view->loadEventInviteTemplate();
                break;
            case 'mutual':
                echo $this->view->loadEventMutualTemplate();
                break;
            case 'all':
                echo $this->view->loadEventAllTemplate();
                break;
            case 'inviteUser':
                echo $this->view->loadEventInviteUserTemplate();
                break;
            case 'search':
                echo $this->view->loadEventSearchTemplate();
                break;
            case 'showInvitation':
                echo $this->view->loadEventShowInvitationTemplate();
                break;
            case 'eventMap':
                echo $this->view->loadEventMapTemplate();
                break;
            case 'viewEventInvitationRequest':
                echo $this->view->loadEventInvitationRequestTemplate();
                break;
            default:
                break;

        }

    }

    private function prepareInput() {

        if(empty($_POST['remember'])) {
            $this->input['remember'] = 0;
        } else {
            $this->input['remember'] = $_POST['remember'];
        }

        if(empty($_POST['adjustable'])) {
            $this->input['adjustable'] = 'flexible';
        } else {
            $this->input['adjustable'] = $_POST['adjustable'];
        }

        if(!empty($_POST['dateFrom'])) {

            $dateFromArray = explode('/', $_POST['dateFrom']);
            $this->input['dateFrom'] = $dateFromArray[2].'-'.$dateFromArray[1].'-'.$dateFromArray[0];

        } else {
            $this->input['dateFrom'] = NULL;
        }

    }

    private function checkError() {

        if(empty($this->input['name']) || empty($this->input['dateFrom'])) {
            $this->error = true;
        } else {
            $this->error = false;
        }

    }

    private function sendEmailInvitation() {

        if(!empty($this->input['email'])) {

            $this->email = new application_frontend_email_controller();
            $emailContent['name'] = $this->user['myinfo']['name'];
            $emailContent['eid'] = $this->input['eid'];
            $emailContent['hash'] = md5($this->input['ueid']);

            if(strpos(';', $this->input['email']) === false) {
                $this->email->sendEmail($this->input['email'], 'eventInvite', $emailContent);
                $this->model->saveEmailInvitation($this->input['ueid'], $this->input['eid'], $this->input['email'], $emailContent);
            } else {

                $emailArray = explode(';', $this->input['email']);
                
                foreach($emailArray as $email) {

                    if(!empty($email)) {
                        $this->email->sendEmail($email, 'eventInvite', $emailContent);
                        $this->model->saveEmailInvitation($this->input['ueid'], $this->input['eid'], $email, $emailContent);
                    }
                    
                }

            }

        }

    }

    private function inviteFriend() {

        $friendArrayLength = count($this->input['friends']);

        for($i = 0; $i < $friendArrayLength; $i++) {

            $input = array('eid' => $this->input['eid'], 'ueid' => $this->input['ueid'], 'inviter' => $this->user['myuid'], 'receiver' => $this->input['friends'][$i]);
            
            if($input['receiver'] != 0 && $this->model->checkInvited($input) === false) {
                $this->model->inviteEvent($input);
                self::sendNotificationEmail('eventInvitation', $input);
            }

        }

    }

    private function checkInvitedFriend($friendArray, $invitedArray, $joinedArray) {

        if(!empty($friendArray) && !empty($invitedArray)) {
            $friendArray = array_diff($friendArray, $invitedArray);
        }
        if(!empty($joinedArray) && !empty($friendArray)) {
            $friendJoinedArray = array_intersect($friendArray, $joinedArray);
        }
        if(!empty($friendArray) && !empty($friendJoinedArray)) {
            $friendArray = array_diff($friendArray, $friendJoinedArray);
        }
        sort($friendArray);

        return $friendArray;

    }

    private function createEventFolder($eid) {
        mkdir('data/users/'.$this->user['md5'].'/event/'.$eid, 0777);
    }

    private function uploadEventImage($eid) {

        $filename = $_FILES['image']['name'];
        $filesize = $_FILES['image']['size'];
        $filetype = $_FILES['image']['type'];

        if($filename == '') {
            $uploadCheck = false;
        } elseif($filesize > 1048576) {
            $uploadCheck = false;
        } elseif($filetype != 'image/jpeg' && $filetype != 'image/gif' && $filetype != 'image/png') {
            $uploadCheck = false;
        } else {

            $fileInfo = getimagesize($_FILES['image']['tmp_name']);

            if($fileInfo['mime'] != 'image/jpeg' && $fileInfo['mime'] != 'image/gif' && $fileInfo['mime'] != 'image/png') {
                $uploadCheck = false;
            } else {
                $uploadCheck = true;
            }

        }

        if($uploadCheck) {

            $resizeImage = new library_default_classes_resizeImage($this->user, $fileInfo, $_FILES['image']['tmp_name']);
            $resizeImage->resizeImage('eventImage', $eid);
            $resizeImage->resizeImage('eventImageSmall', $eid);
            sleep(1);

        } elseif(file_exists('data/users/'.$this->user['md5'].'/event/'.$eid.'/eventImage.jpg') === false) {
            copy('public/templates/default/images/eventImage.jpg', 'data/users/'.$this->user['md5'].'/event/'.$eid.'/eventImage.jpg');
            chmod('data/users/'.$this->user['md5'].'/event/'.$eid.'/eventImage.jpg', 0755);
            copy('public/templates/default/images/eventImageSmall.jpg', 'data/users/'.$this->user['md5'].'/event/'.$eid.'/eventImageSmall.jpg');
            chmod('data/users/'.$this->user['md5'].'/event/'.$eid.'/eventImageSmall.jpg', 0755);
        }

    }

    private function prepareEventMoveData() {

        $this->input['top'] = str_replace('px', '', $this->input['top']);
        $this->input['height'] = str_replace('px', '', $this->input['height']);
        $this->input['ueid'] = str_replace('event', '', $this->input['ueid']);

        if($this->input['top'] > 0) {
            $this->input['top'] = ($this->input['top'])/27;
        } else {
            $this->input['top'] = 0;
        }

        if($this->input['left'] != 0) {
            $this->input['left'] = $this->input['left']/136;
        } else {
            $this->input['left'] = 0;
        }

        $this->input['height'] = ($this->input['height'] + 3)/27;
        $this->input['bottom'] = $this->input['top'] + $this->input['height'];
        $this->input['top'] = str_pad($this->input['top'], 2, '0', STR_PAD_LEFT).':00';
        $this->input['bottom'] = str_pad($this->input['bottom'], 2, '0', STR_PAD_LEFT).':00';

    }

    public function checkEventEmailInvitation($uid) {

        if(isset($_SESSION['eventInvite']) && !empty($_SESSION['eventInvite'])) {

            application_frontend_event_model::saveEventInvitation($_SESSION['eventInvite']['ueid'], $_SESSION['eventInvite']['eid'], $_SESSION['eventInvite']['inviter'], $uid);
            application_frontend_event_model::deleteEventEmailInvitation($_SESSION['eventInvite']['receiver'], $_SESSION['eventInvite']['ueid'], $_SESSION['eventInvite']['inviter']);

            unset($_SESSION['eventInvite']);

        }

    }

    private function sendNotificationEmail($content, $input) {

        switch($content) {

            case 'eventInvitation':

                $sendEmailTo = $this->model->getSendEmailUser($input['receiver']);
                if($sendEmailTo['eventInvite'] == 1) {

                    $emailContent['eid'] = $input['eid'];
                    $emailContent['receiver'] = $sendEmailTo['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email = new application_frontend_email_controller();
                    $this->email->sendEmail($sendEmailTo['email'], 'newEventInvite', $emailContent);

                }
                break;
            case 'eventUpdate':

                $this->email = new application_frontend_email_controller();
                $sendEmailToArray = $this->model->getEmailEventUser($input['eid'], $this->user['myuid']);

                foreach($sendEmailToArray as $sendEmailTo) {

                    $emailContent = $input;
                    $emailContent['eid'] = $input['eid'];
                    $emailContent['receiver'] = $sendEmailTo['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email->sendEmail($sendEmailTo['email'], 'eventUpdate', $emailContent);

                }
                break;

        }

    }

    private function checkEventUpdate($input, $eid) {

        $event = $this->model->getEventUpdate($eid);
        $update = array();
        $sendEmail = false;

        if($event['name'] != $input['name']) {
            $sendEmail = true;
            $update['name'] = $input['name'];
            $update['nameOld'] = $event['name'];
        }
        if($event['address'] != $input['address']) {
            $sendEmail = true;
            $update['address'] = $input['address'];
            $update['addressOld'] = $event['address'];
        }
        if($event['location'] != $input['location']) {
            $sendEmail = true;
            $update['location'] = $input['location'];
            $update['locationOld'] = $event['location'];
        }
        if($event['dateFrom'] != $input['dateFrom']) {
            $sendEmail = true;
            $update['dateFrom'] = $input['dateFrom'];
            $update['dateFromOld'] = $event['dateFrom'];
        }
        if($event['timeFrom'] != $input['timeFrom']) {
            $sendEmail = true;
            $update['timeFrom'] = $input['timeFrom'];
            $update['timeFromOld'] = $event['timeFrom'];
        }
        if($event['timeTo'] != $input['timeTo']) {
            $sendEmail = true;
            $update['timeTo'] = $input['timeTo'];
            $update['timeToOld'] = $event['timeTo'];
        }

        if($sendEmail) {

            $update['eid'] = $eid;
            self::sendNotificationEmail('eventUpdate', $update);
            
        }

    }

}

?>