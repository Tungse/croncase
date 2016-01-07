<?php

class application_frontend_profile_model extends library_default_classes_model {

    public function getProfilePrivacy() {

        $query = "select birth, relationship, email, aboutMe, website, location, hometown, politics, religious,
                    activities, interests, films, music, books, quotations,
                    school, leavingYearsSchool, university, leavingYearsUniversity, courses, employer, position, description, city, since
                    from profilePrivacy where uid = ".$this->user['uid'];
        $queryResult = parent::query($query);

        return parent::fetchRow($queryResult);

    }

    public function getUserPrivacy($uid) {

        $query = "select calendar, status, comment, friend from userPrivacy where uid = ".$uid;
        $queryResult = parent::query($query);

        return parent::fetchArray($queryResult);

    }

    public function getUserAccount($uid) {

        $query = "select firstname, lastname, email, activation from users where uid = ".$uid;
        $queryResult = parent::query($query);

        return parent::fetchArray($queryResult);

    }

    public function getUserProfile() {

        if($this->user['page']) {
            $userProfile = self::getAllProfileData();
        } else {
            $userProfile = self::getProfileDataOnRelationStatus();
        }

        $nonPrivacyData = self::getNonPrivacyData();

        $userProfile = array_merge($nonPrivacyData, $userProfile);
        
        return $userProfile;
       
    }

    private function getAllProfileData() {

        $query = "select pb.birth, pb.relationship, pb.email, pb.aboutMe, pb.website, pb.location, pb.hometown, pb.politics, pb.religious,
                    pi.activities, pi.interests, pi.films, pi.music, pi.books, pi.quotations,
                    pe.school, pe.leavingYearsSchool, pe.university, pe.leavingYearsUniversity, pe.courses, pe.employer, pe.position, pe.description, pe.city, pe.since
                    from profileBasic as pb, profileInterest as pi, profileEducation as pe
                    where pb.uid = pi.uid and pb.uid = pe.uid and pb.uid = ".$this->user['uid'];
        $queryResult = parent::query($query);

        return parent::fetchAssoc($queryResult);

    }

    private function getProfileDataOnRelationStatus() {

        $userProfile = self::getAllProfileData();

        $arrayLenght = count($userProfile);
        $profileContent = array(0 => 'birth', 1 => 'relationship', 2 => 'email', 3 => 'aboutMe', 4 => 'website', 5 => 'location', 6 => 'hometown', 7 => 'politics', 8 => 'religious',
                                9 => 'activities', 10 => 'interests', 11 => 'films', 12 => 'music', 13 => 'books', 14 => 'quotations',
                                15 => 'school', 16 => 'leavingYearsSchool', 17 => 'university', 18 => 'leavingYearsUniversity', 19 => 'courses', 20 => 'employer', 21 => 'position', 22 => 'description', 23 => 'city', 24 => 'since');

        for($i = 0; $i < $arrayLenght; $i++) {
            
            if($this->profilePrivacy[$i] < $this->user['relation']) {
                $userProfile[$profileContent[$i]] = '<font color="#666"><i>privacy</i></small></font>';
            }

        }

        return $userProfile;
        
    }

    private function getNonPrivacyData() {

        $query = "select gender from profileBasic where uid = ".$this->user['uid'];
        $queryResult = parent::query($query);

        $result['gender'] = parent::queryResult($queryResult, 'gender');

        return $result;

    }

    public function saveUserPrivacy($input) {

        $query = "update userPrivacy set calendar = ".$input['calendar'].", status = ".$input['status'].", comment = ".$input['comment'].", friend = ".$input['friend']." where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    public function saveUserAccount($input) {

        $query = "update users set firstname = '".$input['firstname']."', lastname = '".$input['lastname']."', email = '".$input['email']."' where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    public function saveUserPassword($password) {

        $query = "update users set password = '".$password."' where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    public function saveUserProfile() {

        self::saveProfilePrivacy();
        self::saveProfileBasic();
        self::saveProfileInterest();
        self::saveProfileEducation();
        self::updateUserEmail();

    }

