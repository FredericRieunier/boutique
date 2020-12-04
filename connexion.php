<?php require_once 'inc/header.inc.php'; ?>
<?php

// DECONNEXION : 
// Debug ($_GET);

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){ //S'il y a une action valant deconnexion dans l'URL alors on détruit la session.

    session_destroy();
}

// Restriction à l'accès à cette page si on est connecté :
if(userConnect()){
    header('location:profil.php');
    exit();
}

if($_POST){
    // debug($_POST);



    // Comparaison du pseudo posté et celui en bdd :
    $r = execute_requete(" SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");

    if($r->rowCount() >= 1){
        // S'il y a une correspondance ds la table memebre, $r renverra 1 ligne, donc le pseudo existe !

        echo "OK";
        
        
        $membre = $r->fetch(PDO::FETCH_ASSOC);
        // debug($membre);

        if(password_verify($_POST['mdp'], $membre['mdp'])){
            //password_verify(arg1, arg2) : permet de comparer une chaîne de caractère ac une chaîne cryptée.
                // arg1 : le mdp (ici, posté par l'internaute)
                // arg2 : la chaîne cryptée (par la fonction password_hash, ici, le mdp en bdd)
                echo '<div class="alert alert-success">Vous êtes connecté.</div>';

                //Ici, on va renseigner les informations concernant la personne connectée dans notre fichier de session:
			foreach( $membre as $index => $valeur ){

				$_SESSION['membre'][$index] = $valeur;
			}
           
            // redirection vers la page de profil
            header('location:profil.php');

        }
        else{  //Sinon, le mdp est incorrect.
            $error .= '<div class="alert alert-danger">Le mot de passe ne correspond pas au pseudo saisi.</div>';
        }


    }
    else{ //Sinon, le pseudo n'existe donc pas ds la bdd

        $error .= '<div class="alert alert-danger">Ce pseudo n\'existe pas.</div>';

    }
}



?>

<h1>Connexion</h1>
<?= $error ?>

<form method="post">

    <label>Pseudo :</label><br>
    <input type="text" name="pseudo" class="form-control"><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mdp" class="form-control"><br><br>

    <input type="submit" value="Se connecter" class="btn btn-secondary">

</form>



<?php require_once 'inc/footer.inc.php'; ?>