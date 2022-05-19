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
		<title>PageAccueil</title>
		<link rel="stylesheet" href="style.css"/>
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
		echo '<section class="stats_base">';
		$requetesql = 'SELECT count(id_match) FROM matchh WHERE nombre_points_equipe > nombre_points_equipe_adverse';
		$requete = $linkpdo->prepare($requetesql);
		$requete->execute();
		$data = $requete->fetchAll();
		foreach ($data as $data) {
			echo 'Nombre de victoires : ';
			echo $data['count(id_match)'];
			echo '</br>';
			$requetesql = 'SELECT count(id_match) FROM matchh';
			$requete = $linkpdo->prepare($requetesql);
			$requete->execute();
			$data2 = $requete->fetchAll();
			foreach ($data2 as $data2) {
				echo 'Pourcentage de victoires : ';
				$pourcent = ( $data['count(id_match)'] / $data2['count(id_match)'] ) * 100 ;
				echo $pourcent;
				echo '</br>';
			}
		}

		$requetesql = 'SELECT count(id_match) FROM matchh WHERE nombre_points_equipe < nombre_points_equipe_adverse';
		$requete = $linkpdo->prepare($requetesql);
		$requete->execute();
		$data = $requete->fetchAll();
		foreach ($data as $data) {
			echo 'Nombre de défaites : ';
			echo $data['count(id_match)'];
			echo '</br>';
			$requetesql = 'SELECT count(id_match) FROM matchh';
			$requete = $linkpdo->prepare($requetesql);
			$requete->execute();
			$data2 = $requete->fetchAll();
			foreach ($data2 as $data2) {
				echo 'Pourcentage de défaites : ';
				$pourcent = ( $data['count(id_match)'] / $data2['count(id_match)'] ) * 100 ;
				echo $pourcent;
				echo '</br>';
			}
		}

		$requetesql = 'SELECT count(id_match) FROM matchh WHERE nombre_points_equipe = nombre_points_equipe_adverse';
		$requete = $linkpdo->prepare($requetesql);
		$requete->execute();
		$data = $requete->fetchAll();
		foreach ($data as $data) {
			echo 'Nombre de matchs nuls : ';
			echo $data['count(id_match)'];
			echo '</br>';
			$requetesql = 'SELECT count(id_match) FROM matchh';
			$requete = $linkpdo->prepare($requetesql);
			$requete->execute();
			$data2 = $requete->fetchAll();
			foreach ($data2 as $data2) {
				echo 'Pourcentage de matchs nuls : ';
				$pourcent = ( $data['count(id_match)'] / $data2['count(id_match)'] ) * 100 ;
				echo $pourcent;
				echo '</br>';
			}
		}
		echo '</section>';

		echo '<section class="tableau">

		<table class="listePatients">
			<thead>
				<tr>
					<th>Nom : </th>
					<th>Prénom : </th>
					<th>Statut : </th>
					<th>Poste préféré : </th>
					<th>Nombre total de séléction en tant que titulaire : </th>
					<th>Nombre total de séléction en tant que remplacant : </th>
					<th>Moyenne des évaluation de l\'entraineur : </th>
					<th>Pourcentage de matchs gagnés lorsqu\'il a participé : </th>
				</tr>
			</thead>
			<tbody>';
			$requetesql = 'SELECT * FROM joueur';
			$requete = $linkpdo->prepare($requetesql);
			$requete->execute();
			$joueurs = $requete->fetchAll();
			foreach ($joueurs as $joueurs) {
			echo '	
				<tr>
					<th>'.$joueurs["nom"].'</th>
					<th>'.$joueurs["prenom"].'</th>
					<th>'.$joueurs["statut"].'</th>
					<th>'.$joueurs["poste_prefere"].'</th>';
					$requetesql = 'SELECT count(id_match) AS res FROM participer WHERE num_licence = "'.$joueurs['num_licence'].'" AND role_joueur = "Titulaire"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$titulaire = $requete->fetchAll();
					foreach ($titulaire as $titulaire) {
					echo'
					<th>'.$titulaire['res'].' </th>';
					}
					$requetesql = 'SELECT count(id_match) AS res FROM participer WHERE num_licence = "'.$joueurs['num_licence'].'" AND role_joueur = "Remplacant"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$remplacant = $requete->fetchAll();
					foreach ($remplacant as $remplacant) {
					echo'
					<th>'.$remplacant['res'].' </th>';
					}

					$requetesql = 'SELECT avg(performance) AS res FROM participer WHERE num_licence = "'.$joueurs['num_licence'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$perf = $requete->fetchAll();
					foreach ($perf as $perf) {
					echo'
					<th>'.$perf['res'].' /5 </th>';
					}
					
					$requetesql = 'SELECT count(matchh.id_match) AS res1 FROM matchh, participer WHERE participer.id_match = matchh.id_match
					AND matchh.nombre_points_equipe > matchh.nombre_points_equipe_adverse 
					AND participer.num_licence = "'.$joueurs['num_licence'].'"';
					$requete = $linkpdo->prepare($requetesql);
					$requete->execute();
					$data = $requete->fetchAll();
					foreach ($data as $data) {
						$requetesql = 'SELECT count(matchh.id_match) AS res2 FROM matchh, participer WHERE participer.id_match = matchh.id_match
						AND participer.num_licence = "'.$joueurs['num_licence'].'"';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute();
						$data2 = $requete->fetchAll();
						foreach ($data2 as $data2) {
							$pourcent = ( $data['res1'] / $data2['res2'] ) * 100 ;
							echo '<th>'.$pourcent.' % </th>';
						}
					}

					echo'
				</tr>';
			}
			echo'
			</tbody>
		</table>
		';
		echo '</section>';

	?>

	</body>
</html>
