<?php
    # Retourne un tableau avec les valeurs des paramètres récupérés ou des paramètres par défaut
    function getParameters() 
    {
        $arrayParameters = array();
        if ( isset($_POST["nbExtensions"]) && isset($_POST["nbInsertPerQuery"]) && isset($_POST["format"]))
        {
            $arrayParameters["MAX_EXTENSIONS"] = $_POST["nbExtensions"]; # On limite le nombre d'extensions à sauvegarder 
            $arrayParameters["INSERTS_PER_QUERY"] = $_POST["nbInsertPerQuery"]; # Définit le nombre d'insertions de cartes par requête SQL
            $arrayParameters["FORMAT"] = $_POST["format"]; # Format sur lequel on va récupérer nos extensions
            if ( isset($_POST["showCardImage"]) ) {
                $arrayParameters["SHOW_CARD_IMAGE"] = true;
            } else {
                $arrayParameters["SHOW_CARD_IMAGE"] = false;
            }
        } else { # Si les paramètres ne sont pas définis, on définis nous même des valeurs par défaut
            echo "<b>Paramètres inexistants ou incorrects - Test avec 5 extensions du format Modern (par défaut)</b><br><br>";
            $arrayParameters["MAX_EXTENSIONS"] = 5; # On limite le nombre d'extensions à sauvegarder pour ne pas prendre trop de temps
            $arrayParameters["INSERTS_PER_QUERY"] = 20; # Définit le nombre d'insertions de cartes par requête SQL
            $arrayParameters["FORMAT"]= "Modern";
            $arrayParameters["SHOW_CARD_IMAGE"] = true;
        }
        return $arrayParameters;
    }

    # Retourne un tableau contenant les codes extensions pour un format donné en paramètre
    function getExtensionsCodesFromFormat($format)
    {
        echo "<b>Récupération des données JSON - Codes des extensions du format {$format} ...</b><br>";
        $pageContent = file_get_contents("https://mtgjson.com/api/v5/{$format}.json"); # On va récupérer les codes extension du format Modern
        $jsonData = json_decode($pageContent); # Met en forme les données reçues
        $keys = array(); # Tableau allant contenir nos codes d'extensions
        foreach ($jsonData->data as $key => $value) { # On parcourt tout les codes d'extension récupérés
            array_push($keys, $key); # On sauvegarde chaque code d'extension dans le tableau $keys
            echo $key . "\n";
        }
        echo "<br><br>";
        return $keys;
    }

    # Retourne une chaîne de caractères représentant les valeurs de la carte sous format SQL
    # Le code correspond juste au fait de transcrire le format de données JSON qu'on traite en format SQL pour notre base locale
    function getSQLStringCardValues($card, $extensionCode)
    {
        # On initialise nos valeurs qui peuvent être NULL (inexistantes) en accord avec la structure de la table
        $fixedPower = "NULL";
        $fixedToughness = "NULL";
        $fixedType = "NULL";
        $fixedText = "NULL";
        $fixedConvertedManaCost = "NULL";
        $fixedManaText = "";
        $fixedIsBaseTerrain = "0";
        $fixedName = str_replace("'", "\'", $card->name);

        if (isset($card->type) && isset($card->manaCost)) # Prends en compte toutes les cartes non terrain
        {                  
            $fixedType = str_replace("—", "-", $card->type);
            $fixedConvertedManaCost = strval($card->convertedManaCost);
            $fixedManaText = $card->manaCost;

            if (isset($card->power) && isset($card->toughness)) { # Cas particulier pour les cartes créatures 
                $fixedPower = str_replace("*", "0", $card->power);
                $fixedToughness = str_replace("*", "0", $card->toughness);
            }
        } 
        else if (isset($card->type)) # Carte terrain
        { 
            $fixedType = str_replace("—", "-", $card->type);
            if (strpos($card->type, "Basic Land") !== false) { # Syntaxe spéciale PHP5 zzZzZzz
                $fixedIsBaseTerrain = "1"; # Booleen qu'on passe à VRAI lorsque c'est un terrain de base
            }
        }     

        if (isset($card->text)) {
            $fixedText = str_replace("'", "\'", $card->text);
            $fixedText = str_replace("—", "-", $fixedText);
        }
        
        # On retourne le résultat de notre super traitement qui respecte la structure de la table cartes 
        return "('{$fixedName}', '{$extensionCode}', '{$fixedType}', {$fixedIsBaseTerrain}, '{$card->rarity}', {$fixedConvertedManaCost},
                '{$fixedManaText}', {$fixedPower}, {$fixedToughness}, '{$fixedText}','{$card->identifiers->scryfallId}')";
    }

    # Sauvegarde en base de données toutes les cartes d'une extension donnée et retourne un tableau contenant les URLs des images de toutes les cartes sauvegardées
    function saveAllCardsFromExtension($db, $extensionCode, $jsonData, $maxInsertPerQuery)
    {
        $sqlInsertCard = 'INSERT INTO `cartes`(`nom`, `codeExtension`, `type`, `estTerrainBase`, `rarete`, `coutConvertiMana`, `coutManaTexte`, `forceCreature`, `enduranceCreature`, `texte`, `urlImage`) VALUES ';
        $nbCardsSaved = 0; # Notre curseur pour savoir combien de cartes ont été traités par la boucle qui suit
        $urlImageLastCardsSaved = array();

        # On parcourt toutes les cartes disponibles dans la base de données distantes
        foreach ($jsonData->data->cards as $card) 
        {
            array_push($urlImageLastCardsSaved, $card->identifiers->scryfallId); # Sauvegarde de l'url d'image de la carte courante
            try {       
                if ($nbCardsSaved == 0) {
                    $db->beginTransaction(); # On prépare la base de données à insérer des données
                }         

                # On concatène la base de notre requêtes SQL (structure de table) avec la chaîne de caractères des valeurs de la carte
                $finalQuery = $sqlInsertCard . getSQLStringCardValues($card, $extensionCode);
                $db->exec($finalQuery); # On execute l'insertion de la carte courante de la boucle
                $nbCardsSaved +=1; # Allez hop +1 dans la base

                if ($nbCardsSaved == $maxInsertPerQuery) { # Si on atteint le max d'insertions par requête SQL défini dans le formulaire					
                    $db->commit(); # Enregistre la requête dans la base de données
                    $nbCardsSaved = 0;
                }
            } catch (Exception $ex) {
                echo "<br><b style=\"color: red;\">Une erreur est survenue lors de la dernière requête SQL de type INSERT : </b>" . $ex->getMessage(). "<br>";
                return null; # On arrete la fonction et on retourne null pour signaler qu'il n'y a plus de tableau d'images qui tienne !
            }
        }
        $db->commit(); # Enregistre les dernières cartes restantes dans le buffer
        return $urlImageLastCardsSaved;
    }

    # Retourne un tableau avec données de la base locale nécessaires à l'affichage des cartes 
    function getMyCollection($nbLimitCard)
    {
        # Connexion à la base de données locale avec mySQL et les identifiants utilisateurs configurés au préalable dans phpMyAdmin (onglet Utilisateurs)
        $mysql_user = 'root';
        $mysql_password = 'toor';  
        $results = null;
        try {
            $db = new PDO('mysql:host=localhost;dbname=mtg_project', $mysql_user, $mysql_password); # Création de l'objet représentant la BDD, on passe les identifiants en paramètres
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Définit le mode exception sur cette base de données (utilisation du bloc try/catch)

            $selectQuery = "SELECT nom, type, urlImage FROM cartes LIMIT {$nbLimitCard}";
            $results = $db->query($selectQuery);
        } catch (Exception $exception) {
            echo "Erreur lors de la connexion à la base de données mtg_project : " . $exception->getMessage() . "<br>"; # On affiche l'erreur et on arrête l'exécution du code du script
            return null;
        }        
        return $results;
    }

?>