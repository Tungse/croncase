<?php

class application_frontend_index_model extends library_default_classes_model {

    public function getEventRecommend() {
        
        $query = "select eid, owner, name from event where eid in (select eid from userEvent) and searchable = 1 and joinable > 1 order by dateFrom desc limit 0, 50";
        $queryResult = parent::query($query);
        $eventRecommend = array();
        $i = 0;
        
        while($result = parent::fetchArray($queryResult)) {

            if((self::getAttenderCount($result['eid']) > 1) && $i < 7) {
                $eventRecommend[] = array('eid' => $result['eid'], 'owner' => $result['owner'], 'name' => $result['name']);
                $i++;
            }

        }
        
        return $eventRecommend;
        
    }

    private function getAttenderCount($eid) {

        $query = "select uid from userEvent where eid = ".$eid;
        $queryResult = parent::query($query);
        $attenderCount = 0;
        
        if(parent::rowExist($queryResult)) {
            $attenderCount = parent::numRows($queryResult);
        }

        return $attenderCount;

    }

}

?>