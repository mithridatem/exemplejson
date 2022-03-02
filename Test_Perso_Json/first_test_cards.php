<?php

$url = 'https://mtgjson.com/api/v5/VOW.json'; # L'URL change à chaque code d'extension    
$pageContent = file_get_contents($url);
$jsonData = json_decode($pageContent);

foreach ($jsonData->data->cards as $card) {

echo "Nom : " . $card->name."</br>";
echo "Coût en Mana : " . $card->manaValue."</br>";
echo "Couleur : " . $card->colors. "</br>";
echo "<img src=\"https://api.scryfall.com/cards/{$card->identifiers->scryfallId}?format=image\" height=\"30%\" width=\"10%\"></img ></br>";

}

$mysql_user = "root";
$mysql_password = "";  
$db = new PDO("mysql:host=localhost;dbname=mtg_project", $mysql_user, $mysql_password); # Création de l'objet représentant la BDD, on passe les identifiants en paramètres

?>