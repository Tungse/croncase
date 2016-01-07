<?php

class application_frontend_profile_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_profile_model();
        $this->view = new application_frontend_profile_view();
        $this->user = $this->view->user = $this->model->user = $GLOBALS['user'];
        parent::checkLogin($this->user['login']);

    }

    public function actionController() {

        $_GET = parent::filter($_GET);
        self::handleGET();

        if(!empty($_POST)) {
            $_POST = parent::filter($_POST);
            self::handlePOST();
        }

        return self::display();

    }

    private function handleGET() {

        if(isset($_GET['action']) && !empty($_GET['action'])) {

            switch($_GET['action']) {

                case 'edit':
                    $this->template = 'edit';
                    break;
                case 'account':
                    $this->template = 'account';
                    break;
                default:
                    parent::redirect('home');

            }

        } else {
            $this->template = 'view';
        }

    }

    private function handlePOST() {

        if(isset($_POST['save'])) {

            $this->model->privacy = array('privacyBirth' => $_POST['privacyBirth'],
                                    'privacyRelationship' => $_POST['privacyRelationship'],
                                    'privacyEmail' => $_POST['privacyEmail'],
                                    'privacyAboutMe' => $_POST['privacyAboutMe'],
                                    'privacyWebsite' => $_POST['privacyWebsite'],
                                    'privacyPolitics' => $_POST['privacyPolitics'],
                                    'privacyActivities' => $_POST['privacyActivities'],
                                    'privacyInterests' => $_POST['privacyInterests'],
                                    'privacyFilms' => $_POST['privacyFilms'],
                                    'privacyQuotations' => $_POST['privacyQuotations'],
                                    'privacySchool' => $_POST['privacySchool'],
                                    'privacyUniversity' => $_POST['privacyUniversity'],
                                    'privacyEmployer' => $_POST['privacyEmployer']);
            $this->model->basic = array('gender' => $_POST['gender'],
                                    'birth' => $_POST['days'].' '.$_POST['months'].' '.$_POST['years'],
                                    'relationship' => $_POST['relationship'],
                                    'email' => $_POST['email'],
                                    'aboutMe' => $_POST['aboutMe'],
                                    'website' => $_POST['website'],
                                    'location' => $_POST['location'],
                                    'hometown' => $_POST['hometown'],
                                    'politics' => $_POST['politics'],
                                    'religious' => $_POST['religious']);
            $this->model->interests= array('activities' => $_POST['activities'],
                                    'interests' => $_POST['interests'],
                                    'films' => $_POST['films'],
                                    'music' => $_POST['music'],
                                    'books' => $_POST['books'],
                                    'quotations' => $_POST['quotations']);
            $this->model->education = array('school' => $_POST['school'],
                                    'leavingYearsSchool' => $_POST['leavingYearsSchool'],
                                    'university' => $_POST['university'],
                                    'leavingYearsUniversity' => $_POST['leavingYearsUniversity'],
                                    'courses' => $_POST['courses'],
                                    'employer' => $_POST['employer'],
                                    'position' => $_POST['position'],
                                    'description' => $_POST['description'],
                                    'city' => $_POST['city'],
                                    'since' => $_POST['fromMonths'].' '.$_POST['fromYears']);
            $this->model->saveUserProfile();

            parent::redirect('home');

        } elseif(isset($_POST['accountChange']) && $_POST['accountChange'] == true) {

            $this->input = array('calendar' => $_POST['calendar'],
                                    'status' => $_POST['status'],
                                    'comment' => $_POST['comment'],
                                    'friend' => $_POST['friend'],
                                    'commentPage' => $_POST['commentPage'],
                                    'commentSub' => $_POST['commentSub'],
                                    'eventInvite' => $_POST['eventInvite'],
                                    'eventComment' => $_POST['eventComment'],
                                    'eventRequest' => $_POST['eventRequest'],
                                    'eventUpdate' => $_POST['eventUpdate'],
                                    'privateMessage' => $_POST['privateMessage'],
                                    'friendRequest' => $_POST['friendRequest'],
                                    'firstname' => $_POST['firstname'],
                                    'lastname' => $_POST['lastname'],
                                    'email' => $_POST['email'],
                                    'password' => $_POST['password']);

            if(isset($this->input['password']) && !empty($this->input['password'])) {
                $this->model->saveUserPassword(md5($this->input['password']));
            }
            
            $this->model->saveUserPrivacy($this->input);
            $this->model->saveUserAccount($this->input);
            $this->model->saveNotificationSetting($this->input);

        }

    }

    private function display() {

        switch($this->template) {

            case 'view':

                $this->view->profilePrivacy = $this->model->profilePrivacy = $this->model->getProfilePrivacy();
                $this->view->userProfile = $this->model->getUserProfile();
                echo $this->view->loadProfileTemplate();
                break;

            case 'edit':

                $this->view->profilePrivacy = $this->model->profilePrivacy = $this->model->getProfilePrivacy();
                $this->view->userProfile = $this->model->getUserProfile();
                echo $this->view->loadProfileEditTemplate();
                break;

            case 'account':

                $this->view->userNotification = $this->model->getNotificationSetting($this->user['myuid']);
                $this->view->userPrivacy = $this->model->getUserPrivacy($this->user['myuid']);
                $this->view->userAccount = $this->model->getUserAccount($this->user['myuid']);
                echo $this->view->loadProfileAccountTemplate();
                break;

        }

    }

}

?>