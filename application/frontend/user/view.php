<?php

class application_frontend_user_view extends library_default_classes_view {

    public function loadUploadAvatarTemplate() {

        self::checkBackgroundImage();

        ob_start();

            include('public/templates/default/html/uploadAvatar.phtml');
            $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

    private function checkBackgroundImage() {

        if(file_exists('data/users/'.$this->user['md5'].'/home/background.jpg')) {
            $this->background = true;
        } else {
            $this->background = false;
        }

    }

}

?>