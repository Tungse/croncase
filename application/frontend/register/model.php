<?php

class application_frontend_register_model extends library_default_classes_model {

    public function createUser($input) {

        $query = "insert into users (firstname, lastname, email, password, activation, created) values ('".$input['firstname']."', '".$input['lastname']."', '".$input['email']."', '".$input['password']."', 0, now())";
        parent::query($query);
        
        $this->uid = mysql_insert_id();
        $this->hash = md5($this->uid);

        self::insertActivationHash($this->uid, $this->hash);
        self::insertProfile($this->uid, $input);

    }

    public function getUid() {
        return $this->uid;
    }

    public function getHash() {
        return $this->hash;
    }

    public function checkRegisterData($input) {

        $query = "select uid from users where email = '".$input['email']."'";
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    private function insertActivationHash($uid, $hash) {

        $query = "insert into activationHash (uid, hash, date_added) values('".$uid."', '".$hash."', now())";
        parent::query($query);

    }

    private function insertProfile($uid, $input) {

        $query = "insert into profilePrivacy (uid, modify) values ('".$uid."', now())";
        parent::query($query);

        $query = "insert into profileBasic (uid, gender, email) values ('".$uid."', '".$input['gender']."', '".$input['email']."')";
        parent::query($query);

        $query = "insert into profileInterest (uid) values ('".$uid."')";
        parent::query($query);

        $query = "insert into profileEducation (uid) values ('".$uid."')";
        parent::query($query);

        $query = "insert into userPrivacy (uid, calendar, status, comment, friend) values ('".$uid."', 3, 3, 3, 3)";
        parent::query($query);

        $query = "insert into friends (uid, friendlist) values ('".$uid."', '')";
        parent::query($query);

        $query = "insert into pageView (uid, view) values ('".$uid."', 0)";
        parent::query($query);

        $query = "insert into notificationSetting (uid, modify) values ('".$uid."', now())";
        parent::query($query);

    }
    
}


?>