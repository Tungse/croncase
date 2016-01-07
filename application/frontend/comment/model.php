<?php

class application_frontend_comment_model extends library_default_classes_model {

    public function saveComment($input) {

        $query = "insert into comments (wid, uid, comment, created) values ('".$this->user['myuid']."', '".$input['uid']."', '".$input['comment']."', now())";
        parent::query($query);

    }

    public function saveCommentEvent($input) {

        $query = "insert into commentEvent (eid, wid, comment, created) values ('".$input['eid']."', '".$this->user['myuid']."', '".$input['comment']."', now())";
        parent::query($query);

        $query = "update userEvent set commentReaded = 0 where eid = ".$input['eid']." and uid != ".$this->user['myuid'];
        parent::query($query);

    }

    public function saveCommentSub($input) {

        $query = "select cid from comments where cid = ".$input['cid']." and uid = ".$input['uid'];
        $queryResult = parent::query($query);

        if(parent::rowExist($queryResult)) {

            $query = "insert into commentSub (cid, wid, uid, comment, created) values ('".$input['cid']."', '".$this->user['myuid']."', '".$input['uid']."', '".$input['comment']."', now())";
            parent::query($query);

        }

    }

    public function getLastComment() {
        return $this->lastComment;
    }

    public function getComment() {

        $query = "select c.cid, c.wid, c.comment, c.created, u.firstname, u.lastname from comments as c, users as u where c.wid = u.uid and c.uid = ".$this->user['uid']." order by created desc limit 0, 10";
        $queryResult = parent::query($query);
        $comments = array();

        while($result = parent::fetchArray($queryResult)) {
            $comments[] = array('cid' => $result['cid'], 'wid' => $result['wid'], 'comment' => $result['comment'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'name' => $result['firstname'].' '.$result['lastname'], 'commentSub' => self::getCommentSub($result['cid']));           
        }

        $commentCount = count($comments);
        if($commentCount > 0 && $commentCount === 10) {
            $this->lastComment = $comments[$commentCount - 1]['cid'];
        } else {
            $this->lastComment = false;
        }

        return $comments;

    }

    public function getCommentEvent($eid) {

        $query = "select ce.ecid, ce.wid, ce.comment, ce.created, u.firstname, u.lastname from commentEvent as ce, users as u where ce.wid = u.uid and ce.eid = ".$eid." order by created desc";
        $queryResult = parent::query($query);
        $commentEvent = array();

        while($result = parent::fetchArray($queryResult)) {
            $commentEvent[] = array('ecid' => $result['ecid'], 'wid' => $result['wid'], 'comment' => $result['comment'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'name' => $result['firstname'].' '.$result['lastname']);
        }

        return $commentEvent;

    }

    public function getCommentEventNew($eid) {

        $query = "select ce.ecid, ce.wid, ce.comment, ce.created, u.firstname, u.lastname from commentEvent as ce, users as u where ce.wid = u.uid and ce.wid = ".$this->user['myuid']." and ce.eid = ".$eid." order by created desc limit 0, 1";
        $queryResult = parent::query($query);
        $commentEvent = array();

        while($result = parent::fetchArray($queryResult)) {
            $commentEvent = array('ecid' => $result['ecid'], 'wid' => $result['wid'], 'comment' => $result['comment'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'name' => $result['firstname'].' '.$result['lastname']);
        }

        return $commentEvent;

    }

    public function getStatusMessage($uid) {

        $query = "select cid, comment, created from comments where uid = ".$uid." and wid = ".$uid." and date_add(created, interval 5 day) > now() order by created desc limit 0,1";
        $queryResult = parent::query($query);
        $statusMessageArray = NULL;

        while($result = parent::fetchArray($queryResult)) {
            $statusMessageArray = array('cid' => $result['cid'], 'status' => $result['comment'], 'created' => date('d F \a\t H:i', strtotime($result['created'])));
        }

        return $statusMessageArray;

    }

    private function getCommentSub($cid) {

        $query = "select cs.id, cs.cid, cs.wid, cs.comment, cs.created, u.firstname, u.lastname from commentSub as cs, users as u where cs.wid = u.uid and cs.uid = ".$this->user['uid']." and cs.cid = ".$cid." order by created";
        $queryResult = parent::query($query);
        $commentsSub = array();

        while($result = parent::fetchArray($queryResult)) {
            $commentsSub[] = array('id' => $result['id'], 'cid' => $result['cid'], 'wid' => $result['wid'], 'comment' => $result['comment'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'name' => $result['firstname'].' '.$result['lastname']);
        }

        return $commentsSub;
        
    }

    public function deleteComment($input) {

        $query = "delete from comments where cid = ".$input['cid']." and (uid = ".$this->user['myuid']." or wid = ".$this->user['myuid'].")";
        parent::query($query);

        $query = "delete from commentSub where cid = ".$input['cid']." and (uid = ".$this->user['myuid']." or wid = ".$this->user['myuid'].")";
        parent::query($query);

    }

    public function deleteCommentEvent($input) {

        $query = "delete from commentEvent where ecid = ".$input['ecid']." and wid = ".$this->user['myuid'];
        parent::query($query);

    }

    public function deleteCommentSub($input) {

        $query = "delete from commentSub where id = ".$input['csid']." and (uid = ".$this->user['myuid']." or wid = ".$this->user['myuid'].")";
        parent::query($query);

    }

    public function readedEventComment($uid, $eid) {

        $query = "update userEvent set commentReaded = 1 where uid = ".$uid." and eid = ".$eid." and commentReaded = 0";
        parent::query($query);

    }

    public function getCommentMore($lastComment) {

        $query = "select c.cid, c.wid, c.comment, c.created, u.firstname, u.lastname from comments as c, users as u where c.wid = u.uid and c.cid < ".$lastComment." and c.uid = ".$this->user['uid']." order by created desc limit 0, 10";
        $queryResult = parent::query($query);
        $comments = array();

        while($result = parent::fetchArray($queryResult)) {
            $comments[] = array('cid' => $result['cid'], 'wid' => $result['wid'], 'comment' => $result['comment'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'name' => $result['firstname'].' '.$result['lastname'], 'commentSub' => self::getCommentSub($result['cid']));
        }

        $commentCount = count($comments);
        if($commentCount > 0 && $commentCount === 10) {
            $this->lastComment = $comments[$commentCount - 1]['cid'];
        } else {
            $this->lastComment = false;
        }

        return $comments;

    }

    public function checkNotificationAllow($field, $uid) {

        $query = "select ".$field." from notificationSetting where uid = ".$uid;
        $queryResult = parent::query($query);

        $notificationAllow = parent::queryResult($queryResult, $field);

        if($notificationAllow == 1) {
            return true;
        } else {
            return false;
        }

    }

    public function getEmailCommentUser($cid, $uid) {

        $query = "select u.email, u.uid, u.firstname from users as u, commentSub as cs, notificationSetting as ns where u.uid = cs.wid and u.uid = ns.uid and ns.commentSub = 1 and cs.wid != ".$uid." and cs.cid = ".$cid." group by u.uid";
        $queryResult = parent::query($query);
        $sendEmailToArray = array();

        while($result = parent::fetchArray($queryResult)) {
            $sendEmailToArray[] = array('uid' => $result['uid'], 'firstname' => $result['firstname'], 'email' => $result['email']);
        }

        $query = "select u.email, u.uid, u.firstname from users as u, comments as c, notificationSetting as ns where u.uid = c.wid and c.wid != ".$uid." and u.uid = ns.uid and ns.commentSub = 1 and c.cid = ".$cid;
        $queryResult = parent::query($query);

        if(parent::rowExist($queryResult)) {
            $sendEmailToArray[] = array('uid' => parent::queryResult($queryResult, 'uid'), 'firstname' => parent::queryResult($queryResult, 'firstname'), 'email' => parent::queryResult($queryResult, 'email'));
        }

        return $sendEmailToArray;

    }

    public function getEmailEventUser($eid, $uid) {

        $query = "select u.email, u.uid, u.firstname from users as u, userEvent as ue, notificationSetting as ns where u.uid = ns.uid and u.uid = ue.uid and ns.eventComment = 1 and ue.uid != ".$uid." and ue.eid = ".$eid." group by u.uid";
        $queryResult = parent::query($query);
        $sendEmailToArray = array();

        while($result = parent::fetchArray($queryResult)) {
            $sendEmailToArray[] = array('uid' => $result['uid'], 'firstname' => $result['firstname'], 'email' => $result['email']);
        }

        return $sendEmailToArray;

    }

}

?>