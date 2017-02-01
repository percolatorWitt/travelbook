<?php 
/**
In dieser Datei keine Ausgaben, nur LoginFormular oder Umleitung
**/
session_start();

require_once("system/witt.php");
require_once("system/database.php");
require_once("system/autoloader.php");
//var_dump($_REQUEST);
Autoloader::register();

function controller_autoload($class_name){
    require_once 'controller/'.strtolower($class_name).'.php';
}

function model_autoload($class_name){
    require_once 'model/'.strtolower($class_name).'.php';
}
require_once './system/Dwoo-1.2.0/lib/dwooAutoload.php';

spl_autoload_register('controller_autoload');
//spl_autoload_register('model_autoload');
//1 alles includen
//2 Hauptklasse initialisiere
//2.1   eingeloggt?    
//3 aktuellen Controller initialisieren

$witt = new Witt();

?>
