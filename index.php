<?php
require_once('includes/config.php');
require_once('includes/chargementClasses.inc.php');

// Après le script de chargement des classes
// pour pouvoir recharger l'objet de la classe Utilisateur
// stocké dans $_SESSION si l'utilisateur est connecté
session_start(); 

new Routeur;