<?php

class application_frontend_event_model extends library_default_classes_model {

    public function getUserEvent($ueid) {

        $query = "select u.firstname, u.lastname, ue.uid, ue.eid, ue.attending, e.owner, e.name, e.address, e.adjustable, e.location, e.website, e.description, e.joinable, e.searchable, ue.dateFrom, ue.timeFrom, ue.timeTo, ue.privacy, ue.remember, ue.modify
                    from userEvent as ue, event as e, users as u where e.owner = u.uid and ue.eid = e.eid and ue.id = ".$ueid;
        $queryResult = parent::query($query);
        $event = NULL;

        if(parent::rowExist($queryResult)) {
            $event = parent::fetchArray($queryResult);
            $event['commentCount'] = self::getCommentCount($event['eid']);
        }
        
        return $event;

    }

    public function getEvent($eid) {

        $query = "select eid,  owner, name, address, adjustable, location, website, description, dateFrom, timeFrom, timeTo, joinable, searchable
                    from event where eid = ".$eid;
        $queryResult = parent::query($query);
        $event = NULL;

        if(parent::rowExist($queryResult)) {
            $event = parent::fetchArray($queryResult);
        }

        return $event;

    }

    public function getEventUpdate($eid) {

        $query = "select name, address, location, dateFrom, timeFrom, timeTo from event where eid = ".$eid;
        $queryResult = parent::query($query);
        $event = NULL;

        if(parent::rowExist($queryResult)) {
            $event = parent::fetchArray($queryResult);
        }

        return $event;
        
    }

    public function getCommentCount($eid) {

        $query = "select count(ecid) as commentCount from commentEvent where eid = ".$eid;
        $queryResult = parent::query($query);
        $commentCount = 0;

        if(parent::rowExist($queryResult)) {
            $commentCount = parent::queryResult($queryResult, 'commentCount');
        }
        return $commentCount;

    }

    public function getAttenders($eid) {

        $query = "select u.uid, u.firstname, u.lastname from users as u, userEvent as ue where u.uid = ue.uid and ue.eid = ".$eid." and ue.privacy >= ".$this->user['relation']." group by ue.uid order by modify desc limit 0, 20";
        $queryResult = parent::query($query);
        $attenders = array();      

        while($result = parent::fetchArray($queryResult)) {
            $attenders[] = array('uid' => $result['uid'], 'name' => $result['firstname'].' '.$result['lastname']);
        }

        $attenders['count'] = (int)self::getAttenderCount($eid);
        return $attenders;

    }

    public function getAttenderCount($eid) {

        $query = "select uid from userEvent where eid = ".$eid." group by uid";
        $queryResult = parent::query($query);
        $attenderCount = 0;

        if(parent::rowExist($queryResult)) {
            $attenderCount = parent::numRows($queryResult);
        }

        return $attenderCount;

    }

    public function getUserAttendingId($uid, $eid) {

        $query = "select id from userEvent where uid = ".$uid." and eid = ".$eid." order by modify desc limit 0, 1";
        $queryResult = parent::query($query);
        $ueid = NULL;

        if(parent::rowExist($queryResult)) {
            $ueid = parent::queryResult($queryResult, 'id');
        }

        return $ueid;

    }

    public function getUserEventByEid($eid) {

        $query = "select u.firstname, u.lastname, u.uid, e.eid, e.owner, e.name, e.address, e.adjustable, e.location, e.website, e.description, e.joinable, e.searchable, e.dateFrom, e.timeFrom, e.timeTo, e.modify
                    from event as e, users as u where e.owner = u.uid and e.eid = ".$eid;
        $queryResult = parent::query($query);
        $event = NULL;

        if(parent::rowExist($queryResult)) {
            $event = parent::fetchArray($queryResult);
            $event['commentCount'] = self::getCommentCount($event['eid']);
        }

        return $event;

    }

