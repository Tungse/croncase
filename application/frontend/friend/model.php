<?php

class application_frontend_friend_model extends library_default_classes_model {

    public function getFriendsInfo($friendArray) {

        $friendInfoArray = array();

        if(!empty($friendArray) && is_array($friendArray)) {

            foreach($friendArray as $key => $uid) {

                $query = "select uid, firstname, lastname, email from users where uid = ".$uid;
                $queryResult = parent::query($query);

                while($result = parent::fetchArray($queryResult)) {
                    $friendInfoArray[$key] = array('uid' => $result['uid'], 'firstname' => $result['firstname'], 'lastname' => $result['lastname'], 'name' => $result['firstname'].' '.$result['lastname'], 'email' => $result['email']);
                }

            }

        }

        return $friendInfoArray;

    }

    public function getFriendsInfoWithEvent($friendArray) {

        $friendInfoArray = array();

        if(!empty($friendArray) && is_array($friendArray)) {

            foreach($friendArray as $key => $uid) {

                $query = "select uid, firstname, lastname, email from users where uid = ".$uid;
                $queryResult = parent::query($query);

                while($result = parent::fetchArray($queryResult)) {
                    $friendInfoArray[$key] = array('uid' => $result['uid'], 'firstname' => $result['firstname'], 'lastname' => $result['lastname'], 'name' => $result['firstname'].' '.$result['lastname'], 'email' => $result['email'], 'eventCount' => application_frontend_event_model::getEventOfThisWeek($result['uid']));
                }

            }

        }

        return $friendInfoArray;

    }

    public function getLimitedRandomFriends($uid) {

        $query = "select friendlist from friends where uid = ".$uid;
        $queryResult = parent::query($query);

        if(parent::rowExist($queryResult)) {

            $friendList = parent::queryResult($queryResult, 'friendlist');

            if($friendList !== NULL) {

                $friendArray = explode(';', $friendList);
                array_pop($friendArray);
                $friendArrayLength = count($friendArray);

                if($friendArrayLength > 12) {
                    shuffle($friendArray);
                    $friendArray = array_slice($friendArray, 0, 13);
                }

            }

        }

        return $friendArray;

    }

    public function getAllFriends($uid) {

        $query = "select friendlist from friends where uid = ".$uid;
        $queryResult = parent::query($query);
        $friendArray = array();

        if(parent::rowExist($queryResult)) {

            $friendList = parent::queryResult($queryResult, 'friendlist');

            if($friendList !== NULL) {
                $friendArray = explode(';', $friendList);
                array_pop($friendArray);
            }

        }

        return $friendArray;

    }

    public function getAllFriendsCount($uid) {

        $query = "select friendlist from friends where uid = ".$uid;
        $queryResult = parent::query($query);
        $friendCount = 0;

        if(parent::rowExist($queryResult)) {

            $friendList = parent::queryResult($queryResult, 'friendlist');

            if($friendList !== NULL) {
                $friendArray = explode(';', $friendList);
                array_pop($friendArray);
                $friendCount = count($friendArray);
            }

        }

        return $friendCount;

    }

    public function getRandomMutualFriends($uid, $uidOfPage) {

        $myFriendsArray = self::getAllFriends($uid);
        $friendsFriendsArray = self::getAllFriends($uidOfPage);
        $mutualFriendArray = NULL;

        if(!empty($myFriendsArray) && !empty($friendsFriendsArray)) {
            $mutualFriendArray = array_intersect($myFriendsArray, $friendsFriendsArray);
            $mutualFriendArrayLength = count($mutualFriendArray);
            shuffle($mutualFriendArray);

            if($mutualFriendArrayLength > 3) {
                $mutualFriendArray = array_slice($mutualFriendArray, 0, 4);
            }

        }

        return $mutualFriendArray;

    }

    public function getAllMutualFriends($uid, $uidOfPage) {

        $myFriendsArray = self::getAllFriends($uid);
        $friendsFriendsArray = self::getAllFriends($uidOfPage);
        $mutualFriendArray = NULL;

        if(!empty($myFriendsArray) && !empty($friendsFriendsArray)) {
            $mutualFriendArray = array_intersect($myFriendsArray, $friendsFriendsArray);
            shuffle($mutualFriendArray);
        }

        return $mutualFriendArray;

    }

