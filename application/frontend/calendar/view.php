<?php

class application_frontend_calendar_view extends library_default_classes_view {

    public function __construct() {

        $this->monday = NULL;
        $this->tuesday = NULL;
        $this->wednesday = NULL;
        $this->thursday = NULL;
        $this->friday = NULL;
        $this->saturday = NULL;
        $this->sunday = NULL;

    }

    public function loadCalendarTemplate(){

        ob_start();

            include('public/templates/default/html/calendar.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function getDayInfo($weekIndex) {

        if($weekIndex != 0) {
            $weekIndex = $weekIndex*7;
        }

        switch(date('l')) {

            case 'Monday':
                $dayInfo['monday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+$weekIndex, date('Y')));
                $dayInfo['tuesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+1+$weekIndex, date('Y')));
                $dayInfo['wednesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+2+$weekIndex, date('Y')));
                $dayInfo['thursday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+3+$weekIndex, date('Y')));
                $dayInfo['friday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+4+$weekIndex, date('Y')));
                $dayInfo['saturday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+5+$weekIndex, date('Y')));
                $dayInfo['sunday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+6+$weekIndex, date('Y')));
                break;
            case 'Tuesday':
                $dayInfo['monday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-1+$weekIndex, date('Y')));
                $dayInfo['tuesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+$weekIndex, date('Y')));
                $dayInfo['wednesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+1+$weekIndex, date('Y')));
                $dayInfo['thursday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+2+$weekIndex, date('Y')));
                $dayInfo['friday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+3+$weekIndex, date('Y')));
                $dayInfo['saturday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+4+$weekIndex, date('Y')));
                $dayInfo['sunday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+5+$weekIndex, date('Y')));
                break;
            case 'Wednesday':
                $dayInfo['monday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-2+$weekIndex, date('Y')));
                $dayInfo['tuesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-1+$weekIndex, date('Y')));
                $dayInfo['wednesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+$weekIndex, date('Y')));
                $dayInfo['thursday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+1+$weekIndex, date('Y')));
                $dayInfo['friday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+2+$weekIndex, date('Y')));
                $dayInfo['saturday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+3+$weekIndex, date('Y')));
                $dayInfo['sunday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+4+$weekIndex, date('Y')));
                break;
            case 'Thursday':
                $dayInfo['monday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-3+$weekIndex, date('Y')));
                $dayInfo['tuesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-2+$weekIndex, date('Y')));
                $dayInfo['wednesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-1+$weekIndex, date('Y')));
                $dayInfo['thursday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+$weekIndex, date('Y')));
                $dayInfo['friday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+1+$weekIndex, date('Y')));
                $dayInfo['saturday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+2+$weekIndex, date('Y')));
                $dayInfo['sunday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+3+$weekIndex, date('Y')));
                break;
            case 'Friday':
                $dayInfo['monday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-4+$weekIndex, date('Y')));
                $dayInfo['tuesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-3+$weekIndex, date('Y')));
                $dayInfo['wednesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-2+$weekIndex, date('Y')));
                $dayInfo['thursday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-1+$weekIndex, date('Y')));
                $dayInfo['friday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+$weekIndex, date('Y')));
                $dayInfo['saturday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+1+$weekIndex, date('Y')));
                $dayInfo['sunday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+2+$weekIndex, date('Y')));
                break;
            case 'Saturday':
                $dayInfo['monday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-5+$weekIndex, date('Y')));
                $dayInfo['tuesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-4+$weekIndex, date('Y')));
                $dayInfo['wednesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-3+$weekIndex, date('Y')));
                $dayInfo['thursday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-2+$weekIndex, date('Y')));
                $dayInfo['friday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-1+$weekIndex, date('Y')));
                $dayInfo['saturday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+$weekIndex, date('Y')));
                $dayInfo['sunday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+1+$weekIndex, date('Y')));
                break;
            case 'Sunday':
                $dayInfo['monday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-6+$weekIndex, date('Y')));
                $dayInfo['tuesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-5+$weekIndex, date('Y')));
                $dayInfo['wednesday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-4+$weekIndex, date('Y')));
                $dayInfo['thursday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-3+$weekIndex, date('Y')));
                $dayInfo['friday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-2+$weekIndex, date('Y')));
                $dayInfo['saturday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')-1+$weekIndex, date('Y')));
                $dayInfo['sunday'] = date('D d. M', mktime(0, 0, 0, date('m'), date('d')+$weekIndex, date('Y')));
                break;

        }

        return $dayInfo;

    }

    public function organizeEventOfWeek() {

        foreach($this->event as $event) {

            $blockStyle = array();

            switch($event['attending']) {

                case 'sure':
                    $blockStyle['color'] = '#99EE99';
                    break;
                case 'maybe':
                    $blockStyle['color'] = '#FFEE99';
                    break;
                case 'open':
                    $blockStyle['color'] = '#FF8888';
                    break;

            }

            if(!empty($event['timeFrom']) && !empty($event['timeTo'])) {

                $timeFrom = str_replace(':00', '', $event['timeFrom']);
                $timeTo = str_replace(':00', '', $event['timeTo']);

                $timeDifference = (int)$timeTo - (int)$timeFrom;

                if($timeDifference > 0) {
                    $divHeight = ($timeDifference*27) - 3;
                    $blockStyle['divHeight'] = $divHeight;
                } else {
                    $blockStyle['divHeight'] = 24;
                }

            } else {
                $blockStyle['divHeight'] = 24;
            }

            if($blockStyle['divHeight'] > 78) {
                $event['eventDate'] = '<p><small>'.$event['timeFrom'].' - '.$event['timeTo'].'</small></p>';
            } elseif($blockStyle['divHeight'] > 51 && strlen($event['name']) < 51) {
                $event['eventDate'] = '<p><small>'.$event['timeFrom'].' - '.$event['timeTo'].'</small></p>';
            } elseif($blockStyle['divHeight'] > 24 && strlen($event['name']) < 18) {
                $event['eventDate'] = '<p><small>'.$event['timeFrom'].' - '.$event['timeTo'].'</small></p>';
            } else {
                $event['eventDate'] = '';
            }

            $blockStyle['innerDivHeight'] = $blockStyle['divHeight'] - 1;

            if(!empty($event['timeFrom'])) {

                $timeFrom = str_replace(':00', '', $event['timeFrom']);
                $blockStyle['divTop'] = (int)$timeFrom*27;
                $blockStyle['divTop'] = $blockStyle['divTop'];

            } else {
                $blockStyle['divTop'] = 0;
            }

            if($this->user['page']) {

                if($event['adjustable'] === 'flexible') {
                    $event['draggable'] = ' draggable';
                } else {
                    $event['draggable'] = '';
                }

                if($event['owner'] == $this->user['myuid']) {
                    $event['invite'] = true;
                } else {

                    if($event['joinable'] >= 2) {
                        $event['invite'] = true;
                    } else {
                        $event['invite'] = false;
                    }

                }

            } else {
                $event['draggable'] = '';
                $event['invite'] = false;
            }

            $dayNameArray = explode('-', $event['dateFrom']);
            $dayName = date('l', mktime(0, 0, 0, (int)$dayNameArray[1], (int)$dayNameArray[2], (int)$dayNameArray[0]));

            switch ($dayName) {

                case 'Monday':
                    $this->monday .= self::createEventBlock($event, $blockStyle, 0);
                    break;
                case 'Tuesday':
                    $this->tuesday .= self::createEventBlock($event, $blockStyle, 136);
                    break;
                case 'Wednesday':
                    $this->wednesday .= self::createEventBlock($event, $blockStyle, 272);
                    break;
                case 'Thursday':
                    $this->thursday .= self::createEventBlock($event, $blockStyle, 408);
                    break;
                case 'Friday':
                    $this->friday .= self::createEventBlock($event, $blockStyle, 544);
                    break;
                case 'Saturday':
                    $this->saturday .= self::createEventBlock($event, $blockStyle, 680);
                    break;
                case 'Sunday':
                    $this->sunday .= self::createEventBlock($event, $blockStyle, 816);
                    break;

            }

        }

    }

    private function createEventBlock($event, $blockStyle, $left) {

        ob_start();

            include('public/templates/default/html/eventBlock.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

?> 