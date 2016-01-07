<?php

class application_frontend_content_model extends library_default_classes_model {

    public function getAboutMe($uid) {

        $query = "select aboutMe from profileBasic where uid = ".$uid;
        $queryResult = parent::query($query);

        return parent::queryResult($queryResult, 'aboutMe');

    }

    public function getStatus($uid) {

        $query = "select comment from comments where uid = ".$uid." and wid = ".$uid." and date_add(created, interval 1 day) > now() order by created desc limit 0,1";
        $queryResult = parent::query($query);

        return parent::queryResult($queryResult, 'comment');

    }

}

?>