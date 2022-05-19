
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
		<title></title>
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
	  <section class="profil_equipe">
		
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

				$requetesql = 'SELECT id_equipe FROM equipe WHERE nom_equipe LIKE "'.$_POST['nom_delete'].'"';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();

				foreach ($data as $data) {

					$requetesql = 'UPDATE joueur SET id_equipe = :id_equipe WHERE joueur.id_equipe LIKE "'.$data['id_equipe'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute([
						'id_equipe' => null,
					]);

					$requetesql = 'SELECT id_match FROM matchh WHERE id_equipe LIKE "'.$data['id_equipe'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$data2 = $requete->fetchAll();

					foreach ($data2 as $data2) {

						$requetesql = 'DELETE FROM participer WHERE id_match LIKE "'.$data2['id_match'].'"';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute();

					}

					$requetesql = 'DELETE FROM matchh WHERE id_equipe LIKE "'.$data['id_equipe'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					
					$requetesql = 'DELETE FROM equipe WHERE id_equipe LIKE "'.$data['id_equipe'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
				}
					header('Location:http://localhost/php/ListeEquipes.php');
				}
			
				$requetesql = 'SELECT nom_equipe FROM equipe WHERE nom_equipe LIKE "'.$_POST['equipe'].'"';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();

				foreach ($data as $data) {
			?>
					<p><?php echo $data['nom_equipe']; ?></p>
			<?php
				}

				$requetesql = 'SELECT nom, prenom, photo, poste_prefere, statut FROM joueur WHERE id_equipe = (SELECT id_equipe FROM equipe WHERE nom_equipe LIKE "'.$_POST['equipe'].'")';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();

				foreach ($data as $data) {
			?>
					<p><?php echo 'Nom : '.$data['nom'].''; echo ' Prenom : '.$data['prenom'].''; echo ' Poste préféré : '.$data['poste_prefere'].''; 
					echo ' Statut : '.$data['statut'].''; echo ' Photo : <img src="'.$data['photo'].'"/>' 
					?></p>
			<?php

				}

				echo
						'<form action="CreationEquipe.php" method="post">
							<input type="submit" name="modifier_equipe" value="Modifier équipe"><br/>
							<input type="hidden" name="nom_equipe" value="'.$_POST['equipe'].'">
						</form>
						<form action="ListeEquipes.php" method="post">
							<input type="submit" value="Retour"><br/>
						</form>
						<form action="ProfilEquipe.php" method="post">
							<input type="submit" name="delete" value="Supprimer"><br/>
							<input type="hidden" name="nom_delete" value="'.$_POST['equipe'].'">
						</form>';
						
			?>

		</section>
	</body>
</html>