    public function saveEvent($input) {

        $query = "insert into event (owner, name, address, adjustable, location, website, description, dateFrom, timeFrom, timeTo, joinable, searchable, modify)
                    values ('".$this->user['uid']."',
                            '".$input['name']."',
                            '".$input['address']."',
                            '".$input['adjustable']."',
                            '".$input['location']."',
                            '".$input['website']."',
                            '".$input['description']."',
                            '".$input['dateFrom']."',
                            '".$input['timeFrom']."',
                            '".$input['timeTo']."',
                            '".$input['joinable']."',
                            '".$input['searchable']."',
                            now())";
        parent::query($query);

        $eid = mysql_insert_id();
        self::saveUserEvent($input, $eid);

        return $eid;

    }

    public function updateEvent($input, $eid) {

        $query = "update event set name = '".$input['name']."',
                                    address = '".$input['address']."',
                                    adjustable = '".$input['adjustable']."',
                                    location = '".$input['location']."',
                                    website = '".$input['website']."',
                                    description = '".$input['description']."',
                                    dateFrom = '".$input['dateFrom']."',
                                    timeFrom = '".$input['timeFrom']."',
                                    timeTo = '".$input['timeTo']."',
                                    joinable = '".$input['joinable']."',
                                    searchable = '".$input['searchable']."',
                                    modify = now()
                                    where eid = ".$eid." and owner = ".$this->user['myuid'];
        parent::query($query);

    }

    public function changeEvent($input, $ueid) {
        
        $query = "update userEvent set attending = '".$input['attend']."',
                                        dateFrom = '".$input['dateFrom']."',
                                        timeFrom = '".$input['timeFrom']."',
                                        timeTo = '".$input['timeTo']."',
                                        privacy = '".$input['privacy']."',
                                        remember = '".$input['remember']."',
                                        modify = now()
                                        where id = ".$ueid." and uid = ".$this->user['myuid'];
        parent::query($query);

    }

    public function duplicateEvent($input, $ueid, $eid) {

        $query = "insert into userEvent (uid, eid, attending, dateFrom, timeFrom, timeTo, privacy, remember, commentReaded, modify)
                   values ('".$this->user['myuid']."', '".$eid."', '".$input['attend']."', '".$input['dateFrom']."', '".$input['timeFrom']."', '".$input['timeTo']."', '".$input['privacy']."', '".$input['remember']."', 1, now())";

        parent::query($query);

    }

    public function deleteEvent($ueid) {

        $query = "delete from userEvent where id = ".$ueid." and uid = ".$this->user['myuid'];
        parent::query($query);

    }

    private function saveUserEvent($input, $eid) {

        if($input['repeating'] == 'no') {

            $query = "insert into userEvent (uid, eid, attending, dateFrom, timeFrom, timeTo, privacy, remember, modify)
                        values ('".$this->user['uid']."',
                                '".$eid."',
                                '".$input['attend']."',
                                '".$input['dateFrom']."',
                                '".$input['timeFrom']."',
                                '".$input['timeTo']."',
                                '".$input['privacy']."',
                                '".$input['remember']."',
                                now())";
            parent::query($query);

        } elseif($input['repeating'] == 'daily') {

            $days = (int)$input['days'];

            for($i = 0; $i < $days; $i++) {

                $dateFrom = explode('-', $input['dateFrom']);
                $dateFrom = date('Y-m-d', mktime(0, 0, 0, (int)$dateFrom[1], (int)$dateFrom[2] + (int)$i, (int)$dateFrom[0]));

                $query = "insert into userEvent (uid, eid, attending, dateFrom, timeFrom, timeTo, privacy, remember, modify)
                            values ('".$this->user['uid']."',
                                    '".$eid."',
                                    '".$input['attend']."',
                                    '".$dateFrom."',
                                    '".$input['timeFrom']."',
                                    '".$input['timeTo']."',
                                    '".$input['privacy']."',
                                    '".$input['remember']."',
                                    now())";
                parent::query($query);

            }

        } elseif($input['repeating'] == 'weekly') {

            $weeks = (int)$input['weeks'];
            $a = 0;

            for($i = 0; $i < $weeks; $i++) {               

                $dateFrom = explode('-', $input['dateFrom']);
                $dateFrom = date('Y-m-d', mktime(0, 0, 0, (int)$dateFrom[1], (int)$dateFrom[2] + (int)$a, (int)$dateFrom[0]));

                $query = "insert into userEvent (uid, eid, attending, dateFrom, timeFrom, timeTo, privacy, remember, modify)
                            values ('".$this->user['uid']."',
                                    '".$eid."',
                                    '".$input['attend']."',
                                    '".$dateFrom."',
                                    '".$input['timeFrom']."',
                                    '".$input['timeTo']."',
                                    '".$input['privacy']."',
                                    '".$input['remember']."',
                                    now())";
                parent::query($query);
                $a += 7;

            }

        }

    }

