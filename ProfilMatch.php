 

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
		<title>Profil match</title>
	</head>
	<body>
		<nav>
        <ul>
          <li><a href="PageAccueil.php">Accueil</a></li>
          <li><a href="ListeMatch.php">Match</a></li>
          <li><a href="ListeEquipes.php">Equipe</li>
          <li><a href="ListeJoueurs.php">Joueur</li>
        </ul>
      	</nav>

	  	<section class="profil_match">

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

				if(isset($_POST['delete'])){
					$requetesql = 'DELETE FROM participer WHERE id_match LIKE "'.$_POST['id_match_delete'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$requetesql = 'DELETE FROM matchh WHERE id_match LIKE "'.$_POST['id_match_delete'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();

					header('Location:http://localhost/php/ListeMatch.php');
				}

				// On récupère les infos du match séléctionné

				$requetesql = 'SELECT * FROM matchh, equipe WHERE id_match LIKE "'.$_POST['id_match'].'" AND equipe.id_equipe = matchh.id_equipe';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();

				foreach ($data as $data) {
			?>
					<p><?php echo 'Equipe : '.$data["nom_equipe"].'<br/>Equipe adverse : '.$data["nom_equipe_adverse"].'<br/>Lieu : '.$data["lieu_match"].'<br/>
					Date : '.$data["date_match"].'<br/>Heure : '.$data["heure"].'<br/>Nombre de points equipe : '.$data["nombre_points_equipe"].'<br/>
					Nombre de points equipe adverse: '.$data["nombre_points_equipe_adverse"].'<br/>'; ?></p>
			<?php
				}
				// On récupère les joueurs participants au match et les performance et leur rôle 

				$requetesql = 'SELECT * FROM matchh, participer, joueur WHERE matchh.id_match LIKE "'.$_POST['id_match'].'" AND participer.id_match = matchh.id_match AND participer.num_licence = joueur.num_licence';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();
				echo 'Liste des participants : ';
				foreach ($data as $data) {
					?>
							<p><?php echo ''.$data["nom"].' '.$data["prenom"].' Rôle : '.$data["role_joueur"].' Performance : '.$data["performance"].''; ?></p>
					<?php
						}
				echo
						'<form action="CreationMatch.php" method="post">
							<input type="submit" name="modifier_match" value="Modifier match"><br/>
							<input type="hidden" name="id_match" value="'.$_POST['id_match'].'">
						</form>
						<form action="ListeMatch.php" method="post">
							<input type="submit" value="Retour"><br/>
						</form>
						<form action="ProfilMatch.php" method="post">
							<input type="submit" name="delete" value="Supprimer"><br/>
							<input type="hidden" name="id_match_delete" value="'.$_POST['id_match'].'">
						</form>';

				
		?>

		</section>

	</body>

</html>
