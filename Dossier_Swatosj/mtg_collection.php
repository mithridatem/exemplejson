<!DOCTYPE html>
<html lang="fr-FR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Ma Collection v1</title>
		<style type="text/css" media="screen">
			h1 {
				font-size: 48px;
				background: linear-gradient(180deg, rgb(68, 57, 241) 15%, rgb(34, 139, 125) 50%, rgb(11, 233, 148) 85%);
				background-clip: text;
				-webkit-text-fill-color: transparent;
			}
		</style>

        <script>
            function setCreatureFilter() 
            {
                const creatureCards = document.querySelectorAll('.Creature');
                if (document.getElementById('showCreature').checked == true) 
                {
                    creatureCards.forEach(element => {
                        element.style.border='4px solid #E8272C';
                    });
                } 
                else {
                    creatureCards.forEach(element => {
                        element.style.border='none';
                    });
                }   
            }
        </script> 
	</head>

	<body>
		<h1><u>Ma collection v1</u></h1>
        <label for="showCreature">Filtre Créature : </label>
        <input type="checkbox" id="showCreature" onchange=setCreatureFilter()>
        <br><br>

        <?php
            include 'mtg_json_functions.php';
        
            $collection = getMyCollection(250); # On récupère les 250 premières cartes de la collection (table cartes)
            if ($collection == null) {
                echo "<b>Vous ne disposez d'aucune carte dans votre collection locale</b>";
            } else {
                foreach  ($collection as $row) { # On récupère notre résultat dans une boucle et on l'affiche en HTML avec echo
                    $name = $row['nom'];
                    $type = $row['type'];
                    $urlImage = $row['urlImage'];
                    if (strpos($type, "Creature") !== false) {
                        $type = "Creature"; # On simplifie pour éviter d'avoir des classes d'img "Creature - Dryad" (pour l'instant hehe)
                    }
                    # Attention les yeux la ligne magique...
                    echo "<img src=\"https://api.scryfall.com/cards/{$urlImage}?format=image\" class=\"{$type}\" id=\"{$name}\" alt=\"IMG\" style=\"width: 5%; height: 5%; margin: 1px;\">";
                } 
            }

        ?>

	</body>
</html>