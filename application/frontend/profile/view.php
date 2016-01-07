<?php

class application_frontend_profile_view extends library_default_classes_view {

    public function loadProfileTemplate() {

        ob_start();

            include('public/templates/default/html/profile.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadProfileEditTemplate() {

        self::getOptionSelected();
        self::getProfilePrivacy();

        ob_start();

            include('public/templates/default/html/profileEdit.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    public function loadProfileAccountTemplate() {

        $this->notification = self::getUserNotification();
        self::getUserPrivacy();
        $this->userAccount['activation'] = self::getActivationStatus();

        ob_start();

            include('public/templates/default/html/profileAccount.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    private function getUserPrivacy() {

        $privacyArray = array(0 => 'calendar', 1 => 'status', 2 => 'comment', 3 => 'friend');
        $optionArray = array(0 => 'only me', 1 => 'only friends', 2 => 'friends of friends', 3 => 'puplic');
        
        $this->privacy = self::getPrivacyOption($privacyArray, $this->userPrivacy, $optionArray);

    }

    private function getProfilePrivacy() {

        $privacyArray = array(0 => 'birth', 1 => 'relationship', 2 => 'email', 3 => 'aboutMe', 4 => 'website', 5 => 'location', 6 => 'hometown', 7 => 'politics', 8 => 'religious',
                                9 => 'activities', 10 => 'interests', 11 => 'films', 12 => 'music', 13 => 'books', 14 => 'quotations',
                                15 => 'school', 16 => 'leavingYearsSchool', 17 => 'university', 18 => 'leavingYearsUniversity', 19 => 'courses', 20 => 'employer', 21 => 'position', 22 => 'description', 23 => 'city', 24 => 'since');
        $optionArray = array(0 => 'only me', 1 => 'only friends', 2 => 'friends of friends', 3 => 'puplic');

        $this->privacy = self::getPrivacyOption($privacyArray, $this->profilePrivacy, $optionArray);

    }

    private function getOptionSelected() {

        $optionArray = array(0 => 'female', 1 => 'male');
        $resultSelect = $this->userProfile['gender'];
        $this->genderOption = self::getOption($optionArray, $resultSelect);

        for($years = 2015, $a = 1; $years >= 1950; $years--, $a++) {
            $optionArray[$a] = $years;
        }
        $optionArray[0] = '';
        $resultSelect = $this->userProfile['leavingYearsSchool'];
        $this->leavingYearsSchoolOption = self::getOption($optionArray, $resultSelect);

        for($years = 2015, $a = 1; $years >= 1950; $years--, $a++) {
            $optionArray[$a] = $years;
        }
        $optionArray[0] = '';
        $resultSelect = $this->userProfile['leavingYearsUniversity'];
        $this->leavingYearsUniversityOption = self::getOption($optionArray, $resultSelect);

        $optionArray = array(0 => '', 1 => 'Single', 2 => 'In a relationship', 3 => 'Engaged', 4 => 'Married', 5 => 'It is complicated', 6 => 'In an open relationship');
        $resultSelect = $this->userProfile['relationship'];
        $this->relationshipOption = self::getOption($optionArray, $resultSelect);

        $optionArray = array(0 => '', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
        $resultSelect = substr($this->userProfile['since'], 0, -5);
        $this->fromMonthsOption = self::getOption($optionArray, $resultSelect);

        for($years = 2010, $a = 1; $years >= 1950; $years--, $a++) {
            $optionArray[$a] = $years;
        }
        $optionArray[0] = '';
        $resultSelect = substr($this->userProfile['since'], -4);
        $this->fromYearsOption = self::getOption($optionArray, $resultSelect);

        for($years = 2009, $a = 1; $years >= 1950; $years--, $a++) {
            $optionArray[$a] = $years;
        }
        $optionArray[0] = '';
        $resultSelect = substr($this->userProfile['birth'], -4);
        $this->birthYearOption = self::getOption($optionArray, $resultSelect);

        $optionArray = array(0 => '', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
        $resultSelect = substr($this->userProfile['birth'], 3, -5);
        $this->birthMonthOption = self::getOption($optionArray, $resultSelect);

        for($days = 1, $a = 1; $days <= 31; $days++, $a++) {

            if(strlen($days) < 2) {
                $optionArray[$a] = '0'.$days;
            } else {
                $optionArray[$a] = $days;
            }

        }
        $optionArray[0] = '';
        $resultSelect = substr($this->userProfile['birth'], 0, 2);
        $this->birthDayOption = self::getOption($optionArray, $resultSelect);

    }

    private function getOption($optionArray, $resultSelect) {

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

    private function getPrivacyOption($privacyArray, $savedPrivacy, $optionArray) {

        $privacyArrayLenght = count($privacyArray);
        $optionArrayLenght = count($optionArray);
        
        for($i = 0; $i < $privacyArrayLenght; $i++) {

            $optionDropdown[$privacyArray[$i]] = '';

            for($a = 0; $a < $optionArrayLenght; $a++) {

                if($savedPrivacy[$i] == $a) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }

                $optionDropdown[$privacyArray[$i]] .= '<option value="'.$a.'" '.$selected.'>'.$optionArray[$a].'</option>';

            }

        }

        return $optionDropdown;

    }

    private function getActivationStatus() {

        if($this->userAccount['activation'] == 0) {
            $accountActivation = '<input type="checkbox" id="activation" name="activation" checked="checked" value="0" />';
        } else {
            $accountActivation = '<input type="checkbox" id="activation" name="activation" value="0" />';
        }

        return $accountActivation;

    }

    private function getUserNotification() {

        $notification['comment'] = self::getRadioOptions('commentPage', $this->userNotification['comment']);
        $notification['commentSub'] = self::getRadioOptions('commentSub', $this->userNotification['commentSub']);
        $notification['eventInvite'] = self::getRadioOptions('eventInvite', $this->userNotification['eventInvite']);
        $notification['eventComment'] = self::getRadioOptions('eventComment', $this->userNotification['eventComment']);
        $notification['eventRequest'] = self::getRadioOptions('eventRequest', $this->userNotification['eventRequest']);
        $notification['eventUpdate'] = self::getRadioOptions('eventUpdate', $this->userNotification['eventUpdate']);
        $notification['privateMessage'] = self::getRadioOptions('privateMessage', $this->userNotification['privateMessage']);
        $notification['friendRequest'] = self::getRadioOptions('friendRequest', $this->userNotification['friendRequest']);

        return $notification;

    }

    private function getRadioOptions($notificationName, $userNotification) {

        if($userNotification == 1) {
            $radioOptions = '<input type="radio" id="'.$notificationName.'" name="'.$notificationName.'" value="1" checked="checked" />&nbsp;yes&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="'.$notificationName.'" name="'.$notificationName.'" value="0" />&nbsp;no';
        } else {
            $radioOptions = '<input type="radio" id="'.$notificationName.'" name="'.$notificationName.'" value="1" />&nbsp;yes&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="'.$notificationName.'" name="'.$notificationName.'" value="0" checked="checked" />&nbsp;no';
        }

        return $radioOptions;

    }

}

?>