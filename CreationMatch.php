
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
		<title>Création Match</title>
	</head>
	<body>
	
		<section class="creation_match">

		<?php
			try
			{
				// On se connecte à MySQL
				$linkpdo = new PDO('mysql:host=localhost;dbname=club;charset=utf8', 'root', 'root');
			}
			catch(Exception $e)
			{
				// En cas d'erreur, on affiche un message et on arrête tout
				die('Erreur : '.$e->getMessage());
			}

			// Si tout va bien, on peut continuer



			// Nouveau match
			if(isset($_POST["ajouter_match"])){
				echo
					'<form action="CreationMatch.php" method="post">
						Choix équipe : <select name="equipe_ajouter">';

				// Récupération des nom d'équipes

				$requetesql = 'SELECT * FROM equipe';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();

				foreach ($data as $data) {
					echo'<option value="'.$data["id_equipe"].'">'.$data["nom_equipe"].'</option>';
				}
				echo'</select><br/>';

				echo
					'
					Nom équipe adverse : <input type="text" name="equipe_adverse_ajouter"><br/>
					Lieu : <select name="lieu_ajouter">
							<option value="domicile">domicile</option>
							<option value="exterieur">exterieur</option> </select><br/>
					Heure : <input type="time" name="heure_ajouter"><br/>
					Date : <input type="date" name="date_ajouter"><br/>
					Nombre points equipe : <input type="text" name="points_ajouter"><br/>
					Nombre points equipe adverse : <input type="text" name="points_adverse_ajouter"><br/>
					';

				// Liste des participants

				echo '
					<input type="submit" name="valider_ajouter" value="Valider"><br/>
					</form>
					<form action="ListeMatch.php" method="post">
						<input type="submit" value="Annuler"><br/>
					</form>';
			}
			

			// Execution des requêtes nouveau match valider
			if(isset($_POST["valider_ajouter"])){

				// Création d'une nouveau match execution des requetes
				if( !empty($_POST['equipe_ajouter']) && !empty($_POST['equipe_adverse_ajouter']) && !empty($_POST['lieu_ajouter']) && !empty($_POST['date_ajouter']) ){

					// Création d'un match

					// On test si les valeur qui ne sont pas obligées d'être rentrées sont vide et si c'est le cas on les met a NULL
					if(empty($_POST['heure_ajouter'])) {
						$heure = NULL;
					} else {
						$heure = $_POST['heure_ajouter'];
					}
					if(empty($_POST['points_adverse_ajouter'])) {
						$nombre_points_equipe_adverse = NULL;
					} else {
						$nombre_points_equipe_adverse = $_POST['points_adverse_ajouter'];
					}
					if(empty($_POST['points_ajouter'])) {
						$nombre_points_equipe = NULL;
					} else {
						$nombre_points_equipe = $_POST['points_ajouter'];
					}

					$nom_equipe_adverse = $_POST['equipe_adverse_ajouter'];
					$lieu_match = $_POST['lieu_ajouter'];
					$date_match = date("Y-m-d", strtotime($_POST['date_ajouter']));
					$id_equipe = $_POST['equipe_ajouter'];

					$requetesql = 'INSERT INTO matchh (id_match, heure, nom_equipe_adverse, nombre_points_equipe_adverse, nombre_points_equipe, lieu_match, date_match, id_equipe) VALUES (NULL, :heure, :nom_equipe_adverse, :nombre_points_equipe_adverse, :nombre_points_equipe, :lieu_match, :date_match, :id_equipe)';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute(array(
						'heure'=> $heure,
						'nom_equipe_adverse'=> $nom_equipe_adverse,
						'nombre_points_equipe_adverse'=> $nombre_points_equipe_adverse,
						'nombre_points_equipe'=> $nombre_points_equipe, 
						'lieu_match'=> $lieu_match, 
						'date_match'=> $date_match, 
						'id_equipe'=> $id_equipe,
					));
					header('Location:http://localhost/php/ListeMatch.php');
				}	

			}



			// Modification d'un match existant
			if(isset($_POST["modifier_match"])){
				echo
				'<form action="CreationMatch.php" method="post">
					Choix équipe : <select name="equipe_ajouter">';

				// Récupération des nom d'équipes et préselection de l'équipe à modifier

				$requetesql = 'SELECT * FROM equipe';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();

				foreach ($data as $data) {
					$requetesql = 'SELECT id_equipe FROM matchh WHERE id_match LIKE "'.$_POST["id_match"].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$data2 = $requete->fetchAll();

					foreach ($data2 as $data2) {
						if($data["id_equipe"] == $data2["id_equipe"]){
							echo'<option value="'.$data["id_equipe"].'" selected>'.$data["nom_equipe"].'</option>';
						}else{
							echo'<option value="'.$data["id_equipe"].'">'.$data["nom_equipe"].'</option>';
						}
					}
				}
				echo'</select><br/>';

				$requetesql = 'SELECT * FROM matchh WHERE id_match LIKE "'.$_POST["id_match"].'"';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data2 = $requete->fetchAll();

				foreach ($data2 as $data2) {
					echo
						'
						Nom équipe adverse : <input type="text" name="equipe_adverse_ajouter" value="'.$data2["nom_equipe_adverse"].'"><br/>';
					if ($data2["lieu_match"] == "domicile"){	
						echo 'Lieu : <select name="lieu_ajouter">
								<option value="domicile" selected>domicile</option>
								<option value="exterieur">exterieur</option> </select><br/>';
					} else {
						echo 'Lieu : <select name="lieu_ajouter">
								<option value="domicile">domicile</option>
								<option value="exterieur" selected>exterieur</option> </select><br/>';
					}
					
					echo 
						'Heure : <input type="time" name="heure_ajouter" value="'.$data2["heure"].'"><br/>
						Date : <input type="date" name="date_ajouter" value="'.$data2["date_match"].'"><br/>
						Nombre points equipe : <input type="text" name="points_ajouter" value="'.$data2["nombre_points_equipe"].'"><br/>
						Nombre points equipe adverse : <input type="text" name="points_adverse_ajouter" value="'.$data2["nombre_points_equipe_adverse"].'"><br/>
						';
					// Liste des participants

					echo 'Liste des participants : <br/>';
					$requetesql = 'SELECT * FROM matchh, participer, joueur WHERE matchh.id_match LIKE "'.$_POST['id_match'].'" AND participer.id_match = matchh.id_match AND participer.num_licence = joueur.num_licence';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$data = $requete->fetchAll();
					foreach ($data as $data) {
						echo ''.$data["nom"].' '.$data["prenom"].' Rôle : '.$data["role_joueur"].' Performance : '.$data["performance"].'<br/>'; 
					}
					echo'<input type="submit" name="modifier_liste_joueurs" value="Modifier liste des joueurs"><br/>
						<input type="submit" name="valider_modifier" value="Valider">
						<input type="hidden" name="id_match" value="'.$_POST["id_match"].'"><br/>
						</form>
						<form action="ListeMatch.php" method="post">
							<input type="submit" value="Annuler"><br/>
						</form>';
			
				}
			}



			// Execution des requêtes modifier match
			if(isset($_POST["valider_modifier"])){

				// Modifier match execution des requetes
				if( !empty($_POST['equipe_ajouter']) && !empty($_POST['equipe_adverse_ajouter']) && !empty($_POST['lieu_ajouter']) && !empty($_POST['date_ajouter']) ){
					
					// Modification du match

					// On test si les valeur qui ne sont pas obligées d'être rentrées sont vide et si c'est le cas on les met a NULL
					if(empty($_POST['heure_ajouter'])) {
						$heure = NULL;
					} else {
						$heure = $_POST['heure_ajouter'];
					}
					if(empty($_POST['points_adverse_ajouter'])) {
						$nombre_points_equipe_adverse = NULL;
					} else {
						$nombre_points_equipe_adverse = $_POST['points_adverse_ajouter'];
					}
					if(empty($_POST['points_ajouter'])) {
						$nombre_points_equipe = NULL;
					} else {
						$nombre_points_equipe = $_POST['points_ajouter'];
					}

					$nom_equipe_adverse = $_POST['equipe_adverse_ajouter'];
					$lieu_match = $_POST['lieu_ajouter'];
					$date_match = date("Y-m-d", strtotime($_POST['date_ajouter']));
					$id_equipe = $_POST['equipe_ajouter'];

					$requetesql = 'DELETE FROM participer WHERE id_match LIKE "'.$_POST["id_match"].'" AND num_licence NOT IN(SELECT num_licence FROM joueur WHERE id_equipe LIKE "'.$id_equipe.'")';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();

					$requetesql = 'UPDATE matchh SET heure = :heure,
					nom_equipe_adverse = :nom_equipe_adverse,
					nombre_points_equipe_adverse = :nombre_points_equipe_adverse,
					nombre_points_equipe = :nombre_points_equipe, 
					lieu_match = :lieu_match, 
					date_match = :date_match, 
					id_equipe = :id_equipe WHERE id_match LIKE "'.$_POST["id_match"].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute([
						'heure'=> $heure,
						'nom_equipe_adverse'=> $nom_equipe_adverse,
						'nombre_points_equipe_adverse'=> $nombre_points_equipe_adverse,
						'nombre_points_equipe'=> $nombre_points_equipe, 
						'lieu_match'=> $lieu_match, 
						'date_match'=> $date_match, 
						'id_equipe'=> $id_equipe,
					]);

					header('Location:http://localhost/php/ListeMatch.php');
				}
			}

			

			// Execution des requêtes modifier liste joueurs
			if(isset($_POST["modifier_liste_joueurs"])){

				// Modifier match execution des requetes
				if( !empty($_POST['equipe_ajouter']) && !empty($_POST['equipe_adverse_ajouter']) && !empty($_POST['lieu_ajouter']) && !empty($_POST['date_ajouter']) ){

					// Modification du match

					// On test si les valeur qui ne sont pas obligées d'être rentrées sont vide et si c'est le cas on les met a NULL
					if(empty($_POST['heure_ajouter'])) {
						$heure = NULL;
					} else {
						$heure = $_POST['heure_ajouter'];
					}
					if(empty($_POST['points_adverse_ajouter'])) {
						$nombre_points_equipe_adverse = NULL;
					} else {
						$nombre_points_equipe_adverse = $_POST['points_adverse_ajouter'];
					}
					if(empty($_POST['points_ajouter'])) {
						$nombre_points_equipe = NULL;
					} else {
						$nombre_points_equipe = $_POST['points_ajouter'];
					}

					$nom_equipe_adverse = $_POST['equipe_adverse_ajouter'];
					$lieu_match = $_POST['lieu_ajouter'];
					$date_match = date("Y-m-d", strtotime($_POST['date_ajouter']));
					$id_equipe = $_POST['equipe_ajouter'];

					$requetesql = 'DELETE FROM participer WHERE id_match LIKE "'.$_POST["id_match"].'" AND num_licence NOT IN(SELECT num_licence FROM joueur WHERE id_equipe LIKE "'.$id_equipe.'")';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();

					$requetesql = 'UPDATE matchh SET heure = :heure,
					nom_equipe_adverse = :nom_equipe_adverse,
					nombre_points_equipe_adverse = :nombre_points_equipe_adverse,
					nombre_points_equipe = :nombre_points_equipe, 
					lieu_match = :lieu_match, 
					date_match = :date_match, 
					id_equipe = :id_equipe WHERE id_match LIKE "'.$_POST["id_match"].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute([
						'heure'=> $heure,
						'nom_equipe_adverse'=> $nom_equipe_adverse,
						'nombre_points_equipe_adverse'=> $nombre_points_equipe_adverse,
						'nombre_points_equipe'=> $nombre_points_equipe, 
						'lieu_match'=> $lieu_match, 
						'date_match'=> $date_match, 
						'id_equipe'=> $id_equipe,
					]);


					// Liste des participants

					echo 'Liste des participants : <br/>
					<form action="CreationMatch.php" method="post">';
					$requetesql = 'SELECT * FROM joueur WHERE id_equipe LIKE "'.$id_equipe.'" AND statut LIKE "Actif"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$data = $requete->fetchAll();
					foreach ($data as $data) {
						$requetesql = 'SELECT * FROM participer WHERE num_licence LIKE "'.$data['num_licence'].'" AND id_match LIKE "'.$_POST["id_match"].'"';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute();
						$data2 = $requete->fetchAll();
						if(empty($data2)){
							echo ''.$data['nom'].' '.$data['prenom'].' <input type="checkbox" name="'.$data['num_licence'].'">
							Rôle : <select name="role_joueur'.$data['num_licence'].'"> <option value="Titulaire">Titulaire</option><option value="Remplacant">Remplacant</option></select>
							Performance (1 - 5) : <input type="number" name="performance'.$data['num_licence'].'" min="1" max="5">';
							echo ' Photo : <img src="'.$data['photo'].'"/> Taille : '.$data['taille'].'cm Poids : '.$data['poids'].'kg Poste préféré : '.$data['poste_prefere'].' Commentaire : '.$data['commentaire'].'<br/>';
						}
						foreach ($data2 as $data2) {

								echo ''.$data['nom'].' '.$data['prenom'].' <input type="checkbox" name="'.$data['num_licence'].'" checked>
								Rôle : ';
								if($data2['role_joueur'] == "Titulaire"){
									echo '<select name="role_joueur'.$data['num_licence'].'"> <option value="Titulaire" selected>Titulaire</option><option value="Remplacant">Remplacant</option></select>';
								} else {
									echo '<select name="role_joueur'.$data['num_licence'].'"> <option value="Titulaire">Titulaire</option><option value="Remplacant" selected>Remplacant</option></select>';
								}

								echo 'Performance (1 - 5) : <input type="number" name="performance'.$data['num_licence'].'" min="1" max="5" value="'.$data2['performance'].'">';
								echo ' Photo : <img src="'.$data['photo'].'"/> Taille : '.$data['taille'].'cm Poids : '.$data['poids'].'kg Poste préféré : '.$data['poste_prefere'].' Commentaire : '.$data['commentaire'].'<br/>';

						}

					}
					
					echo'
					<input type="submit" name="valider_liste_joueurs" value="Valider"><br/>
					<input type="hidden" name="id_match" value="'.$_POST["id_match"].'">
					<input type="hidden" name="id_equipe" value="'.$id_equipe.'">
					</form>
					<form action="ListeMatch.php" method="post">
						<input type="submit" value="Annuler"><br/>
					</form>';
					
					
				}	

			}



			// Traitement des requêtes : valider liste des joueurs

			if(isset($_POST["valider_liste_joueurs"])){

				$nombre_de_joueurs = 0;
				$requetesql = 'SELECT * FROM joueur WHERE id_equipe LIKE "'.$_POST["id_equipe"].'"';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();
				foreach ($data as $data) {
					if(isset($_POST[''.$data['num_licence'].''])){
						$nombre_de_joueurs ++ ;
					}
				}

				if($nombre_de_joueurs >= 5){

					$requetesql = 'SELECT * FROM joueur WHERE id_equipe LIKE "'.$_POST["id_equipe"].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$data = $requete->fetchAll();
					foreach ($data as $data) {
						if(isset($_POST[''.$data['num_licence'].''])){
							// On vérifie si le joueur participe déja au match
							$requetesql = 'SELECT * FROM participer WHERE id_match LIKE "'.$_POST["id_match"].'" AND num_licence LIKE "'.$data['num_licence'].'"';
							$requete = $linkpdo->prepare($requetesql);
							$requete->execute();
							$data2 = $requete->fetchAll();
							
							foreach ($data2 as $data2) {
								if(!($data2['num_licence'] == $data['num_licence'])){
									// On ajoute le joueur au match dans participer
									if(empty($_POST['role_joueur'.$data['num_licence'].''])){
										$role_joueur = NULL;
									} else {
										$role_joueur = $_POST['role_joueur'.$data['num_licence'].''];
									}
									if(empty($_POST['performance'.$data['num_licence'].''])){
										$performance = NULL;
									} else {
										$performance = $_POST['performance'.$data['num_licence'].''];
									}
									$id_match = $_POST["id_match"];
									$num_licence = $data['num_licence'];
									$requetesql = 'INSERT INTO participer (performance, role_joueur, id_match, num_licence) VALUES (:performance, :role_joueur, :id_match, :num_licence)';
									$requete = $linkpdo->prepare($requetesql);
									$requete->execute(array(
										'performance' => $performance,
										'role_joueur' => $role_joueur,
										'id_match'=> $id_match,
										'num_licence'=> $num_licence,
									));
								} else {
									if(empty($_POST['role_joueur'.$data['num_licence'].''])){
										$role_joueur = NULL;
									} else {
										$role_joueur = $_POST['role_joueur'.$data['num_licence'].''];
									}
									if(empty($_POST['performance'.$data['num_licence'].''])){
										$performance = 0;
									} else {
										$performance = $_POST['performance'.$data['num_licence'].''];
									}
									$requetesql = 'UPDATE participer 
									SET performance = "'.$performance.'",
									role_joueur = "'.$role_joueur.'"
									WHERE id_match LIKE "'.$_POST["id_match"].'" AND num_licence LIKE "'.$data['num_licence'].'"';
									$requete = $linkpdo->prepare($requetesql);
									$requete->execute([
										'performance' => $performance,
										'role_joueur' => $role_joueur,
									]);
								}
							}
							if(empty($data2)){
								// On ajoute le joueur au match dans participer
								if(empty($_POST['role_joueur'.$data['num_licence'].''])){
									$role_joueur = NULL;
								} else {
									$role_joueur = $_POST['role_joueur'.$data['num_licence'].''];
								}
								if(empty($_POST['performance'.$data['num_licence'].''])){
									$performance = NULL;
								} else {
									$performance = $_POST['performance'.$data['num_licence'].''];
								}
								$id_match = $_POST["id_match"];
								$num_licence = $data['num_licence'];
								$requetesql = 'INSERT INTO participer (performance, role_joueur, id_match, num_licence) VALUES (:performance, :role_joueur, :id_match, :num_licence)';
								$requete = $linkpdo->prepare($requetesql);
								$requete->execute(array(
									'performance' => $performance,
									'role_joueur' => $role_joueur,
									'id_match'=> $id_match,
									'num_licence'=> $num_licence,
								));
							}
						} else {
							$requetesql = 'DELETE FROM participer WHERE id_match LIKE "'.$_POST["id_match"].'" AND num_licence LIKE "'.$data['num_licence'].'"';
							$requete = $linkpdo->prepare($requetesql);
							$requete->execute();
						}
					}
					echo '<form action="CreationMatch.php" method="post">
					Les modifiaction ont bien été efféctuées <br/>
					<input type="submit" name="modifier_match" value="Retour"><br/>
					<input type="hidden" name="id_match" value="'.$_POST["id_match"].'">
					
					</form>';
				} else {
					echo '<form action="CreationMatch.php" method="post">
						Le nombre de joueurs séléctionnés est insuffisant (minimum 5) <br/>
						<input type="submit" name="modifier_match" value="Retour"><br/>
						<input type="hidden" name="id_match" value="'.$_POST["id_match"].'">
						
						</form>';
				}
			}

		?>

		</section>

	</body>
</html>