<?php

class application_frontend_home_model extends library_default_classes_model {

    public function getShortInfo($uid) {

        $query = "select pb.gender, pb.birth, pb.relationship, pb.aboutMe, pb.location, pv.view from profileBasic as pb, pageView as pv where pv.uid = pb.uid and pb.uid = ".$uid;
        $queryResult = parent::query($query);

        return parent::fetchArray($queryResult);

    }

    public function getMutualEvents($myuid, $uid) {

        $query = "select eid from userEvent where uid = ".$myuid." and eid in(select eid from userEvent where uid = ".$uid.") group by eid";
        $queryResult = parent::query($query);
        $mutualEvents = 0;

        $mutualEvents = parent::numRows($queryResult);

        return $mutualEvents;

    }

    public function updatePageView($uid) {

        $query = "update pageView set view = view + 1 where uid = ".$uid;
        parent::query($query);
        
    }

}

?>