    public function joinEvent($input, $eid) {

        $query = "insert into userEvent (uid, eid, attending, dateFrom, timeFrom, timeTo, privacy, remember, modify)
                    values ('".$this->user['myuid']."',
                            '".$eid."',
                            '".$input['attend']."',
                            '".$input['dateFrom']."',
                            '".$input['timeFrom']."',
                            '".$input['timeTo']."',
                            '".$input['privacy']."',
                            '".$input['remember']."',
                            now())";
        parent::query($query);

    }

    public function deleteEventInvitation($eid) {

        $query = "delete from eventInvitation where eid = ".$eid." and receiver = ".$this->user['myuid'];
        parent::query($query);

    }

    public function inviteEvent($input) {

        $query = "insert into eventInvitation (eid, ueid, inviter, receiver, created) values ('".$input['eid']."', '".$input['ueid']."', '".$input['inviter']."', '".$input['receiver']."', now())";
        parent::query($query);

    }

    public function getJoinedFriends($ueid) {

        $query = "select uid from userEvent where eid in (select eid from userEvent where id= ".$ueid.")";
        $queryResult = parent::query($query);
        $joinedArray = array();

        while($result = parent::fetchArray($queryResult)) {
            $joinedArray[] = $result['uid'];
        }

        return $joinedArray;

    }

    public function getInvitedFriends($uid, $ueid) {

        $query = "select receiver from eventInvitation where inviter = ".$uid." and ueid = ".$ueid;
        $queryResult = parent::query($query);
        $invitedArray = array();

        while($result = parent::fetchArray($queryResult)) {
            $invitedArray[] = $result['receiver'];
        }

        return $invitedArray;

    }

    private function getDateFromUeid($ueid, $eid) {

        $query = "select dateFrom, timeFrom, timeTo from userEvent where id = ".$ueid;
        $queryResult = parent::query($query);
        $eventDate = array('dateFrom' => NULL, 'timeFrom' => NULL, 'timeTo' => NULL);

        if(parent::rowExist($queryResult)) {

            $eventDate['dateFrom'] = parent::queryResult($queryResult, 'dateFrom');
            $eventDate['timeFrom'] = parent::queryResult($queryResult, 'timeFrom');
            $eventDate['timeTo'] = parent::queryResult($queryResult, 'timeTo');

        } else {

            $query = "select dateFrom, timeFrom, timeTo from event where eid = ".$eid;
            $queryResult = parent::query($query);

            if(parent::rowExist($queryResult)) {

                $eventDate['dateFrom'] = parent::queryResult($queryResult, 'dateFrom');
                $eventDate['timeFrom'] = parent::queryResult($queryResult, 'timeFrom');
                $eventDate['timeTo'] = parent::queryResult($queryResult, 'timeTo');

            }

        }

        return $eventDate;

    }

    public function getInvitation($uid) {

        $query = "select e.name, e.eid, e.adjustable, e.owner, ei.ueid, ei.id, u.uid, u.firstname, u.lastname
                    from event as e, eventInvitation as ei, users as u
                    where ei.inviter = u.uid and e.eid = ei.eid and ei.receiver = ".$uid." order by ei.created desc";
        $queryResult = parent::query($query);
        $invitationList = array();

        while($result = parent::fetchArray($queryResult)) {

            $eventDate = self::getDateFromUeid($result['ueid'], $result['eid']);

            $invitationList[] = array('iid' => $result['id'],
                                        'owner' => $result['owner'],
                                        'eventName' => $result['name'],
                                        'adjustable' => $result['adjustable'],
                                        'eid' => $result['eid'],
                                        'joinedCount' => self::getJoinedCount($result['eid']),
                                        'ueid' => $result['ueid'],
                                        'name' => $result['firstname'].' '.$result['lastname'],
                                        'uid' => $result['uid'],
                                        'dateFrom' => $eventDate['dateFrom'],
                                        'timeFrom' => $eventDate['timeFrom'],
                                        'timeTo' => $eventDate['timeTo']);
        }

        return $invitationList;

    }