    private function saveProfilePrivacy() {

        $query = "update profilePrivacy set
                    birth = ".$this->privacy['privacyBirth'].",
                    relationship = ".$this->privacy['privacyRelationship'].",
                    email = ".$this->privacy['privacyEmail'].",
                    aboutMe = ".$this->privacy['privacyAboutMe'].",
                    website = ".$this->privacy['privacyWebsite'].",
                    location = ".$this->privacy['privacyWebsite'].",
                    hometown = ".$this->privacy['privacyWebsite'].",
                    politics = ".$this->privacy['privacyPolitics'].",
                    religious = ".$this->privacy['privacyPolitics'].",
                    activities = ".$this->privacy['privacyActivities'].",
                    interests = ".$this->privacy['privacyInterests'].",
                    films = ".$this->privacy['privacyFilms'].",
                    music = ".$this->privacy['privacyFilms'].",
                    books = ".$this->privacy['privacyFilms'].",
                    quotations = ".$this->privacy['privacyQuotations'].",
                    school = ".$this->privacy['privacySchool'].",
                    leavingYearsSchool = ".$this->privacy['privacySchool'].",
                    university = ".$this->privacy['privacyUniversity'].",
                    leavingYearsUniversity = ".$this->privacy['privacyUniversity'].",
                    courses = ".$this->privacy['privacyUniversity'].",
                    employer = ".$this->privacy['privacyEmployer'].",
                    position = ".$this->privacy['privacyEmployer'].",
                    description = ".$this->privacy['privacyEmployer'].",
                    city = ".$this->privacy['privacyEmployer'].",
                    since = ".$this->privacy['privacyEmployer'].",
                    modify = now() where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    private function saveProfileBasic() {

        $query = "update profileBasic
                    set gender = '".$this->basic['gender']."',
                    birth = '".$this->basic['birth']."',
                    relationship = '".$this->basic['relationship']."',
                    email = '".$this->basic['email']."',
                    aboutMe = '".$this->basic['aboutMe']."',
                    website = '".$this->basic['website']."',
                    location = '".$this->basic['location']."',
                    hometown = '".$this->basic['hometown']."',
                    politics = '".$this->basic['politics']."',
                    religious = '".$this->basic['religious']."'
                    where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    private function saveProfileInterest() {

        $query = "update profileInterest
                    set activities = '".$this->interests['activities']."',
                    interests = '".$this->interests['interests']."',
                    films = '".$this->interests['films']."',
                    music = '".$this->interests['music']."',
                    books = '".$this->interests['books']."',
                    quotations = '".$this->interests['quotations']."'
                    where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    private function saveProfileEducation() {

        $query = "update profileEducation
                    set school = '".$this->education['school']."',
                    leavingYearsSchool = '".$this->education['leavingYearsSchool']."',
                    university = '".$this->education['university']."',
                    leavingYearsUniversity = '".$this->education['leavingYearsUniversity']."',
                    courses = '".$this->education['courses']."',
                    employer = '".$this->education['employer']."',
                    position = '".$this->education['position']."',
                    description = '".$this->education['description']."',
                    city = '".$this->education['city']."',
                    since = '".$this->education['since']."'
                    where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    private function updateUserEmail() {

        $query = "update users set email = '".$this->basic['email']."' where uid = ".$this->user['myuid'];
        parent::query($query);

    }

    public function getNotificationSetting($uid) {

        $query = "select comment, commentSub, eventInvite, eventComment, eventRequest, eventUpdate, privateMessage, friendRequest from notificationSetting where uid = ".$uid;
        $queryResult = parent::query($query);

        return parent::fetchArray($queryResult);

    }

    public function saveNotificationSetting($input) {

        $query = "update notificationSetting set comment = '".$input['commentPage']."', commentSub = '".$input['commentSub']."', eventInvite = '".$input['eventInvite']."', eventComment = '".$input['eventComment']."', eventRequest = '".$input['eventRequest']."', eventUpdate = '".$input['eventUpdate']."', privateMessage = '".$input['privateMessage']."', friendRequest = '".$input['friendRequest']."' where uid = ".$this->user['myuid'];
        parent::query($query);

    }

}

?>