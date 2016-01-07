<?php

class library_default_classes_model {

    protected function query($query) {

        $result = mysql_query($query) or self::showError($query, mysql_errno(), mysql_error());
        return $result;

    }

    protected function rowExist($query) {

        if(mysql_num_rows($query) > 0) {
            return true;
        } else {
            return false;
        }

    }

    protected function rowAffected() {

        $result = mysql_affected_rows();
        return $result;

    }

    protected function numRows($query) {
        return mysql_num_rows($query);
    }

    protected function fetchArray($query) {

        $array = mysql_fetch_array($query);
        return $array;

    }

    protected function fetchAssoc($query) {

        $array = mysql_fetch_assoc($query);
        return $array;

    }

    protected function fetchRow($query) {

        $array = mysql_fetch_row($query);
        return $array;

    }

    protected function fetchLength($query) {

        $result = mysql_fetch_lengths($query);
        return $result;

    }

    protected function queryResult($query, $key) {

        $result = mysql_result($query, 0, $key);
        return $result;

    }

    private function showError($query, $errno, $error) {

        die('<font color="#FF0000"><b>'.$errno.'</font> - '.$error.'</b><br><br>'.$query);

    }
    
}


?>