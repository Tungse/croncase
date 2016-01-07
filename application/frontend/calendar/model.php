<?php

class application_frontend_calendar_model extends library_default_classes_model {

    public function getEventFromWeek($firstDayOfWeek, $lastDayOfWeek) {

        $query = "select ue.id, ue.eid, ue.attending, e.owner, e.name, e.address, e.adjustable, e.location, e.description, e.joinable, ue.dateFrom, ue.timeFrom, ue.timeTo, ue.privacy, ue.remember, ue.modify
                    from userEvent as ue, event as e
                    where ue.eid = e.eid and ue.dateFrom >= '".$firstDayOfWeek."' and ue.dateFrom <= '".$lastDayOfWeek."' and ue.uid = ".$this->user['uid']." and ".$this->user['relation']." <= ue.privacy
                    order by timeFrom";
        $queryResult = parent::query($query);
        $event = array();
        
        while($result = parent::fetchArray($queryResult)) {
            
            $event[] = array('id' => $result['id'],
                                'eid' => $result['eid'],
                                'attending' => $result['attending'],
                                'owner' => $result['owner'],
                                'name' => $result['name'],
                                'address' => $result['address'],
                                'adjustable' => $result['adjustable'],
                                'location' => $result['location'],
                                'description' => $result['description'],
                                'joinable' => $result['joinable'],
                                'dateFrom' => $result['dateFrom'],
                                'timeFrom' => $result['timeFrom'],
                                'timeTo' => $result['timeTo'],
                                'privacy' => $result['privacy'],
                                'remember' => $result['remember'],
                                'modify' => $result['modify']);
            
        }

        return $event;

    }

}

?>