<?php

include_once "../config.php"; // On appelle le model/class Database.
include_once "../models/class_cartes.php"; // On appelle le model/class Cartes.

$cards = new Cartes; //On instancie une variable qui contient tout les paramètres de l'objet "Cartes".

//$cards est une variable qui devient un objet car on lui attribut la classe Cartes. 

$cards ->  set_color('U'); // On définit l'attribut color (par exemple ici: la valeur de l'attribut color est "W").
//$cards -> set_color($_POST["color"]);
$array_color = $cards -> read_color(); // On crée une variable qui va contenir dans un tableau (fetch) toutes les cartes d'une couleur.