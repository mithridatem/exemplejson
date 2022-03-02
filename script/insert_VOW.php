<?php

$db = new PDO('mysql:host=localhost;dbname=mtg_project', 'root', ''); # Création de l'objet représentant la BDD, on passe les identifiants en paramètres
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Définit le mode exception sur cette base de données (utilisation du bloc try/catch)

$pageContent = file_get_contents("https://mtgjson.com/api/v5/VOW.json");
$jsonData = json_decode($pageContent); # Met en forme les données reçues
$keys = array(); # Tableau allant contenir nos codes d'extensions

foreach ($jsonData->data->cards as $card) { # On parcourt tout les codes d'extension récupérés

    $colors = $card->colorIdentity;
    $nom = $card->name;
    $codeExtension = $card->setCode;
    $type = NULL; 
    $rarete = $card->rarity;
    $coutManaText = NULL;
    $coutTotalMana = $card->manaValue;
    $forceCreature = NULL;
    $enduranceCreature = NULL;
    $text = $card->text;
    $urlImage = $card->identifiers->scryfallId;

    if (isset($card->type)){
        //str_replace car problème encodage utf-8 dans bdd
        $type = str_replace("—", "-", $card->type); //str_replace : fonction qui permet de remplacer un caractère ou une string par un autre caractère ou une string
        

        if (isset($card->manaCost)){
            $coutManaText = $card->manaCost;
        }        

        if (isset($card->manaValue) && isset($card->power) && isset($card->toughness) && isset($card->text)){
            // $coutConvertiMana = $card->$manaValue;
            $forceCreature = $card->power;
            $enduranceCreature = $card->toughness;
            $text = $card->text;
        }
    };

    //On parcourt le tableau "colors" (cf. json) qu'on va concatener pour afficher/sauvegarder les couleurs les unes après les autres.
    // Par exemple : tableau{abcd} -> pour chaque case du tableau, on va récupérer chaque index qui seront concaténés et séparé par un "/"
    $str_color = NULL;
    foreach ($colors as $color) {
        $str_color .= $color . "/"; //.= est la concaténation de ce qui va déjà exister dans la variable, ex: tableau a,b,c -> index [0] "/" index [1] "/" etc. 
    }

    $str_color = substr($str_color, 0, -1);
    // "substring" est une fonction qui permet de récupérer une partie de la string que l'on souahite. Ex: exemple = substr(abcd), 0, -2 = ab

    echo "Carte allant etre insérée : " . $nom . " /coutManaText: " . $coutManaText . " " . $str_color . " <br>";

    $sql = 'INSERT INTO cartes (`color`,`nom`,`codeExtension`,`type`,`rarete`,`coutTotalMana`,`coutManaText`,`forceCreature`, `enduranceCreature`, `text`,`urlImage`) VALUES(?,?,?,?,?,?,?,?,?,?,?)';
    $query = $db->prepare($sql);
    $query->execute(array($str_color, $nom, $codeExtension, $type, $rarete, $coutTotalMana,$coutManaText, $forceCreature, $enduranceCreature, $text, $urlImage));
}
?>