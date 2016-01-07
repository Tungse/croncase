<?php

class application_frontend_user_model extends library_default_classes_model {

    public function checkUserExist($uid) {

        $query = "select uid from users where uid = ".$uid;
        $queryResult = self::query($query);

        return parent::rowExist($queryResult);

    }

    public function getUser($uid) {

        $query = "select firstname, lastname, email from users where uid = ".$uid;
        $queryResult = parent::query($query);
        $userData = array();

        while($result = parent::fetchArray($queryResult)) {
            $userData = array('name' => $result['firstname'].' '.$result['lastname'], 'firstname' => $result['firstname'], 'lastname' => $result['lastname'], 'email' => $result['email']);
        }

        return $userData;

    }

}

?>