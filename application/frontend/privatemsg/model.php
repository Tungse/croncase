<?php

class application_frontend_privatemsg_model extends library_default_classes_model {

    public function getAllPrivatemsg($uid) {

        $query = "select p.pid, p.writer, p.subject, p.content, p.readed, p.created, u.firstname, u.lastname from privatemsg as p, users as u where p.reader = ".$uid." and u.uid = p.writer and p.reader_delete = 0 order by p.created desc limit 0, 50";
        $queryResult = parent::query($query);
        $privatemsg = array('unread' => array(), 'readed' => array(), 'sended' => array());

        while($result = parent::fetchArray($queryResult)) {

            if($result['readed'] == 0 ) {
                $privatemsg['unread'][] = array('pid' => $result['pid'], 'writer' => $result['writer'], 'name' => $result['firstname'].' '.$result['lastname'], 'subject' => $result['subject'], 'content' => $result['content'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'privatemsgSub' => self::getAllPrivatemsgGetSub($result['pid']));
            } else {

                if(self::checkUnreadPrivatemsgSub($result['pid'])) {
                    $privatemsg['unread'][] = array('pid' => $result['pid'], 'writer' => $result['writer'], 'name' => $result['firstname'].' '.$result['lastname'], 'subject' => $result['subject'], 'content' => $result['content'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'privatemsgSub' => self::getAllPrivatemsgGetSub($result['pid']));
                } else {
                    $privatemsg['readed'][] = array('pid' => $result['pid'], 'writer' => $result['writer'], 'name' => $result['firstname'].' '.$result['lastname'], 'subject' => $result['subject'], 'content' => $result['content'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'privatemsgSub' => self::getAllPrivatemsgGetSub($result['pid']));
                }

            }

        }

        $query = "select p.pid, p.reader, p.writer, p.subject, p.content, p.created, u.firstname, u.lastname from privatemsg as p, users as u where p.reader = u.uid and p.writer_delete = 0 and p.writer = ".$uid." order by p.created desc";
        $queryResult = parent::query($query);

        while($result = parent::fetchArray($queryResult)) {

            if(self::checkUnreadPrivatemsgSub($result['pid'])) {
                $privatemsg['unread'][] = array('pid' => $result['pid'], 'writer' => $result['writer'], 'name' => $result['firstname'].' '.$result['lastname'], 'subject' => $result['subject'], 'content' => $result['content'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'privatemsgSub' => self::getAllPrivatemsgGetSub($result['pid']));
            } else {
                $privatemsg['sended'][] = array('pid' => $result['pid'], 'reader' => $result['reader'], 'writer' => $result['writer'], 'name' => $result['firstname'].' '.$result['lastname'], 'subject' => $result['subject'], 'content' => $result['content'], 'created' => date('d F \a\t H:i', strtotime($result['created'])), 'privatemsgSub' => self::getAllPrivatemsgGetSub($result['pid']));
            }
        }

        return $privatemsg;

    }

    public function getUnreadPrivatemsgCount($uid) {

        $query = "select count(pid) as privatemsgCount from privatemsg where reader = ".$uid." and readed = 0";
        $queryResult = parent::query($query);
        $privatemsgCount = 0;

        if(parent::rowExist($queryResult)) {
            $privatemsgCount = parent::queryResult($queryResult, 'privatemsgCount');
        }

        $query = "select count(pid) as privatemsgSubCount from privatemsgSub where reader = ".$uid." and readed = 0";
        $queryResult = parent::query($query);
        $privatemsgSubCount = 0;

        if(parent::rowExist($queryResult)) {
            $privatemsgSubCount = parent::queryResult($queryResult, 'privatemsgSubCount');
        }

        $privatemsgAllCount = $privatemsgCount + $privatemsgSubCount;

        return $privatemsgAllCount;

    }

    public function getAllPrivatemsgGetSub($pid) {

        $query = "select ps.id, ps.writer, ps.content, ps.readed, ps.created, u.firstname, u.lastname from privatemsgSub as ps, users as u where ps.writer = u.uid and ps.pid = ".$pid." order by ps.created";
        $queryResult = parent::query($query);
        $privatemsgGetSub = array();

        while($result = parent::fetchArray($queryResult)) {
            $privatemsgGetSub[] = array('id' => $result['id'], 'writer' => $result['writer'], 'name' => $result['firstname'].' '.$result['lastname'], 'content' => $result['content'], 'created' => date('d F \a\t H:i', strtotime($result['created'])));
        }

        return $privatemsgGetSub;

    }

    public function savePrivatemsgWrite($input) {

        $query = "insert into privatemsg (writer, reader, subject, content, modify, created) values ('".$input['writer']."', '".$input['reader']."', '".$input['subject']."', '".$input['content']."', now(), now())";
        parent::query($query);

    }

    public function privatemsgReaded($pid) {

        $query = "update privatemsg set readed = 1, modify = now() where pid = ".$pid." and reader = ".$this->user['myuid'];
        parent::query($query);

        $query = "update privatemsgSub set readed = 1, modify = now() where pid = ".$pid." and reader = ".$this->user['myuid'];
        parent::query($query);

    }

    public function savePrivatemsgAnswer($input) {

        $query = "insert into privatemsgSub (pid, writer, reader, content, readed, modify, created) values ('".$input['pid']."', '".$input['writer']."', '".$input['reader']."', '".$input['content']."', 0, now(), now())";
        parent::query($query);

    }

    public function deletePrivatemsg($pid) {

        $query = "update privatemsg set reader_delete = 1, modify = now() where pid = ".$pid." and reader = ".$this->user['myuid'];
        parent::query($query);

    }

    public function deletePrivatemsgSended($pid) {

        $query = "update privatemsg set writer_delete = 1, modify = now() where pid = ".$pid." and writer = ".$this->user['myuid'];
        parent::query($query);

    }

    public function checkUnreadPrivatemsgSub($pid) {

        $query = "select id from privatemsgSub where readed = 0 and pid = ".$pid." and writer != ".$this->user['myuid'];
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    public function getSendEmailUser($uid) {

        $query = "select u.email, u.firstname, ns.privateMessage from users as u, notificationSetting as ns where u.uid = ns.uid and u.uid = ".$uid;
        $queryResult = parent::query($query);
        $user = array();

        if(parent::rowExist($queryResult)) {
            $user = array('privateMessage' => parent::queryResult($queryResult, 'privateMessage'), 'email' => parent::queryResult($queryResult, 'email'), 'firstname' => parent::queryResult($queryResult, 'firstname'));
        }

        return $user;

    }

}

?>