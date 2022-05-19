

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
		<title>Liste des joueurs</title>
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
		<div class="Titre"><h1>Liste des joueurs : </h1></div>
		<?php
			//Connexion à la base de données
			try {
				$linkpdo = new PDO("mysql:host=localhost;dbname=club", 'root', 'root');
				$linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(Exception $e){
				die('Erreur : '.$e->getMessage());
			}

				$req=$linkpdo->prepare('SELECT * FROM equipe, joueur WHERE joueur.id_equipe=equipe.id_equipe');
				$req->execute();

			while($data = $req -> fetch()){ ?>
				<div class="description_joueur">
					<?php echo	$data['nom']  ; ?>
				 	<?php echo	$data['prenom'] ;?> <br/>
					<?php	echo	$data['nom_equipe'] ; ?> <br/>
					<?php echo	'Numéro de licence : '.$data['num_licence'] ;?> <br/>
					<?php echo	'Date de naissance : ' .$data['date_naissance'] ;?> <br/>
					<?php echo	'Taille : '. $data['taille'].'m' ;?> <br/>
					<?php echo	'Poids : '. $data['poids'] .'kg';?> <br/>
					<?php echo	'Poste préféré : '. $data['poste_prefere'] ;?> <br/>
					<?php echo	'Commentaire : '.$data['commentaire'] ;?> <br/>
					<?php echo	'Statut : '. $data['statut'] ;
					?> <br/>
				 	<br/>
					<a href="ProfilJoueur.php?numeroLicence=<?php echo $data['num_licence']?>" ><button> Modifier ce joueur </button></a>
					<a href="SupprimerJoueur.php?numeroLicence=<?php echo $data['num_licence']?>" ><button> Supprimer ce joueur </button></a>
					</div>
					<?php
						
				}

				$req=$linkpdo->prepare('SELECT * FROM joueur');
				$req->execute();

			while($data = $req -> fetch()){ 
				if(empty($data['id_equipe'])){
					?>
				<div class="description_joueur">
					<?php echo	$data['nom']  ; ?>
				 	<?php echo	$data['prenom'] ;?> <br/>
					<?php echo	'Numéro de licence : '.$data['num_licence'] ;?> <br/>
					<?php echo	'Date de naissance : ' .$data['date_naissance'] ;?> <br/>
					<?php echo	'Taille : '. $data['taille'].'m' ;?> <br/>
					<?php echo	'Poids : '. $data['poids'] .'kg';?> <br/>
					<?php echo	'Poste préféré : '. $data['poste_prefere'] ;?> <br/>
					<?php echo	'Commentaire : '.$data['commentaire'] ;?> <br/>
					<?php echo	'Statut : '. $data['statut'] ;
					?> <br/>
					
				 	<br/>
					<a href="ProfilJoueur.php?numeroLicence=<?php echo $data['num_licence']?>" ><button> Modifier ce joueur </button></a>
					<a href="SupprimerJoueur.php?numeroLicence=<?php echo $data['num_licence']?>" ><button> Supprimer ce joueur </button></a>
					</div>
					<?php
				}}
				?>


		<form action="CreationJoueur.php" method="post">
			<input type="submit" name="creation_joueur" value="Créer un nouveau joueur"><br/>
		</form>
	</body>
</html>
