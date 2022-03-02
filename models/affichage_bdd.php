<?php

$db = new PDO('mysql:host=localhost;dbname=mtg_project', 'root', ''); # Création de l'objet représentant la BDD, on passe les identifiants en paramètres
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Définit le mode exception sur cette base de données (utilisation du bloc try/catch)


        try {
            $db = new PDO('mysql:host=localhost;dbname=mtg_project', 'root', ''); # Création de l'objet représentant la BDD, on passe les identifiants en paramètres
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Définit le mode exception sur cette base de données (utilisation du bloc try/catch)

            $selectQuery = "SELECT nom, type, urlImage FROM cartes";
            $results = $db->query($selectQuery);
        } catch (Exception $exception) {
            echo "Erreur lors de la connexion à la base de données mtg_project : " . $exception->getMessage() . "<br>"; # On affiche l'erreur et on arrête l'exécution du code du script
            return null;
        }        
        return $results;
