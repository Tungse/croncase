<?php

class application_frontend_view_model extends library_default_classes_model {

    public function getEvent($eid) {

        $query = "select e.owner, e.name, e.address, e.adjustable, e.location, e.website, e.description, e.dateFrom, e.timeFrom, e.timeTo, e.joinable, u.firstname, u.lastname
                    from event as e, users as u
                    where e.owner = u.uid and e.eid = ".$eid;
        $queryResult = parent::query($query);
        $event = NULL;

        while($result = parent::fetchArray($queryResult)) {
            $event = array('eid' => $eid,
                           'owner' => $result['owner'],
                           'name' => $result['name'],
                           'address' => $result['address'],
                           'adjustable' => $result['adjustable'],
                           'location' => $result['location'],
                           'website' => $result['website'],
                           'description' => $result['description'],
                           'dateFrom' => date('d/m/Y', strtotime($result['dateFrom'])),
                           'timeFrom' => $result['timeFrom'],
                           'timeTo' => $result['timeTo'],
                           'joinable' => $result['joinable'],
                           'firstname' => $result['firstname'],
                           'lastname' => $result['lastname'],
                           'comment' => self::getCommentEvent($eid),
                           'attenderCount' => (int)self::getAttenderCount($eid),
                           'attenders' => self::getAttenders($eid));
        }

        return $event;

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

    public function getAttenders($eid) {

        $query = "select u.uid, u.firstname, u.lastname from users as u, userEvent as ue where u.uid = ue.uid and ue.eid = ".$eid." and ue.privacy = 3 group by ue.uid order by u.firstname";
        $queryResult = parent::query($query);
        $attenders = array();

        while($result = parent::fetchArray($queryResult)) {
            $attenders[] = array('uid' => $result['uid'], 'name' => $result['firstname'].' '.$result['lastname']);
        }

        return $attenders;

    }

    public function getAttenderCount($eid) {

        $query = "select uid from userEvent where eid = ".$eid." group by uid";
        $queryResult = parent::query($query);
        $attenderCount = 0;

        if(parent::rowExist($queryResult)) {
            $attenderCount = parent::numRows($queryResult);
        }

        return $attenderCount;

    }

}

?>