<?php

class application_frontend_activation_model extends library_default_classes_model {

    public function checkHashExist($hash) {

        $query = "select uid from activationHash where hash = '".$hash."'";
        $queryResult = parent::query($query);

        if(parent::rowExist($queryResult)) {
            return parent::queryResult($queryResult, 'uid');
        } else {
            return false;
        }
        
    }

    public function deleteHash($hash) {

        $query = "delete from activationHash where hash = '".$hash."'";
        parent::query($query);

    }

    public function activateUser($uid) {

        $query = "update users set activation = 1 where uid = ".$uid;
        parent::query($query);

    }

    public function getEventInvitationFromHash($hash) {

        $query = "select eid, ueid, inviter, receiver from eventEmailInvitation where hash = '".$hash."'";
        $queryResult = parent::query($query);
        $invitation = array();

        while($result = parent::fetchArray($queryResult)) {
            $invitation = array('eid' => $result['eid'], 'ueid' => $result['ueid'], 'inviter' => $result['inviter'], 'receiver' => $result['receiver']);
        }

        return $invitation;

    }

    public function getFriendInvitationFromHash($hash) {

        $query = "select uid, email from friendEmailInvitation where hash = '".$hash."'";
        $queryResult = parent::query($query);
        $invitation = array();

        while($result = parent::fetchArray($queryResult)) {
            $invitation = array('uid' => $result['uid'], 'email' => $result['email']);
        }

        return $invitation;

    }

}

?>