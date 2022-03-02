<!DOCTYPE html>
<html lang="fr-FR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>MTGJSON Database Script</title>
	</head>
	<body>
		
		<?php
			include 'mtg_json_functions.php'; # Importation du fichier source contenant les fonctions dont on va se servir

			ini_set('memory_limit', '2048M'); # Permet de traiter des fichiers lourds
			$initialTime = new DateTime('now');
			
			# Connexion à la base de données locale avec mySQL et les identifiants utilisateurs configurés au préalable dans phpMyAdmin (onglet Utilisateurs)
			$mysql_user = 'root';
			$mysql_password = 'toor';  
			try {
				$db = new PDO('mysql:host=localhost;dbname=mtg_project', $mysql_user, $mysql_password); # Création de l'objet représentant la BDD, on passe les identifiants en paramètres
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # Définit le mode exception sur cette base de données (utilisation du bloc try/catch)
			} catch (Exception $exception) {
				die("Erreur lors de la connexion à la base de données mtg_project : " . $exception->getMessage()); # On affiche l'erreur et on arrête l'exécution du code du script
			}

			# Récupération des paramètres du formulaire mtg_json_form.html
			$parameters = getParameters();
			$format = $parameters["FORMAT"];
	
			# Récupérer les codes des extensions pour ensuite aller piocher les infos sur chaque URL de l'extension (ex : https://mtgjson.com/api/v5/10E.json)
			$keys = getExtensionsCodesFromFormat($format);
			
			# Récupérer les informations des cartes pour chaque extension dont on a précédemment récupéré les codes
			echo "<b>Récupération des données JSON - Informations des cartes de chaque extension du {$format} ...</b><br><br>";
			$url = ''; # Chaîne de caractères qui représentera l'URL de chaque extension
			$nbExtensionsSaved = 0; # Notre curseur pour savoir combien d'extensions ont déjà été sauvegardés
			$urlImageLastCardsSaved = array(); # Tableau qui contient les url des illustrations de cartes allant être enregistrées			
			
			# Pour chaque code d'extension récupéré dans le tableau $keys
			foreach ($keys as $extensionCode)
			{
				if ($nbExtensionsSaved == $parameters["MAX_EXTENSIONS"]) {
					break; # Si on atteint le max d'extensions qui ont été parcourus, on arrête la boucle
				} 
				else {			
					echo "<b>Sauvegarde de toutes les cartes pour l'extension de code " . $extensionCode . "</b><br><br>";

					$url = 'https://mtgjson.com/api/v5/' . $extensionCode . '.json'; # L'URL change à chaque code d'extension dans la boucle				
					$pageContent = file_get_contents($url);
					$jsonData = json_decode($pageContent);

					# On sauvegarde toutes les cartes de l'extension et on récupère les URLs des images de cette extension
					$urlImageLastCardsSaved = saveAllCardsFromExtension($db, $extensionCode, $jsonData, $parameters["INSERTS_PER_QUERY"]);

					# Si l'utilisateur veut qu'on affiche les cartes, c'est parti pour le show
					if ($parameters["SHOW_CARD_IMAGE"] == true && $urlImageLastCardsSaved != null) 
					{
						foreach ($urlImageLastCardsSaved as $urlImage) {
							echo "<img src=\"https://api.scryfall.com/cards/{$urlImage}?format=image\" alt=\"IMG\" style=\"width: 5%; height: 5%; margin: 1px;\">";										
						}
					}							
				}
				echo "<br><br>";
				$nbExtensionsSaved += 1; # On passe à la prochain extension à parcourir	
			}
			
			# Calcul et affichage du temps total de la procédure
			$finalTime = new DateTime('now');
			$differenceInSeconds = $finalTime->getTimestamp() - $initialTime->getTimestamp();
			$secondsPerExtension = $differenceInSeconds / $parameters["MAX_EXTENSIONS"];
			echo "<b>Temps total de la procédure : </b>" . $differenceInSeconds . " secondes (soit " . round($secondsPerExtension, 3) . " secondes par extension)<br>";
			
		?>

	</body>
</html>