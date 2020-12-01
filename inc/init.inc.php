<?php
// Ouverture de session
session_start();    //1re ligne de code pr tt fichier php : se positionne tjs en haut et en premier avt tt traitement php ! 

// Connexion à la bdd
$pdo = new PDO('mysql:host=localhost;dbname=boutique', 'root', 'root', array(  PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES UTF8" ));

// Définir une constante :
define('URL', 'http://localhost/PHP/boutique/');

// Définition de variables :
$content = '';
$error = '';

// Inclusion des fonctions :
require_once 'fonction.inc.php';