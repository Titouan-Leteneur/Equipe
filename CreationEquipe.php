
<!DOCTYPE html>
<?php
//Démarrage de l’environnement de session 
	session_start();
	
	//si l'util est authentifié, 
	if (isset($_SESSION['login']) && isset($_SESSION['mdp']) && $_SESSION['login'] == 'admin' && $_SESSION['mdp'] == '1234'){
		//alors on affiche le nbre de visites
		echo 'Bonjour '.$_SESSION['login'].' !</br>';
        echo    '<form action="PageAuthentification.php" method="post">
					<input type="submit" name="deconnection" value="Se déconnecter"><br/>
				</form>';
		
	//sinon, on le renvoie vers la page d'accueil
	}else{
		header('Location:http://localhost/php/PageAuthentification.php');
	}
	 
?>
<html>
	<head>
		<title>Création équipe</title>
	</head>
	<body>
		<section class="creation_equipe">
		
			<?php
				try
				{
					// On se connecte à MySQL
					$linkpdo = new PDO('mysql:host=localhost;dbname=club', 'root', 'root');
				}
				catch(Exception $e)
				{
					// En cas d'erreur, on affiche un message et on arrête tout
						die('Erreur : '.$e->getMessage());
				}

				// Si tout va bien, on peut continuer

				// Entree des valeurs

				// Nouvelle Equipe
				if(isset($_POST['ajouter_equipe'])){
					echo
						'<form action="CreationEquipe.php" method="post">
							Nom équipe : <input type="text" name="nom_ajouter"><br/>';

					// Sélection des joueurs dans l'équipe

					$requetesql = 'SELECT num_licence, nom, prenom FROM joueur';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$data = $requete->fetchAll();
	
					foreach ($data as $data) {
						
						echo ''.$data['nom'].' '.$data['prenom'].' <input type="checkbox" name="'.$data['num_licence'].'"><br/>';
						
					}

					echo
						'<input type="submit" name="valider" value="Valider"><br/>
						</form>
						<form action="ListeEquipes.php" method="post">
							<input type="submit" value="Annuler"><br/>
						</form>';
				}

				//Modifier une équipe
				if(isset($_POST['modifier_equipe'])){
					echo
						'<form action="CreationEquipe.php" method="post">
							Nom équipe : <input type="text" name="nom_modifier" value="'.$_POST['nom_equipe'].'"><br/>
							<input type="hidden" name="nomdebase" value="'.$_POST['nom_equipe'].'">';

							// Sélection des joueurs dans l'équipe

							$requetesql = 'SELECT num_licence, nom, prenom, id_equipe FROM joueur';
							$requete = $linkpdo->prepare($requetesql);
							$requete->execute();
							$data = $requete->fetchAll();
			
							foreach ($data as $data) {
								
								// Si les joueurs sont déjà dans l'équipe les checkbox sont déjà check

								$requetesql = 'SELECT id_equipe FROM equipe WHERE nom_equipe LIKE "'.$_POST['nom_equipe'].'"';
								$requete = $linkpdo->prepare($requetesql);
								$requete->execute();
								$data2 = $requete->fetchAll();

								foreach($data2 as $data2){
									
									if($data['id_equipe'] == $data2['id_equipe']){

										echo ''.$data['nom'].' '.$data['prenom'].' <input type="checkbox" name="'.$data['num_licence'].'" checked><br/>';
	
									} else {
	
										echo ''.$data['nom'].' '.$data['prenom'].' <input type="checkbox" name="'.$data['num_licence'].'"><br/>';
	
									}
				
								}
			
							}

					echo
						'<input type="submit" name="valider" value="Valider"><br/>
						</form>
						<form action="ListeEquipes.php" method="post">
							<input type="submit" value="Annuler"><br/>
						</form>';
				}
				
				// Execution des requetes

				if(isset($_POST['valider'])){

					// Création d'une nouvelle équipe execution des requetes
					if(isset($_POST['nom_ajouter'])){
						
						// Création d'une équipe
						$nom = $_POST['nom_ajouter'];
						$requetesql = 'INSERT INTO equipe(id_equipe, nom_equipe) VALUES (NULL, :nom_equipe)';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute([
							'nom_equipe' => $nom,
						]);

						
						//Ajout des joueurs dans l'équipe 
						$requetesql = 'SELECT num_licence FROM joueur';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute();
						$data2 = $requete->fetchAll(); //$data2 = num_licence

						foreach($data2 as $data2){
							$requetesql = 'SELECT id_equipe FROM equipe WHERE nom_equipe LIKE "'.$_POST['nom_ajouter'].'"';
							$requete = $linkpdo->prepare($requetesql);
							$requete->execute();
							$data3 = $requete->fetchAll(); //$data3 = id_equipe
						
							foreach($data3 as $data3){
								if(isset($_POST[''.$data2['num_licence'].''])){
									$id = $data3['id_equipe'];
									$requetesql = 'UPDATE joueur SET id_equipe = "'.$data3['id_equipe'].'" WHERE joueur.num_licence LIKE "'.$data2['num_licence'].'"';
									$requete = $linkpdo->prepare($requetesql);
									$requete->execute([
										'id_equipe' => $id,
									]);
								}
							}
						}	

						header('Location:http://localhost/php/ListeEquipes.php');

					}

					// Modification d'une équipe existante execution des requetes 
					if(isset($_POST['nom_modifier'])){
						
						//Modification du nom
						$nom = $_POST['nom_modifier'];
						$requetesql = 'UPDATE equipe SET nom_equipe = "'.$_POST['nom_modifier'].'" WHERE equipe.nom_equipe LIKE "'.$_POST['nomdebase'].'"';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute([
							'nom_equipe' => $nom,
						]);

						// Ajout et suppression des joueurs dans l'équipe
						$requetesql = 'SELECT num_licence, id_equipe FROM joueur';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute();
						$data2 = $requete->fetchAll(); //$data2 = num_licence et id_equipe

						foreach($data2 as $data2){
							$requetesql = 'SELECT id_equipe FROM equipe WHERE nom_equipe LIKE "'.$_POST['nom_modifier'].'"';
							$requete = $linkpdo->prepare($requetesql);
							$requete->execute();
							$data3 = $requete->fetchAll(); //$data3 = id_equipe
						
							foreach($data3 as $data3){
								if(isset($_POST[''.$data2['num_licence'].''])){
									$id = $data3['id_equipe'];
									$requetesql = 'UPDATE joueur SET id_equipe = "'.$data3['id_equipe'].'" WHERE joueur.num_licence LIKE "'.$data2['num_licence'].'"';
									$requete = $linkpdo->prepare($requetesql);
									$requete->execute([
										'id_equipe' => $id,
									]);
								} elseif(!(isset($_POST[''.$data2['num_licence'].''])) && ($data3['id_equipe'] == $data2['id_equipe'])){
									$id = 0;
									$requetesql = 'UPDATE joueur SET id_equipe = null WHERE joueur.num_licence LIKE "'.$data2['num_licence'].'"';
									$requete = $linkpdo->prepare($requetesql);
									$requete->execute([
										'id_equipe' => $id,
									]);
								}
							}
						}

						header('Location:http://localhost/php/ListeEquipes.php');

					}

				}
			?>

		</section>

	</body>
</html>