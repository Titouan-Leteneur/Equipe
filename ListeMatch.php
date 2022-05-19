
 
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
		<title>Liste match</title>
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

		<div class="Titre"><h1>Liste des matchs : </h1></div>
		<section class="liste_match">
				
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

						// On récupère les infos sur les matchs

						$requetesql = 'SELECT matchh.id_match, equipe.nom_equipe, matchh.nom_equipe_adverse, matchh.lieu_match, matchh.date_match FROM equipe, matchh WHERE equipe.id_equipe = matchh.id_equipe';
						$requete = $linkpdo->prepare($requetesql);
						$requete->execute();
						$data = $requete->fetchAll();

						// On affiche chaque match un à un
						echo 
						'<div class="liste_matchs">';
						foreach ($data as $data) {
							?>
							<?php echo '
								<form action="ProfilMatch.php" method="post">
								<input type="submit" name="match" value="Equipe : '.$data["nom_equipe"].' Equipe adverse : '.$data["nom_equipe_adverse"].' Lieu : '.$data["lieu_match"].' Date : '.$data["date_match"].'"><br/>
								<input type="hidden" name="id_match" value="'.$data["id_match"].'">
								</form>'; 
							?>
							<?php
							
						}
						echo '</div>';
					?>
					<?php echo 
					'<div class="ajouetr_match">
					<form action="CreationMatch.php" method="post">
					<input type="submit" name="ajouter_match" value="Ajouter un match"><br/>
					</form>
					</div>'; 

				?>

		</section>

	</body>
</html>
