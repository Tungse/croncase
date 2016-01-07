<?php

error_reporting(E_ALL);
setlocale(LC_ALL, 'de_DE');

session_start();

include('includes/config.php');
include('library/default/functions/autoload.php');
include('library/default/functions/initialize.php');


$microtimeFloatStart = microtime(true);

$database = new library_default_classes_database();
$database->connect();

$GLOBALS['user'] = application_frontend_user_controller::getUser();

if(isset($_GET['app']) && !empty($_GET['app'])) {
    $object = initialize($_GET['app']);
    $object->actionController();
} else {
    $object = new application_frontend_index_controller();
    $object->actionController();
}


$microtimeFloat = microtime(true) - $microtimeFloatStart;
//mysql_query("insert into _loadtime (uid, app, referer, ip, browser, microtime, created) values ('".$GLOBALS['user']['myuid']."', '".$_SERVER['REQUEST_URI']."', '".$_SERVER['HTTP_REFERER']."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '".$microtimeFloat."', now())");


?>