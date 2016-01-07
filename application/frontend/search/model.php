<?php

class application_frontend_search_model extends library_default_classes_model {

    public function getUserSearchResult($searchKeyword) {

        $query = "select uid, firstname, lastname from users where firstname like '%".$searchKeyword."%' or lastname like '%".$searchKeyword."%' group by uid limit 0, 200";
        $queryResult = parent::query($query);
        $userSearch = array();
        
        while($result = parent::fetchArray($queryResult)) {
            $userSearch[] = array('uid' => $result['uid'],
                                    'name' => $result['firstname'].' '.$result['lastname'],
                                    'firstname' => $result['firstname'],
                                    'eventCount' => application_frontend_event_model::getEventOfThisWeek($result['uid']));
        }

        return $userSearch;

    }

    public function getEventSearchAdvanceResult($searchAdvanceKeyword) {

        if($searchAdvanceKeyword['eventName'] !== '') {
            $nameQuery = "and (e.name like '%".$searchAdvanceKeyword['eventName']."%')";
        } else {
            $nameQuery = '';
        }

        if($searchAdvanceKeyword['owner'] !== '') {
            $ownerQuery = "and (u.firstname like '%".$searchAdvanceKeyword['owner']."%' or u.lastname like '%".$searchAdvanceKeyword['owner']."%')";
        } else {
            $ownerQuery = '';
        }

        if($searchAdvanceKeyword['location'] !== '') {
            $locationQuery = "and (e.location like '%".$searchAdvanceKeyword['location']."%')";
        } else {
            $locationQuery = '';
        }

        if($searchAdvanceKeyword['from'] !== '') {

            $dateFromArray = explode('/', $searchAdvanceKeyword['from']);
            $searchAdvanceKeyword['from'] = $dateFromArray[2].'-'.$dateFromArray[1].'-'.$dateFromArray[0];
            $fromQuery = "and (e.dateFrom >= '".$searchAdvanceKeyword['from']."')";
            
        } else {
            $fromQuery = '';
        }

        $query = "select e.eid, e.owner, e.name, e.description, e.dateFrom, e.timeFrom, e.timeTo, e.joinable, u.firstname, u.lastname
                    from event as e, users as u
                    where e.owner = u.uid and e.searchable = 1 
                    ".$nameQuery."
                    ".$ownerQuery."
                    ".$locationQuery."
                    ".$fromQuery."
                    group by e.eid desc limit 0, 200";
        $queryResult = parent::query($query);
        $eventSearchAdvance = array();

        while($result = parent::fetchArray($queryResult)) {

            $eventSearchAdvance[] = array('eid' => $result['eid'],
                                    'owner' => $result['owner'],
                                    'eventName' => $result['name'],
                                    'name' => $result['firstname'].' '.$result['lastname'],
                                    'description' => $result['description'],
                                    'joinable' => $result['joinable'],
                                    'dateFrom' => $result['dateFrom'],
                                    'timeFrom' => $result['timeFrom'],
                                    'timeTo' => $result['timeTo']);

        }

        return $eventSearchAdvance;

    }

    public function getEventSearchResult($searchKeyword) {

        $query = "select e.eid, e.owner, e.name, e.description, e.dateFrom, e.timeFrom, e.timeTo, e.joinable, u.firstname, u.lastname
                    from event as e, users as u
                    where e.owner = u.uid and e.searchable = 1 and (e.name like '%".$searchKeyword."%' or e.description like '%".$searchKeyword."%') group by e.eid desc limit 0, 200";
        $queryResult = parent::query($query);
        $eventSearch = array();

        while($result = parent::fetchArray($queryResult)) {

            $eventSearch[] = array('eid' => $result['eid'],
                                    'owner' => $result['owner'],
                                    'eventName' => $result['name'],
                                    'name' => $result['firstname'].' '.$result['lastname'],                                   
                                    'description' => $result['description'],
                                    'joinable' => $result['joinable'],
                                    'dateFrom' => $result['dateFrom'],
                                    'timeFrom' => $result['timeFrom'],
                                    'timeTo' => $result['timeTo']);

        }

        return $eventSearch;

    }

}


?>