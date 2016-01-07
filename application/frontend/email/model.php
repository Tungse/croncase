<?php

class application_frontend_email_model extends library_default_classes_model {

    public function getUserNameFromEmail($email) {

        $query = "select firstname from users where email = '".$email."'";
        $queryResult = parent::query($query);
        $name = NULL;

        if(parent::rowExist($queryResult)) {
            $name = parent::queryResult($queryResult, 'firstname');
        }

        return $name;

    }

    public function getEventName($eid) {

        $query = "select name from event where eid = ".$eid;
        $queryResult =  parent::query($query);
        $eventName = NULL;

        if(parent::rowExist($queryResult)) {
            $eventName = parent::queryResult($queryResult, 'name');
        }

        return $eventName;

    }

}

?>
