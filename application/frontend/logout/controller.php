<?php

class application_frontend_logout_controller extends library_default_classes_controller {

    public function actionController() {

        session_unset();
        parent::redirect('index');

    }

}

?>