    public function getAllMutualFriendsCount($uid, $uidOfPage) {

        $myFriendsArray = self::getAllFriends($uid);
        $friendsFriendsArray = self::getAllFriends($uidOfPage);
        $mutualFriendCount = 0;

        if(!empty($myFriendsArray) && !empty($friendsFriendsArray)) {
            $mutualFriendArray = array_intersect($myFriendsArray, $friendsFriendsArray);
            $mutualFriendCount = count($mutualFriendArray);
        }

        return $mutualFriendCount;

    }

    public function requestFriendship($uid) {

        $query = "insert into friendRequest (request, accept) values (".$this->user['myuid'].", ".$uid.")";
        parent::query($query);

    }

    public function deleteRequest($uid) {

        $query = "delete from friendRequest where (request = ".$this->user['myuid']." and accept = ".$uid.") or (request = ".$uid." and accept = ".$this->user['myuid'].")";
        parent::query($query);

    }

    public function getAllRequest($uid) {

        $query = "select fr.request, u.firstname, u.lastname from friendRequest as fr, users as u where fr.request = u.uid and fr.accept = ".$uid;
        $queryResult = parent::query($query);
        $requestList = array();

        while($result = parent::fetchArray($queryResult)) {
            $requestList[] = array('request' => $result['request'], 'name' => $result['firstname'].' '.$result['lastname'], 'firstname' => $result['firstname'], 'lastname' => $result['lastname'], 'eventCount' => application_frontend_event_model::getEventOfThisWeek($result['request']));
        }

        return $requestList;

    }

    public function getRequestCount($uid) {

        $query = "select count(id) as requestCount from friendRequest where accept = ".$uid;
        $queryResult = parent::query($query);
        $requestCount = 0;

        if(parent::rowExist($queryResult)) {
            $requestCount = parent::queryResult($queryResult, 'requestCount');
        }

        return $requestCount;

    }

    public function checkFriendship($uid, $uidOfPage) {

        $myFriendsArray = self::getAllFriends($uid);

        if(array_search($uidOfPage, $myFriendsArray) === false) {
            return false;
        } else {
            return true;
        }

    }

    public function addFriend($uid, $uidToAdd) {

        $query = "select friendlist from friends where uid = ".$uid;
        $queryResult = parent::query($query);

        $friendlist = parent::queryResult($queryResult, 'friendlist');
        $newFriendlist = $friendlist.$uidToAdd.';';

        $query = "update friends set friendlist = '".$newFriendlist."' where uid = ".$uid;
        parent::query($query);

    }

    public function deleteFriend($uid, $uidToDelete) {

        $query = "select friendlist from friends where uid = ".$uid;
        $queryResult = parent::query($query);

        $friendlist = parent::queryResult($queryResult, 'friendlist');

        if(strpos($friendlist, $uidToDelete.';') == 0) {
            $newFriendlist = str_replace($uidToDelete.';', '', $friendlist);
        } else {
            $newFriendlist = str_replace(';'.$uidToDelete.';', ';', $friendlist);
        }

        $query = "update friends set friendlist = '".$newFriendlist."' where uid = ".$uid;
        parent::query($query);

    }

    public function checkRequestSend($uid, $uidOfPage) {

        $query = "select id from friendRequest where request = ".$uid." and accept = ".$uidOfPage;
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    public function saveEmailInvitation($uid, $email, $emailContent) {

        $query = "insert into friendEmailInvitation (uid, email, content, hash, created) values ('".$uid."', '".$email."', '".$emailContent['content']."', '".$emailContent['hash']."', now())";
        parent::query($query);

    }

    public function deleteFriendInvitation($email, $uid) {

        $query = "delete from friendEmailInvitation where email = '".$email."' and uid = ".$uid;
        parent::query($query);

    }

    public function getSendEmailUser($uid) {

        $query = "select u.email, u.firstname, ns.friendRequest from users as u, notificationSetting as ns where u.uid = ns.uid and u.uid = ".$uid;
        $queryResult = parent::query($query);
        $user = array();

        if(parent::rowExist($queryResult)) {
            $user = array('friendRequest' => parent::queryResult($queryResult, 'friendRequest'), 'email' => parent::queryResult($queryResult, 'email'), 'firstname' => parent::queryResult($queryResult, 'firstname'));
        }

        return $user;

    }

}

?>