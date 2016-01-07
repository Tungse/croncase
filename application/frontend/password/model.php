<?php

class application_frontend_password_model extends library_default_classes_model {

    public function checkEmailExist($email) {

        $query = "select uid from users where email = '".$email."'";
        $queryResult = parent::query($query);

        return parent::rowExist($queryResult);

    }

    public function setNewPassword($newPassword, $email) {

        $query = "update users set password = '".$newPassword."' where email = '".$email."'";
        parent::query($query);

    }

}


?>
