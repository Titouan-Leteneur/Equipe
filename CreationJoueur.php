
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
		<title>PageCréation Joueur</title>
	</head>
	<body>
	<header class="bloc_entete">
			<div class="logo_entete"><a href="PageAccueil.php">
				<img src="Photo/logo.png" alt="Logo">
			</a></div>
			<div class="lienpageListeM"><a href="ListeMatch.php">
				Matchs
			</a></div>	
			<div class="lienpageListeE"><a href="ListeEquipes.php">
				Equipes
			</a></div>
			<div class="lienpageListeJ"><a href="ListeJoueurs.php">
				Joueurs
			</a></div>
        </header>
			<form action="CreationJoueur.php" method="post">
				 Numéro de la licence : <input type="text" name="num_licence"><br/>
				 Nom : <input type="text" name="nom"><br/>
				 Prénom : <input type="text" name="prenom"><br/>
				 Photo : <input type="text" name="lien_photo"><br/>
				 Date de naissance : <input type="date" name="dateN"><br/>
			   Taille : <input type="text" name="taille"><br/>
				 Poste Préféré : <input type="text" name="poste"><br/>
				 Poids : <input type="text" name="poids"><br/>
				 Commentaire : <input type="text" name="commentaire"><br/>
				 Statut : <input type="radio" name="statut" value="Actif" >Actif
				 					<input type="radio" name="statut" value="Suspendu" >Suspendu
									<input type="radio" name="statut" value="Blessé" >Blessé
									<input type="radio" name="statut" value="Absent" >Absent<br/>
				 Equipe :<?php
								try {
									$linkpdo = new PDO("mysql:host=localhost;dbname=club", 'root', 'root');
									$linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								}
								catch(Exception $e){
									die('Erreur : '.$e->getMessage());
								}

									$req=$linkpdo->prepare('SELECT nom_equipe FROM equipe');
									$req->execute();
								while($data = $req -> fetch()){
									 ?> <input type="radio" name="equipe" value = "<?php echo htmlspecialchars($data['nom_equipe']); ?> " > <?php echo $data['nom_equipe'] ?>
								<?php }?>
				<br/> <input type="submit" name="creer" value="Créer le nouveau joueur">
				 <input type="reset" value="Tout effacer">
 		 </form>

			<?php
				if(isset($_POST['creer'])){

				
				//Connexion à la base de données
						try {
							$linkpdo = new PDO("mysql:host=localhost;dbname=club", "root", "root");
						}
						catch(Exception $e){
							die('Erreur : '.$e->getMessage());
						}
							//Connaitre l'id de l'equipe associée au nom
							$req=$linkpdo->prepare("SELECT id_equipe from equipe WHERE nom_equipe = :nom_equipe");
							$req->execute(array('nom_equipe'=> $_POST['equipe']));
							$id;
							while($data = $req -> fetch()){
								 $id = $data['id_equipe'];
							}

							//Changement du format de la date
							$dateJ=date("Y-m-d", strtotime($_POST['dateN']));
							// Création du joueur
							$req1=$linkpdo->prepare("INSERT INTO joueur(num_licence, nom, prenom, photo, date_naissance, taille, poste_prefere, poids, commentaire, statut, id_equipe) VALUES (:num_licence, :nom, :prenom, :photo, :dateN, :taille, :poste, :poids, :comm, :statut, :id_equipe)");
							$req1->execute(array('num_licence' => $_POST['num_licence'], 'nom' => $_POST['nom'], 'prenom' => $_POST['prenom'], 'photo' => $_POST['lien_photo'], 'dateN' => $dateJ, 'taille' => $_POST['taille'], 'poste' => $_POST['poste'],
								'poids' => $_POST['poids'],	'comm' => $_POST['commentaire'], 'statut' => $_POST['statut'], 'id_equipe' => $id));
							echo "Le joueur a bien été créé";
								header('Location:http://localhost/php/ListeJoueurs.php'); 
				}			
			?>
</body>
</html>
