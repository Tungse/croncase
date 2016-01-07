<?php

function __autoload($name) {

    try {

        $file = getFile($name);

        if($file !== NULL) {
            include($file);
        } else {
            throw new Exception('Die Klasse <font color="red">'.$name.'</font> wurde nicht gefunden.');
        }

        if (!class_exists($name)) {
            throw new Exception('Die Datei <b>'.$file.'</b> enth&auml;lt nicht die Klasse <font color="red">'.$name.'</font>');
        }

        return true;

    } catch (Exception $error) {
        die($error->getMessage());
    }

}

function getFile($name) {

    $filePath = str_replace('_', '/', $name).'.php';

    if(file_exists($filePath)) {
        $file = $filePath;
    } else {
        $file = NULL;
    }

    return $file;

}

?>