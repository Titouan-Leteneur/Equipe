<?php
	//Démarrage de l’environnement de session
	session_start();


	//util connecté?
	if(isset($_POST['deconnection'])){
		session_destroy();
		header('Location:http://localhost/php/PageAuthentification.php');
	}


	//session existe?
	if (isset($_SESSION['login']) && isset($_SESSION['mdp']) && $_SESSION['login'] == 'admin' && $_SESSION['mdp'] == '1234'){

		//si oui, affichage message d'accueil et affiche le nombre de visites
		header('Location:http://localhost/php/PageAccueil.php');

	//si non,
	}else{
		//si formulaire rempli
		if(isset($_POST['login']) && isset($_POST['mdp'])){
			//alors crétion session
			$_SESSION['login'] = $_POST['login'];
			$_SESSION['mdp'] = $_POST['mdp'];
			header('Location:http://localhost/php/PageAuthentification.php');

		//sinon afficher le formulaire pour qu'il puisse être rempli
		}else{
			echo'
			  <html>

				<body>
					<form action="PageAuthentification.php" method="post">
						Login : <input type="text" name="login"><br/>
						Mot de Passe : <input type="text" name="mdp"><br/>
							<input type="submit" value="Envoyer"><br/>
					</form>
				</body>

			</html>';
		}
	}

?>
