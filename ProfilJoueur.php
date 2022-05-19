

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

echo '

<html>
	<head>
		<title>PageAccueil</title>
	</head>
	<body> ';


			try {
				$linkpdo = new PDO("mysql:host=localhost;dbname=club", 'root', 'root');
				$linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(Exception $e){
				die('Erreur : '.$e->getMessage());
			}

				$req=$linkpdo->prepare('SELECT * FROM joueur WHERE joueur.num_licence= :num_li');
				$req->execute(array('num_li' => $_GET['numeroLicence']));


				while($data = $req -> fetch()){
					echo '

						 <form action="ProfilJoueur.php" method="post">
						 Nom du joueur : <input type="text" name="nomjoueur" value= "' . $data['nom'] . '"><br/>
						 Prénom du joueur : <input type="text" name="prenom_joueur" value="'. $data['prenom'] . '"><br/>
						 Equipe : ';
										try {
											$linkpdo = new PDO("mysql:host=localhost;dbname=club", 'root', 'root');
											$linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										}
										catch(Exception $e){
											die('Erreur : '.$e->getMessage());
										}

											$req3=$linkpdo->prepare('SELECT nom_equipe, id_equipe FROM equipe');
											$req3->execute();
										while($data2 = $req3 -> fetch()){
											if($data['id_equipe'] == $data2['id_equipe']){
												echo '<input type="radio" name="equipe" value = "' .$data2['nom_equipe']. '" checked> '. $data2['nom_equipe'].'' ;
											} else {
												echo '<input type="radio" name="equipe" value = "' .$data2['nom_equipe']. '" > '. $data2['nom_equipe'].'' ;
											}
										} echo '<br/>
						 Photo : <input type="text" name="photo" value="' . $data['photo'] . '"><br/>
						 Date de naissance  : <input type="date" name="date_joueur" value="'.$data['date_naissance'] .'"><br/>
						 Taille  : <input type="text" name="taille_joueur" value="' .$data['taille']. '"><br/>
						 Poids : <input type="text" name="poids_joueur" value="'.$data['poids'] .'" ><br/>
						 Poste préféré : <input type="text" name="poste_joueur" value="'. $data['poste_prefere'] . '"><br/>
						 Commentaire : <input type="text" name="comm_joueur" value="'. $data['commentaire'] .'"><br/>';
						 if($data['statut'] == "Actif"){
						 	echo 'Statut :  <input type="radio" name="statut" value="Actif" checked>Actif
							<input type="radio" name="statut" value="Suspendu" >Suspendu
							<input type="radio" name="statut" value="Blessé" >Blessé
							<input type="radio" name="statut" value="Absent" >Absent<br/>';
						 }
						 if($data['statut'] == "Suspendu"){
							echo 'Statut :  <input type="radio" name="statut" value="Actif" >Actif
						   <input type="radio" name="statut" value="Suspendu" checked>Suspendu
						   <input type="radio" name="statut" value="Blessé" >Blessé
						   <input type="radio" name="statut" value="Absent" >Absent<br/>';
						}
						if($data['statut'] == "Blessé"){
							echo 'Statut :  <input type="radio" name="statut" value="Actif" >Actif
						   <input type="radio" name="statut" value="Suspendu" >Suspendu
						   <input type="radio" name="statut" value="Blessé" checked>Blessé
						   <input type="radio" name="statut" value="Absent" >Absent<br/>';
						}
						if($data['statut'] == "Absent"){
							echo 'Statut :  <input type="radio" name="statut" value="Actif" >Actif
						   <input type="radio" name="statut" value="Suspendu" >Suspendu
						   <input type="radio" name="statut" value="Blessé" >Blessé
						   <input type="radio" name="statut" value="Absent" checked>Absent<br/>';
						}
						 
							echo '<input type="submit" name ="mod_joueur" value="Modifier le joueur">
						 <input type="hidden" name="numerolicence" value="'.$_GET['numeroLicence'].'">
				     </form> ';
		    }

		 if(isset($_POST['mod_joueur'])){

			 try {
				 $linkpdo = new PDO("mysql:host=localhost;dbname=club", 'root', 'root');
				 $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			 }
			 catch(Exception $e){
				 die('Erreur : '.$e->getMessage());
			 }

				$dateJ=date("Y-m-d", strtotime($_POST['date_joueur']));
				$reeq = 'SELECT id_equipe from equipe WHERE nom_equipe LIKE "'.$_POST['equipe'].'"';
				$req1=$linkpdo->prepare($reeq);
				$req1->execute();
				$ide;
				while($data = $req1 -> fetch()){
					$ide= $data['id_equipe'];
				}

			$id = $_POST['numerolicence'];
			$reqq = 'UPDATE joueur SET nom = :nom, prenom = :prenom, photo = :photo, date_naissance = :dateN, taille = :taille, poste_prefere = :poste, poids = :poids, commentaire = :comm, statut = :statut, id_equipe = :id_equipe WHERE joueur.num_licence LIKE "'.$id.'"';
			$mareq = $linkpdo->prepare($reqq);
			$mareq->execute(array('nom' => $_POST['nomjoueur'], 'prenom' => $_POST['prenom_joueur'], 'photo' => $_POST['photo'], 'dateN' => $dateJ, 'taille'=> $_POST['taille_joueur'], 'poste' => $_POST['poste_joueur'],
			'poids' => $_POST['poids_joueur'], 'comm' => $_POST['comm_joueur'], 'statut' => $_POST['statut'], 'id_equipe' => $ide));
			echo "bonjour";
			header('Location:http://localhost/php/ListeJoueurs.php');

		 }


		?>
