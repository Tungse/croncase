<?php


class application_frontend_login_model extends library_default_classes_model {

    public function getUsersId($input) {

        $query = "select uid from users where email = '".$input['loginEmail']."'";
        $queryResult = parent::query($query);

        if(parent::rowExist($queryResult)) {
            $uid = mysql_result($queryResult, 0, 'uid');
        } else {
            $uid = NULL;
        }

        return $uid;

    }

    public function checkPasswordAccount($input) {

        $query = "select uid from users where email = '".$input['loginEmail']."' and password = '".$input['loginPassword']."'";
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    public function checkAdmin($uid) {

        $query = "select id from admin where uid = ".$uid;
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    public function saveLoginAttempt($uid) {

        $query = "select id from loginAttempt where uid = ".$uid;
        $queryResult = parent::query($query);

        if(parent::rowExist($queryResult)) {

            $query = "update loginAttempt set attempt = attempt + 1, modified = now() where uid = ".$uid;
            parent::query($query);

        } else {

            $query = "insert into loginAttempt (uid, attempt, modified) values ('".$uid."', 1, now())";
            parent::query($query);

        }

    }

    public function checkUserActivation($uid) {

        $query = "select activation from users where uid = ".$uid;
        $queryResult = parent::query($query);

        if(parent::rowExist($queryResult)) {

            $activation = parent::queryResult($queryResult, 'activation');

            if($activation == '1') {
                return true;
            } else {
                //return false -> if activation is needed
                return true;
            }

        }

    }

}

?>