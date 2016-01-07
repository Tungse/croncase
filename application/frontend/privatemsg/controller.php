<?php

class application_frontend_privatemsg_controller extends library_default_classes_controller {

    public function  __construct() {

        $this->model = new application_frontend_privatemsg_model();
        $this->view = new application_frontend_privatemsg_view();
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

                case 'write':
                    if(isset($_GET['feedback'])) {
                        $this->view->feedback = 'true';
                    } else {
                        $this->view->feedback = 'false';
                    }
                    $this->template = 'write';
                    break;
                case 'view':
                    self::loadNavi();
                    $this->view->privatemsg = $this->model->getAllPrivatemsg($this->user['myuid']);

                    if(isset($this->view->privatemsg['unread'][0])) {
                        $this->model->privatemsgReaded($this->view->privatemsg['unread'][0]['pid']);
                    }

                    $this->template = 'view';
                    break;
                case 'delete':
                    $this->template = 'delete';
                    break;

            }

        }

    }

    private function handlePOST() {

        if(isset($_POST['privatemsgWrite']) && $_POST['privatemsgWrite'] == true) {

            if($_POST['feedback'] == 'true') {
                $reader = 1;
            } else {
                $reader = (int)$_POST['uid'];
            }

            $this->input = array('writer' => $this->user['myuid'], 'reader' => $reader, 'subject' => $_POST['subject'], 'content' => $_POST['content']);
            $this->model->savePrivatemsgWrite($this->input);
            self::sendNotificationEmail('privateMessage', $this->input);

        } elseif(isset($_POST['privatemsgRead'])) {
            $this->model->privatemsgReaded($_POST['pid']);
        } elseif(isset($_POST['privatemsgAnswer'])) {
            $this->input = array('pid' => $_POST['pid'], 'writer' => $this->user['myuid'], 'reader' => $_POST['reader'], 'content' => $_POST['content']);
            $this->model->savePrivatemsgAnswer($this->input);
            self::sendNotificationEmail('privateMessage', $this->input);
        } elseif(isset($_POST['deletePrivatemsg'])) {
            $this->model->deletePrivatemsg($_POST['pid']);
        } elseif(isset($_POST['deletePrivatemsgSended'])) {
            $this->model->deletePrivatemsgSended($_POST['psid']);
        }

    }

    private function display() {

        switch($this->template) {

            case 'write':
                echo $this->view->loadPrivatemsgWriteTemplate();
                break;
            case 'view':
                echo $this->view->loadPrivatemsgTemplate();
                break;
            case 'delete':
                echo $this->view->loadPrivatemsgDeleteTemplate();
                break;

        }

    }

    private function loadNavi() {

        $this->search = new application_frontend_search_controller();
        $this->view->application['search'] = $this->search->display();

    }

    private function sendNotificationEmail($content, $input) {

        switch($content) {

            case 'privateMessage':

                $sendEmailTo = $this->model->getSendEmailUser($input['reader']);
                if($sendEmailTo['privateMessage'] == 1) {

                    $emailContent['privateMsg'] = $input['content'];
                    $emailContent['receiver'] = $sendEmailTo['firstname'];
                    $emailContent['writer'] = $this->user['myinfo']['firstname'];
                    $this->email = new application_frontend_email_controller();
                    $this->email->sendEmail($sendEmailTo['email'], 'newPrivateMessage', $emailContent);

                }
                break;

        }

    }

}

?>