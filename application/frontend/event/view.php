<?php

class application_frontend_event_view extends library_default_classes_view {

    public function __construct() {

        $this->event = NULL;
        $this->invitation = false;
        $this->ueidExist = true;

    }

    public function loadEventCreateTemplate() {

        $this->fromTimeOptions = self::getFromTimeOptions($this->event['timeFrom']);
        $this->toTimeOptions = self::getToTimeOptions($this->event['timeTo']);

        ob_start();

            include('public/templates/default/html/eventCreate.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventShowInvitationTemplate() {

        $invitationListLenght = count($this->invitationList);

        for($i = 0; $i < $invitationListLenght; $i++) {

            if($this->invitationList[$i]['adjustable'] === 'flexible') {
                $this->invitationList[$i]['fromTimeOptions'] = self::getFromTimeOptions($this->invitationList[$i]['timeFrom']);
                $this->invitationList[$i]['toTimeOptions'] = self::getToTimeOptions($this->invitationList[$i]['timeTo']);
            }

            $this->invitationList[$i]['dateFrom'] = self::correctDateFormat($this->invitationList[$i]['dateFrom']);

        }

        ob_start();

            include('public/templates/default/html/eventShowInvitation.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventEditTemplate() {

        $this->joinable = self::getJoinableOptions($this->event['joinable']);
        $this->searchable = self::getSearchableOptions($this->event['searchable']);
        $this->event['dateFrom'] = self::correctDateFormat($this->event['dateFrom']);
        $this->fromTimeOptions = self::getFromTimeOptions($this->event['timeFrom']);
        $this->toTimeOptions = self::getToTimeOptions($this->event['timeTo']);
        $this->fixedTimeChecked = self::getFixedTimeChecked($this->event['adjustable']);
        $this->eventNameCount = self::getEventNameCount($this->event['name']);

        ob_start();

            include('public/templates/default/html/eventEdit.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventViewTemplate() {

        $this->event['dateFrom'] = self::correctDateFormat($this->event['dateFrom']);
        $this->event['need_invitation'] = self::checkInvitationNeed($this->event['joinable']);
        $this->event['readonly'] = 'readonly';
        $this->event['fixedDate'] = 'id="dateFromFix"';
        $this->event['fixedTime'] = '<small><i>&nbsp;&nbsp;(not changable)</i></small>';
        $this->event['invitationRequest'] = NULL;
        $this->event['duplicate_button'] = NULL;
        $this->event['cancel_button'] = '<div id="cancel_event"><input id="no_div" type="button" onclick="tb_remove();" value="close" /></div>';

        if($this->event['website'] !== '') {
            $this->event['website'] = '(website '.$this->event['website'].')';
        }
        if($this->event['location'] !== '') {
            $this->event['location'] = $this->event['location'];
        }

        if($this->user['page']) {

            if($this->ueidExist) {
                $this->event['attending'] = self::getAttendOptions($this->event['attending']);
                $this->event['privacy'] = '<select name="privacy">'.self::getPrivacyOptions($this->event['privacy']).'</select>';
                $this->event['remember'] = '<input type="checkbox" name="remember" value="1" '.self::getNotificationOption($this->event['remember']).' />&nbsp&nbsp<small><i>(get a notafication before event)</i></small>';
                $this->event['update_join_button'] = '<div id="update_button"><input id="submit_div" type="button" onclick="updateEventHome('.$this->event['eid'].', '.$this->user['myuid'].', '.$this->ueid.');" value="update event" name="update"></div>';
                $this->event['duplicate_button'] = '<div id="duplicate_button"><input id="submit_div" type="button" onclick="duplicateEvent('.$this->event['eid'].', '.$this->user['myuid'].', '.$this->ueid.');" value="duplicate" name="duplicate"></div>';
            } else {
                $this->event['attending'] = self::getAttendOptionsPlain();
                $this->event['privacy'] = self::getPrivacyOptionsPlain();
                $this->event['remember'] = '<input type="checkbox" name="remember" value="1" />&nbsp&nbsp<small><i>(get a notafication before event)</i></small>';
                $this->event['update_join_button'] = '<div id="update_join_button"><input id="submit_div" type="button" onclick="joinEvent('.$this->event['eid'].');" value="attending event" name="join"></div>';
            }
            if($this->event['adjustable'] === 'flexible') {
                $this->event['timeFrom'] = self::getFromTimeOptions($this->event['timeFrom']);
                $this->event['timeTo'] = self::getToTimeOptions($this->event['timeTo']);
                $this->event['readonly'] = NULL;
                $this->event['fixedTime'] = NULL;
                $this->event['fixedDate'] = 'id="dateFrom"';
            } else {
                $this->event['timeFrom'] = '<option>'.$this->event['timeFrom'].'</option>';
                $this->event['timeTo'] = '<option>'.$this->event['timeTo'].'</option>';
            }

        } else {

            if($this->joined) {

                if($this->ueidExist) {
                    $this->event['attending'] = self::getAttendOptions($this->event['attending']);
                    $this->event['privacy'] = '<select name="privacy">'.self::getPrivacyOptions($this->event['privacy']).'</select>';
                    $this->event['remember'] = '<input type="checkbox" name="remember" value="1" '.self::getNotificationOption($this->event['remember']).' />&nbsp&nbsp<small><i>(get a notafication before event)</i></small>';
                    $this->event['update_join_button'] = '';
                    $this->event['cancel_button'] = '<div id="cancel_full"><input id="no_div" type="button" onclick="tb_remove();" value="close" /></div>';
                } else {
                    $this->event['attending'] = self::getAttendOptionsPlain();
                    $this->event['privacy'] = self::getPrivacyOptionsPlain();
                    $this->event['remember'] = '<input type="checkbox" name="remember" value="1" />&nbsp&nbsp<small><i>(get a notafication before event)</i></small>';
                    $this->event['update_join_button'] = '<div id="update_join_button"><input id="submit_div" type="button" onclick="joinEvent('.$this->event['eid'].');" value="attending event" name="join"></div>';
                }

                if($this->event['adjustable'] === 'flexible') {
                    $this->event['timeFrom'] = self::getFromTimeOptions($this->event['timeFrom']);
                    $this->event['timeTo'] = self::getToTimeOptions($this->event['timeTo']);
                    $this->event['readonly'] = NULL;
                    $this->event['fixedTime'] = NULL;
                    $this->event['fixedDate'] = 'id="dateFrom"';
                } else {
                    $this->event['timeFrom'] = '<option>'.$this->event['timeFrom'].'</option>';
                    $this->event['timeTo'] = '<option>'.$this->event['timeTo'].'</option>';
                }

            } elseif(!$this->joined && !$this->event['need_invitation']) {

                if($this->ueidExist) {
                    $this->event['attending'] = self::getAttendOptions($this->event['attending']);
                    $this->event['privacy'] = '<select name="privacy">'.self::getPrivacyOptions($this->event['privacy']).'</select>';
                    $this->event['remember'] = '<input type="checkbox" name="remember" value="1" '.self::getNotificationOption($this->event['remember']).' />&nbsp&nbsp<small><i>(get a notafication before event)</i></small>';
                    if(!$this->joined) {
                        $this->event['update_join_button'] = '<div id="update_join_button"><input id="submit_div" type="button" onclick="joinEvent('.$this->event['eid'].');" value="attending event" name="join"></div>';
                    } else {
                        $this->event['update_join_button'] = '<div id="update_join_button"><input id="submit_div" type="button" onclick="updateEvent('.$this->event['eid'].');" value="update event" name="update"></div>';                        
                    }

                } else {
                    $this->event['attending'] = self::getAttendOptionsPlain();
                    $this->event['privacy'] = self::getPrivacyOptionsPlain();
                    $this->event['remember'] = '<input type="checkbox" name="remember" value="1" />&nbsp&nbsp<small><i>(get a notafication before event)</i></small>';
                    $this->event['update_join_button'] = '<div id="update_join_button"><input id="submit_div" type="button" onclick="joinEvent('.$this->event['eid'].');" value="attending event" name="join"></div>';
                }

                if($this->event['adjustable'] === 'flexible') {
                    $this->event['timeFrom'] = self::getFromTimeOptions($this->event['timeFrom']);
                    $this->event['timeTo'] = self::getToTimeOptions($this->event['timeTo']);
                    $this->event['readonly'] = NULL;
                    $this->event['fixedTime'] = NULL;
                    $this->event['fixedDate'] = 'id="dateFrom"';
                } else {
                    $this->event['timeFrom'] = '<option>'.$this->event['timeFrom'].'</option>';
                    $this->event['timeTo'] = '<option>'.$this->event['timeTo'].'</option>';
                }

            } else {

                if($this->event['owner'] == $this->user['myuid']) {
                    $this->event['update_join_button'] = '<div id="update_join_button"><input id="submit_div" type="button" onclick="joinEvent('.$this->event['eid'].');" value="attending event" name="join"></div>';
                } else {
                    
                    if($this->ueidExist === false) {
                        $this->ueidRequest = 'false';
                    } else {
                        $this->ueidRequest = $this->ueid;
                    }
                    $this->event['update_join_button'] = '<div id="update_join_button"><input id="submit_div" type="button" onclick="requestEventInvitation('.$this->event['eid'].', '.$this->ueidRequest.');" value="request invitation" name="request"></div>';
                }

                $this->event['fixedTime'] = NULL;
                $this->event['attending'] = NULL;
                $this->event['privacy'] = NULL;
                $this->event['remember'] = NULL;
                $this->event['timeFrom'] = '<option>'.$this->event['timeFrom'].'</option>';
                $this->event['timeTo'] = '<option>'.$this->event['timeTo'].'</option>';

            }

        }

        if(isset($_GET['showComment'])) {
            $this->eventCommentDisplay = 'display:block';
            $this->eventMainDisplay = 'display:none';
        } else {
            $this->eventCommentDisplay = 'display:none';
            $this->eventMainDisplay = 'display:block';
        }

        ob_start();

            include('public/templates/default/html/eventView.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventDeleteTemplate() {

        ob_start();

            include('public/templates/default/html/eventDelete.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventMapTemplate() {

        ob_start();

            include('public/templates/default/html/eventMap.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventUnreadCommentTemplate() {

        ob_start();

            include('public/templates/default/html/eventUnreadComment.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventInviteTemplate() {

        $this->friendSelect = self::getFriendsSelect($this->friendInfoArray);

        ob_start();

            include('public/templates/default/html/eventInvite.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventAllTemplate() {

        ob_start();

            include('public/templates/default/html/eventAll.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventMutualTemplate() {

        ob_start();

            include('public/templates/default/html/eventMutual.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventInviteUserTemplate() {

        ob_start();

            include('public/templates/default/html/eventInviteUser.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }


    public function loadEventSearchTemplate() {

        ob_start();

            include('public/templates/default/html/eventSearch.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadEventInvitationRequestTemplate() {

        ob_start();

            include('public/templates/default/html/eventInvitationRequest.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    private function checkInvitationNeed($joinable) {

        if($joinable > 1) {
            $joinable = false;
        } else {
            $joinable = true;
        }

        return $joinable;

    }

    public function correctDateFormat($dateFrom) {

        $dateFromArray = explode('-', $dateFrom);
        $dateFrom = $dateFromArray[2].'/'.$dateFromArray[1].'/'.$dateFromArray[0];

        return $dateFrom;

    }

    private function getPrivacyOptions($privacy) {

        $optionArray = array(0 => 'visible only to me', 1 => 'visible to friends', 2 => 'visible to friends of friends', 3 => 'visible to everybody');
        $privacy = self::getDropdownOptions($optionArray, $privacy);

        return $privacy;

    }

    private function getSearchableOptions($searchable) {

        $optionArray = array(1 => 'available', 0 => 'not available');
        $searchable = self::getDropdownOptions($optionArray, $searchable);

        return $searchable;

    }

    private function getJoinableOptions($joinable) {

        $optionArray = array(0 => 'invitation of me', 1 => 'invitation of attenders', 2 => 'open to join');
        $joinable = self::getDropdownOptions($optionArray, $joinable);

        return $joinable;

    }

    private function getFromTimeOptions($timeFrom) {

        $optionArray = array();

        for($i = 0; $i <= 24; $i++) {
            $optionArray[$i] = str_pad($i, 2, '0', STR_PAD_LEFT).':00';
        }

        $fromTimeOptions = self::getTimeOptions($optionArray, $timeFrom);

        return $fromTimeOptions;

    }

    private function getToTimeOptions($timeTo) {

        $optionArray = array();

        for($i = 0; $i <= 24; $i++) {
            $optionArray[$i] = str_pad($i, 2, '0', STR_PAD_LEFT).':00';
        }

        $toTimeOptions = self::getTimeOptions($optionArray, $timeTo);

        return $toTimeOptions;

    }

    private function getEventNameCount($name) {
        $eventNameCount = 64 - (int)strlen($name);
        return $eventNameCount;
    }

    private function getAttendOptions($attending) {

        $optionArray = array(0 => 'sure', 1 => 'maybe', 2 => 'open');
        $optionNameArray = array(0 => '<font color="#99EE99"><b>sure</b></font>', 1 => '<font color="#FFEE99"><b>maybe</b></font>', 2 => '<font color="#FF9999"><b>open</b></font>');
        $optionArrayLenght = count($optionArray);
        $optionsRadio = '';

        for($a = 0; $a < $optionArrayLenght; $a++) {

            if($attending === $optionArray[$a]) {
                $checked = 'checked="checked"';
            } else {
                $checked = '';
            }

            $optionsRadio .= '&nbsp;&nbsp;&nbsp;'.$optionNameArray[$a].'&nbsp;<input type="radio" name="attend" value="'.$optionArray[$a].'" '.$checked.'/>';

        }

        return $optionsRadio;

    }

    private function getFixedTimeChecked($adjustable) {

        if($adjustable == 'fixed') {
            $fixedTimeChecked = 'checked="checked"';
        } else {
            $fixedTimeChecked = '';
        }

        return $fixedTimeChecked;

    }

    private function getNotificationOption($remember) {

        if($remember == 1) {
            $notificationChecked = 'checked="checked"';
        } else {
            $notificationChecked = '';
        }

        return $notificationChecked;

    }

    private function getDropdownOptions($optionArray, $resultSelect) {

        $optionArrayLenght = count($optionArray);

        $optionDropdown = '';

        for($a = 0; $a < $optionArrayLenght; $a++) {

            if($resultSelect == $a) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }

            $optionDropdown .= '<option value="'.$a.'" '.$selected.'>'.$optionArray[$a].'</option>';

        }

        return $optionDropdown;

    }

    private function getTimeOptions($optionArray, $resultSelect) {

        $optionArrayLenght = count($optionArray);

        $optionDropdown = '';

        for($a = 0; $a < $optionArrayLenght; $a++) {

            if($resultSelect == $optionArray[$a]) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }

            $optionDropdown .= '<option value="'.$optionArray[$a].'" '.$selected.'>'.$optionArray[$a].'</option>';

        }

        return $optionDropdown;

    }

    private function getFriendsSelect($friendInfoArray) {

        $friendSelect = '';

        foreach($friendInfoArray as $friendInfo) {
            $friendSelect .= '<option value="'.$friendInfo['uid'].'">'.$friendInfo['name'].'</option>';
        }

        return $friendSelect;

    }

    private function getAttendOptionsPlain() {

        $attendOptionsPlain = '&nbsp;&nbsp;&nbsp;<font color="#99EE99"><b>sure</b></font>&nbsp;<input type="radio" name="attend" value="sure" checked/>&nbsp;&nbsp;&nbsp;<font color="#FFEE99"><b>maybe</b></font>&nbsp;<input type="radio" name="attend" value="maybe" />&nbsp;&nbsp;&nbsp;<font color="#FF9999"><b>open</b></font>&nbsp;<input type="radio" name="attend" value="open" />';
        return $attendOptionsPlain;

    }

    private function getPrivacyOptionsPlain() {

        $privacyOptionsPlain = '<select name="privacy"><option value="0">visible only to me</option><option value="1">visible to friends</option><option value="2">visible to friends of friends</option><option value="3" selected="selected">visible to everybody</option></select>';
        return $privacyOptionsPlain;

    }

}

?>