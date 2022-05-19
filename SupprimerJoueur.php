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
    $id= $_GET['numeroLicence'];
    echo $id;
    echo 'non';

    try {
        $linkpdo = new PDO("mysql:host=localhost;dbname=club", 'root', 'root');
        $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(Exception $e){
        die('Erreur : '.$e->getMessage());
    }
    echo 'non';
    $req = 'DELETE FROM participer WHERE num_licence LIKE "'.$_GET['numeroLicence'].'" ';
    echo $req;
     $req2 = $linkpdo->prepare($req);
     echo 'non';
    $req2->execute();

    $req = 'DELETE FROM joueur WHERE num_licence LIKE "'.$_GET['numeroLicence'].'" ';
    echo $req;
     $req2 = $linkpdo->prepare($req);
     echo 'non';
    $req2->execute();

    echo 'non';
     header('Location:http://localhost/php/ListeJoueurs.php');

?>
<html>
    <head>
        <title>PageAccueil</title>
    </head>
    <body>
        <nav>
        <ul>
          <li><a href="#">Accueil</a></li>
          <li><a href="ListeMatch.php">Match</a></li>
          <li><a href="ListeEquipes.php">Equipe</a></li>
          <li><a href="ListeJoueurs.php">Joueur</a></li>
        </ul>
      </nav>

            <?php echo "JOUEUR supprimé !"; ?>

    </body>
</html>