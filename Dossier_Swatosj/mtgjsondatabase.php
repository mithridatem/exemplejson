<!DOCTYPE html>
<html lang="fr-FR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>MTGJSON Database v0.1 for Polo project</title>
	</head>
	<body>
		<h1><u>MTGJSON Database v0.1</u></h1>
		<h3><u>Fonctionnalités</u></h3>
		<ul> 
			<li>Récupère les données des cartes de créatures dans la base de données JSON d'un format d'extension spécifié dans le code (Modern, Legacy...)</li>
			<li>Enregistre les informations des cartes dans la base de données locale</li>
			<li>Le nombre d'extensions parcourues et le nombre de cartes traités par chaque INSERT sont configurables dans le code</li>
			<li>Affichage du temps de procédure et moyenne par extension</li>
		</ul>
		
		<?php
			ini_set('memory_limit', '2048M'); # Permet de traiter des fichiers lourds
			$initialTime = new DateTime('now');
			
			# Connexion à la base de données 
			$mysql_user = "root";
			$mysql_password = "toor";  
			$db = new PDO("mysql:host=localhost;dbname=mtg_project", $mysql_user, $mysql_password); # Création de l'objet représentant la BDD, on passe les identifiants en paramètres
			
			# Récupérer les codes des extensions pour ensuite aller piocher les infos sur chaque URL de l'extension (ex : https://mtgjson.com/api/v5/10E.json)
			echo "<b>Récupération des données JSON - Codes des extensions du format Modern ...</b><br>";
			$pageContent = file_get_contents('https://mtgjson.com/api/v5/Modern.json'); # On va récupérer les codes extension du format Modern
			$jsonData = json_decode($pageContent); # Met en forme les données reçues
			$keys = array(); # Tableau allant contenir nos codes d'extensions
			foreach ($jsonData->data as $key => $value) {
				array_push($keys, $key);	
				echo $key . "\n";
			}
			echo "<br><br>";
			
			# Récupérer les informations des cartes pour chaque extension dont on a précédemment récupéré les codes
			echo "<b>Récupération des données JSON - Informations des cartes de chaque extension du Modern ...</b><br>";
			$url = ''; # Chaîne de caractères qui représentera l'URL de chaque extension
			$maxExtensionsToSave = 5; # On limite le nombre d'extensions à sauvegarder pour ne pas prendre trop de temps
			$nbExtensionsSaved = 0; # Notre curseur pour savoir combien d'extensions ont déjà été sauvegardés
			$maxCardsPerInsert = 20; # Définit le nombre d'insertions de cartes par requête SQL			
			$sqlInsertQuery = 'INSERT INTO `cartes`(`nom`, `codeExtension`, `type`, `rarete`, `coutConvertiMana`, `coutManaTexte`, `forceCreature`, `enduranceCreature`, `texte`, `urlImage`) VALUES ';
			
			foreach ($keys as $extensionCode)
			{
				if ($nbExtensionsSaved == $maxExtensionsToSave) {
					break; # Si on atteint le max d'extensions qui ont été parcourus, on arrête la boucle
				} 
				else {			
					echo "<b>Sauvegarde de toutes les cartes pour l'extension de code " . $extensionCode . "</b><br>";
					$sqlInsertValues = '';
					$nbCardsSaved = 0;
					$url = 'https://mtgjson.com/api/v5/' . $extensionCode . '.json'; # L'URL change à chaque code d'extension dans la boucle				
					$pageContent = file_get_contents($url);
					$jsonData = json_decode($pageContent);
					foreach ($jsonData->data->cards as $card) 
					{
						if ( isset($card->manaCost) && isset($card->power) && isset($card->toughness) && isset($card->text) ) # TODO Prendre en compte les cartes non créatures et les terrains
						{
							# Mise en forme des données pour pouvoir ensuite les insérer
							$fixedName = str_replace("'", "\'", $card->name);
							$fixedText = str_replace("'", "\'", $card->text);
							$fixedType = str_replace("—", "-", $card->type);
							$fixedPower = str_replace("*", "0", $card->power);
							$fixedToughness = str_replace("*", "0", $card->toughness);
							$sqlInsertValues .= "('{$fixedName}','{$extensionCode}','{$fixedType}','{$card->rarity}'," . strval($card->convertedManaCost) . ",'{$card->manaCost}'," . strval($fixedPower) . "," . strval($fixedToughness) . ",'{$fixedText}','{$card->identifiers->scryfallId}')";
							
							if ($nbCardsSaved == $maxCardsPerInsert) 
							{
								$finalInsertQuery = $sqlInsertQuery . str_replace(",,,", ",", $sqlInsertValues); # Concatène le début de la requête SQL avec les valeurs enregistrés
								echo "Requête allant etre éxécutée : " . $finalInsertQuery . "<br>";
								$insertSuccess = $db->prepare($finalInsertQuery)->execute(); # On execute la requête SQL sur l'objet base de données $db						
								if ($insertSuccess == true) {
									echo "<b>La dernière insertion est un succès.</b><br><br>";
								} else {
									echo "<b>La dernière insertion est un échec.</b><br><br>";									
								}
								$sqlInsertValues = '';
								$nbCardsSaved = 0;
							} else {
								$sqlInsertValues .= ',';
								$nbCardsSaved += 1;
							}		
							# echo "<img src=\"https://api.scryfall.com/cards/{$card->identifiers->scryfallId}?format=image\" alt=\"IMG\" style=\"width: 2%; height: 2%; margin: 1px;\">";
						}		
					}
					if ($sqlInsertValues != '') # Si les dernières valeurs restantes n'ont pas été prises en compte, on les ajoute également
					{ 
						$finalInsertQuery = $sqlInsertQuery . str_replace(",,,", ",", substr($sqlInsertValues, 0, -1)); # Concatène le début de la requête SQL avec les valeurs enregistrés
						echo "Requête allant etre éxécutée : " . $finalInsertQuery . "<br>";
						$insertSuccess = $db->prepare($finalInsertQuery)->execute(); # On execute la requête SQL sur l'objet base de données $db	
						if ($insertSuccess == true) {
							echo "<b>La dernière insertion est un succès.</b><br><br>";
						} else {
							echo "<b>La dernière insertion est un échec.</b><br><br>";									
						}						
					}
					echo "<br><br>";
					$nbExtensionsSaved += 1;
				}	
			}
			
			# Calcul et affichage du temps total de la procédure
			$finalTime = new DateTime('now');
			$differenceInSeconds = $finalTime->getTimestamp() - $initialTime->getTimestamp();
			$secondsPerExtension = $differenceInSeconds / $maxExtensionsToSave;
			echo "<b>Temps total de la procédure : </b>" . $differenceInSeconds . " secondes (soit " . round($secondsPerExtension, 3) . " secondes par extension)<br><br>";
			
			# Code pour afficher les données présentes dans la table 'cartes'
			/* $query = "SELECT * FROM cartes"; # Requête SQL à éxécuter
			echo "<b>Récupération des données de la base locale...</b><br>";
			foreach  ($db->query($query) as $row) { # On récupère notre résultat dans une boucle et on l'affiche en HTML avec echo
				echo "<b>Nom</b> : " . $row['nom'] . " / ";
				echo "<b>Type</b> : " . $row['type'] . " / ";				
				echo "<b>Rarete</b> : " .$row['rarete'] . " / ";
				echo "<b>Extension</b> : " . $row['codeExtension'] . " / ";
				echo "<b>Cout mana</b> : " . $row['coutConvertiMana'] . " / ";
				echo "<b>Force</b> : " . $row['forceCreature'] . " / ";
				echo "<b>Endurance</b> : " . $row['enduranceCreature'] . " / ";
				echo "<b>Texte</b> : " . $row['texte'] . "<br>";
			} 
			*/
		?>

	</body>
</html>