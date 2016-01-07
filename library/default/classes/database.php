<?php

class library_default_classes_database {

    public function __construct() {

        $this->databaseName = 'project';
        $this->address = 'localhost';
        $this->name = 'root';
        $this->password = '';

    }

    public function connect() {

        $this->databaseConnect = mysql_connect($this->address, $this->name, $this->password) or die("Keine Verbindung m&ouml;glich: ".mysql_error());
        mysql_select_db($this->databaseName) or die("Auswahl der Datenbank fehlgeschlagen");

    }

    public function disconnect() {
        mysql_close($this->databaseConnect);
    }

}


?>