    private function getJoinedCount($eid) {

        $query = "select id from userEvent where eid = ".$eid." group by uid";
        $queryResult = parent::query($query);
        $joinedCount = array();

        while($result = parent::fetchArray($queryResult)) {
            $joinedCount[] = $result['id'];
        }

        return count($joinedCount);

    }

    public function getInvitationCount($uid) {

        $query = "select count(id) as invitationCount from eventInvitation where receiver = ".$uid;
        $queryResult = parent::query($query);
        $invitationCount = 0;

        if(parent::rowExist($queryResult)) {
            $invitationCount = parent::queryResult($queryResult, 'invitationCount');
        }

        return $invitationCount;

    }

    public function rejectInvitation($iid) {

        $query = "delete from eventInvitation where id = ".$iid;
        parent::query($query);

    }

    public function checkEventAttendingStatus($uid, $eid) {

        $query = "select id from userEvent where uid = ".$uid." and eid = ".$eid;
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    public function saveEventMovesData($input) {

        $query = "update userEvent set timeFrom = '".$input['top']."', timeTo = '".$input['bottom']."', dateFrom = date_add(dateFrom, interval ".$input['left']." day) where id = ".$input['ueid']." and uid = ".$this->user['myuid'];
        parent::query($query);

    }
    
    public function saveEmailInvitation($ueid, $eid, $email, $hash) {

        $query = "insert into eventEmailInvitation (eid, ueid, inviter, receiver, hash, created) values ('".$eid."', '".$ueid."', '".$this->user['myuid']."', '".$email."', '".$hash['hash']."', now())";
        parent::query($query);

    }

    public function getPastEventCreatedOfUser($user) {

        if($user['page']) {
            $query = "select eid, name, owner, description, dateFrom, timeFrom, timeTo from event where owner = ".$user['uid']." and dateFrom < now() order by dateFrom desc limit 0, 100";
        } else {
            $query = "select eid, name, owner, description, dateFrom, timeFrom, timeTo from event where owner = ".$user['uid']." and searchable = 1 and dateFrom < now() order by dateFrom desc limit 0, 100";
        }
        
        $queryResult = parent::query($query);
        $eventArray = array();

        while($result = parent::fetchArray($queryResult)) {
            
            $eventArray[] = array('eid' => $result['eid'],
                                    'name' => $result['name'],
                                    'owner' => $result['owner'],
                                    'description' => $result['description'],
                                    'dateFrom' => $result['dateFrom'],
                                    'timeFrom' => $result['timeFrom'],
                                    'timeTo' => $result['timeTo']);
        }

        return $eventArray;

    }

    public function getPastEventAttendingOfUser($user) {

        if($user['page']) {
            $query = "select ue.eid, e.name, e.owner, e.description, ue.dateFrom, ue.timeFrom, ue.timeTo from event as e, userEvent as ue where ue.eid = e.eid and ue.uid = ".$user['uid']." and ue.dateFrom < now() order by ue.dateFrom desc limit 0, 100";
        } else {
            $query = "select ue.eid, e.name, e.owner, e.description, ue.dateFrom, ue.timeFrom, ue.timeTo from event as e, userEvent as ue where ue.eid = e.eid and ue.uid = ".$user['uid']." and ue.privacy >= ".$user['relation']." and ue.dateFrom < now() order by ue.dateFrom desc limit 0, 100";
        }

        $queryResult = parent::query($query);
        $eventArray = array();

        while($result = parent::fetchArray($queryResult)) {

            $eventArray[] = array('eid' => $result['eid'],
                                    'name' => $result['name'],
                                    'owner' => $result['owner'],
                                    'description' => $result['description'],
                                    'dateFrom' => $result['dateFrom'],
                                    'timeFrom' => $result['timeFrom'],
                                    'timeTo' => $result['timeTo']);
        }

        return $eventArray;

    }

    public function getFutureEventCreatedOfUser($user) {

        if($user['page']) {
            $query = "select eid, name, owner, description, dateFrom, timeFrom, timeTo from event where owner = ".$user['uid']." and dateFrom >= now() order by dateFrom limit 0, 100";
        } else {
            $query = "select eid, name, owner, description, dateFrom, timeFrom, timeTo from event where owner = ".$user['uid']." and searchable = 1 and dateFrom >= now() order by dateFrom limit 0, 100";
        }

        $queryResult = parent::query($query);
        $eventArray = array();

        while($result = parent::fetchArray($queryResult)) {

            $eventArray[] = array('eid' => $result['eid'],
                                    'name' => $result['name'],
                                    'owner' => $result['owner'],
                                    'description' => $result['description'],
                                    'dateFrom' => $result['dateFrom'],
                                    'timeFrom' => $result['timeFrom'],
                                    'timeTo' => $result['timeTo']);
        }

        return $eventArray;

    }

    public function getFutureEventAttendingOfUser($user) {

        if($user['page']) {
            $query = "select ue.eid, e.name, e.owner, e.description, ue.dateFrom, ue.timeFrom, ue.timeTo from event as e, userEvent as ue where ue.eid = e.eid and ue.uid = ".$user['uid']." and ue.dateFrom > now() order by ue.dateFrom limit 0, 100";
        } else {
            $query = "select ue.eid, e.name, e.owner, e.description, ue.dateFrom, ue.timeFrom, ue.timeTo from event as e, userEvent as ue where ue.eid = e.eid and ue.uid = ".$user['uid']." and ue.privacy >= ".$user['relation']." and ue.dateFrom > now() order by ue.dateFrom limit 0, 100";
        }

        $queryResult = parent::query($query);
        $eventArray = array();

        while($result = parent::fetchArray($queryResult)) {

            $eventArray[] = array('eid' => $result['eid'],
                                    'name' => $result['name'],
                                    'owner' => $result['owner'],
                                    'description' => $result['description'],
                                    'dateFrom' => $result['dateFrom'],
                                    'timeFrom' => $result['timeFrom'],
                                    'timeTo' => $result['timeTo']);
        }

        return $eventArray;

    }

    public function saveEventInvitation($ueid, $eid, $inviter, $uid) {

        $query = "insert into eventInvitation (eid, ueid, inviter, receiver, created) values ('".$eid."', '".$ueid."', '".$inviter."', '".$uid."', now())";
        parent::query($query);

    }

    public function deleteEventEmailInvitation($receiver, $ueid, $inviter) {

        $query = " delete from eventEmailInvitation where receiver = '".$receiver."' and ueid = ".$ueid." and inviter = ".$inviter;
        parent::query($query);

    }

    public function getEventOfThisWeek($uid) {

        $firstDayOfWeek = date("Y-m-d", time()-((date("N")-1)*86400));
        $lastDayOfWeek = date("Y-m-d", time()+((7-date("N"))*86400));

        $query = "select count(id) as event_count from userEvent where privacy > 0 and dateFrom >= '".$firstDayOfWeek."'  and dateFrom <= '".$lastDayOfWeek."' and uid = ".$uid;
        $queryResult = parent::query($query);
        $eventCount = 0;

        if(parent::rowExist($queryResult)) {
            $eventCount = parent::queryResult($queryResult, 'event_count');
        }

        return $eventCount;

    }

    public function getEventNameListAutoComplete($uid, $eventName) {

        $query = "select e.eid, e.name, ue.id, ue.dateFrom, ue.timeFrom, ue.timeTo from event as e, userEvent as ue where e.eid = ue.eid and ue.uid = ".$uid." and (e.joinable < 2 or e.owner = ".$uid.") and e.name like '%".$eventName."%' group by e.eid order by e.dateFrom desc limit 0, 20";
        $queryResult = parent::query($query);
        $eventNameList = NULL;

        while($result = parent::fetchAssoc($queryResult)) {
            $eventNameList .= $result['name'].'|'.$result['id'].'|'.date('d F', strtotime($result['dateFrom'])).'|'.$result['timeFrom'].'|'.$result['timeTo'].'|'.$result['eid']."\n";
        }

        return $eventNameList;

    }

    public function getUnreadEventCommentCount($uid) {

        $query = "select id from userEvent where uid = ".$uid." and commentReaded = 0 group by eid";
        $queryResult = parent::query($query);
        $eventCommentCount = 0;
        $eventComment = array();

        while($result = parent::fetchArray($queryResult)) {
            $eventComment[] = $result['id'];
        }

        $eventCommentCount = count($eventComment);
        return $eventCommentCount;

    }

    public function getUnreadEventComment($uid) {

        $query = "select eid, id from userEvent where commentReaded = 0 and uid = ".$uid." group by eid order by modify desc";
        $queryResult = parent::query($query);
        $unreadEventComment = array();
        
        while($result = parent::fetchAssoc($queryResult)) {

            $querySecong = "select u.firstname, u.lastname, u.uid, e.name, e.eid, ce.comment, ce.created from users as u, event as e, commentEvent as ce
                                where ce.wid = u.uid and e.eid = ce.eid and ce.wid != ".$uid." and ce.eid = ".$result['eid']." order by ce.created desc limit 0, 1";
            $queryResultSecond = parent::query($querySecong);

            while($resultSecond = parent::fetchAssoc($queryResultSecond)) {
                $unreadEventComment[] = array('uid' => $resultSecond['uid'], 'name' => $resultSecond['firstname'].' '.$resultSecond['lastname'], 'eventName' => $resultSecond['name'], 'eid' => $result['eid'], 'ueid' => $result['id'], 'comment' => $resultSecond['comment'], 'created' => date('d F \a\t H:i', strtotime($resultSecond['created'])));
            }

        }

        return $unreadEventComment;

    }

    public function saveRequestEventInvitation($eid, $ueid) {

        if($ueid == 'false') {

            $userEvent = self::getUeidFromEid($eid);

            if($userEvent['ueid'] !== false && $userEvent['uid'] !== false) {

                if(self::checkEventRequestExist($userEvent['ueid'], $eid, $userEvent['uid']) === false) {
                    $query = "insert into eventInvitationRequest (eid, ueid, request, accept, created) values ('".$eid."', '".$userEvent['ueid']."', '".$this->user['myuid']."', '".$userEvent['uid']."', now())";
                    parent::query($query);
                }

            }
            
        } else {

            $accept = self::getAcceptFromUeid($eid, $ueid);

            if($accept !== false) {

                if(self::checkEventRequestExist($ueid, $eid, $accept) === false) {
                    $query = "insert into eventInvitationRequest (eid, ueid, request, accept, created) values ('".$eid."', '".$ueid."', '".$this->user['myuid']."', '".$accept."', now())";
                    parent::query($query);
                }

            }
        }

    }

    private function checkEventRequestExist($ueid, $eid, $accept) {

        $query = "select id from eventInvitationRequest where eid = ".$eid." and ueid = ".$ueid." and accept = ".$accept;
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    private function getAcceptFromUeid($eid, $ueid) {

        $query = "select owner, joinable from event as ue where eid = ".$eid;
        $queryResult = parent::query($query);
        $accept = false;

        if(parent::rowExist($queryResult)) {

            $owner = parent::queryResult($queryResult, 'owner');
            $joinable = parent::queryResult($queryResult, 'joinable');

            if($joinable < 1) {
                $accept = $owner;
            } else {
                $query = "select uid from userEvent where id = ".$ueid;
                $queryResult = parent::query($query);

                if(parent::rowExist($queryResult)) {
                    $accept = parent::queryResult($queryResult, 'uid');
                }
            }

        }

        return $accept;

    }

    private function getUeidFromEid($eid) {

        $query = "select ue.id, ue.uid from userEvent as ue, event as e where e.owner = ue.uid and ue.eid = ".$eid;
        $queryResult = parent::query($query);
        $userEvent['ueid'] = false;
        $userEvent['uid'] = false;

        if(parent::rowExist($queryResult)) {
            $userEvent['ueid'] = parent::queryResult($queryResult, 'id');
            $userEvent['uid'] = parent::queryResult($queryResult, 'uid');
        }

        return $userEvent;

    }

    public function getRequestEventInvitationCount($uid) {

        $query = "select count(id) as requestInvitationCount from eventInvitationRequest where accept = ".$uid;
        $queryResult = parent::query($query);
        $requestInvitationCount = 0;

        if(parent::rowExist($queryResult)) {
            $requestInvitationCount = parent::queryResult($queryResult, 'requestInvitationCount');
        }

        return $requestInvitationCount;

    }

    public function getEventRequestInfo($uid) {

        $query = "select u.firstname, u.lastname, e.name, eir.id, eir.eid, eir.ueid, eir.request, eir.created 
                    from users as u, eventInvitationRequest as eir, event as e where eir.request = u.uid and eir.eid = e.eid and eir.accept = ".$uid." order by eir.created";
        $queryResult = parent::query($query);
        $eventRequest = array();

        while($result = parent::fetchArray($queryResult)) {
            $eventRequest[] = array('id' => $result['id'], 'eid' => $result['eid'], 'ueid' => $result['ueid'], 'name' => $result['firstname'].' '.$result['lastname'], 'eventName' => $result['name'], 'request' => $result['request'], 'created' => date('d F \a\t H:i', strtotime($result['created'])));
        }

        return $eventRequest;

    }

    public function deleteEventInvitationRequest($id) {

        $query = "delete from eventInvitationRequest where id = ".$id;
        parent::query($query);

    }

    public function checkInvited($input) {

        $query = "select id from eventInvitation where ueid = ".$input['ueid']." and eid = ".$input['eid']." and inviter = ".$input['inviter']." and receiver = ".$input['receiver'];
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    public function getMutualEvents($myuid, $uid) {

        $query = "select ue.eid, e.name, e.address, e.location, e.owner from userEvent as ue, event as e where ue.eid = e.eid and ue.uid = ".$myuid." and ue.eid in(select eid from userEvent where uid = ".$uid.") group by ue.eid order by ue.dateFrom desc";
        $queryResult = parent::query($query);
        $mutualEvents = array();

        while($result = parent::fetchArray($queryResult)) {
            $mutualEvents[] = array('eid' => $result['eid'], 'name' => $result['name'], 'address' => $result['address'], 'location' => $result['location'], 'owner' => $result['owner']);
        }

        return $mutualEvents;

    }

    public function getSendEmailUser($uid) {

        $query = "select u.email, u.firstname, ns.eventInvite from users as u, notificationSetting as ns where u.uid = ns.uid and u.uid = ".$uid;
        $queryResult = parent::query($query);
        $user = array();

        if(parent::rowExist($queryResult)) {
            $user = array('eventInvite' => parent::queryResult($queryResult, 'eventInvite'), 'email' => parent::queryResult($queryResult, 'email'), 'firstname' => parent::queryResult($queryResult, 'firstname'));
        }

        return $user;

    }

    public function getEmailEventUser($eid, $uid) {

        $query = "select u.email, u.uid, u.firstname from users as u, userEvent as ue, notificationSetting as ns where u.uid = ns.uid and u.uid = ue.uid and ns.eventUpdate = 1 and ue.uid != ".$uid." and ue.eid = ".$eid." and ue.dateFrom > now() group by u.uid";
        $queryResult = parent::query($query);
        $sendEmailToArray = array();

        while($result = parent::fetchArray($queryResult)) {
            $sendEmailToArray[] = array('uid' => $result['uid'], 'firstname' => $result['firstname'], 'email' => $result['email']);
        }

        return $sendEmailToArray;

    }

}

?>