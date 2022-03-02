<?php
    header("Access-Control-Allow-Origin: *"); // C'est la commande qui va nous permettre de rendre accessible le json
    //json error
    $tab = array( //On crée simplement un tableau d'un json d'erreur
        'error' => 'Pas de Json',);
    //test si le paramétre task existe

    
    //test si l'url contient le paramètre 'ALL'
if (isset($_GET['ALL'])) { // $_GET : c'est la super globale qui permet d'aller lire (api_cards.php?ALL) ; le isset est en relation avec le $_GET
    //afficher et encoder un json le résultat de la méthode reqAllCards() 
    echo json_encode(reqAllCards()); //on va echo le tableau json de la table 'cartes'
}


else if (isset($_GET['red'])) { // $_GET : c'est la super globale qui permet d'aller lire (api_cards.php?ALL) ; le isset est en relation avec le $_GET
    //afficher et encoder un json le résultat de la méthode reqAllCards() 
    echo json_encode(reqRedCards()); //on va echo le tableau json de la table 'cartes'
}
else if (isset($_GET['white'])) { // $_GET : c'est la super globale qui permet d'aller lire (api_cards.php?ALL) ; le isset est en relation avec le $_GET
    //afficher et encoder un json le résultat de la méthode reqAllCards() 
    echo json_encode(reqWhiteCards()); //on va echo le tableau json de la table 'cartes'
}
else{
    
    print_r('{"error" : "'.$tab['error'].'"}');
    
}

//fonction qui retourne le contenu de la table "cartes" dans un tableau
function reqAllCards()
    {        
        try //try permet d'éxécuter tant qu'il n'y pas d'erreur dans la fonction
        {   
            //connexion à la Base de données mtg_project
            include('../utils/connexionBdd.php');
            //requete SQL
            $requete = "SELECT * FROM cartes"; // on va stocker dans la variable requete la requête sql
            // Execution de la requéte SQL.
            $reponse = $bdd->query($requete); // dans la variable reponse on va stocker l'éxécution de la requête sql
            //variable $output (Arraylist) contenant le résultat de la requéte
            $output = $reponse->fetchAll(PDO::FETCH_ASSOC); // dans la variable output on va stocker dans un tableau toutes les résulats de la requête
        }
        catch (Exception $e) // catch: arrête le script et affiche les erreurs
        {
            die('Erreur : ' . $e->getMessage());
        }
        //retourne une Arraylist
        return $output; //on retourne le tableau (contenu de la table 'cartes' de la bdd)
    }

    function reqWhiteCards()
    {        
        try //try permet d'éxécuter tant qu'il n'y pas d'erreur dans la fonction
        {   
            //connexion à la Base de données mtg_project
            include('../utils/connexionBdd.php');
            //requete SQL
            $requete = "SELECT * FROM cartes WHERE color='W'"; // on va stocker dans la variable requete la requête sql
            // Execution de la requéte SQL.
            $reponse = $bdd->query($requete); // dans la variable reponse on va stocker l'éxécution de la requête sql
            //variable $output (Arraylist) contenant le résultat de la requéte
            $output = $reponse->fetchAll(PDO::FETCH_ASSOC); // dans la variable output on va stocker dans un tableau toutes les résulats de la requête
        }
        catch (Exception $e) // catch: arrête le script et affiche les erreurs
        {
            die('Erreur : ' . $e->getMessage());
        }
        //retourne une Arraylist
        return $output; //on retourne le tableau (contenu de la table 'cartes' de la bdd)
    }

    function reqRedCards()
    {        
        try //try permet d'éxécuter tant qu'il n'y pas d'erreur dans la fonction
        {   
            //connexion à la Base de données mtg_project
            include('../utils/connexionBdd.php');
            //requete SQL
            $requete = "SELECT * FROM cartes WHERE color='R'"; // on va stocker dans la variable requete la requête sql
            // Execution de la requéte SQL.
            $reponse = $bdd->query($requete); // dans la variable reponse on va stocker l'éxécution de la requête sql
            //variable $output (Arraylist) contenant le résultat de la requéte
            $output = $reponse->fetchAll(PDO::FETCH_ASSOC); // dans la variable output on va stocker dans un tableau toutes les résulats de la requête
        }
        catch (Exception $e) // catch: arrête le script et affiche les erreurs
        {
            die('Erreur : ' . $e->getMessage());
        }
        //retourne une Arraylist
        return $output; //on retourne le tableau (contenu de la table 'cartes' de la bdd)
    }

?>