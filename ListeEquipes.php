

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
		<title>Page Liste </title>
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
		<div class="Titre"><h1>Liste des équipes : </h1></div>
		<section class="liste_equipes">
		
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

				// On récupère tout le contenu de la table equipe
				$requetesql = 'SELECT nom_equipe FROM equipe';
				$requete = $linkpdo->prepare($requetesql);
				$requete->execute();
				$data = $requete->fetchAll();

				// On affiche chaque recette une à une
				foreach ($data as $data) {echo 

					'<form action="ProfilEquipe.php" method="post">
					<input type="submit" name="equipe" value="'.$data["nom_equipe"].'"><br/>
					</form>'; 

				}

				echo 
				'<form action="CreationEquipe.php" method="post">
				<input type="submit" name="ajouter_equipe" value="Ajouter une équipe"><br/>
				</form>'; 

			?>

		</section>

	</body>
</html>
