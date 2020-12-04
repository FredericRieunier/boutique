<?php require_once "inc/header.inc.php"; ?>
<?php

if(userConnect()){

    header('location:profil.php');
    exit();
}

if($_POST){
    debug($_POST);

    // Si la taille du pseudo posté 
    if(empty($_POST['pseudo'])){
        $error .= "<div class='alert alert-danger'>Veuillez renseigner tous les champs</div>";
    }
    elseif(strlen($_POST['pseudo']) <= 3 || strlen($_POST['pseudo']) >= 15){
        // strlen($arg) : retourne la taille de la chaîne (ici $arg)

        $error .= "<div class='alert alert-danger'>Votre pseudo doit comporter au moins 4 caractères.</div>";

    }

    /*  */
        // Test le format de mail.
        $email = $_POST['email'];
        if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)){
            // echo "L'adresse $email est valide !";
        }
        else{
            $error .= "<div class='alert alert-danger'>L'adresse $email ne correspond pas à un format d'adresse e-mail.</div>";
        }

        // Test du format du numéro de code postal
        $cp = $_POST['cp'];
        // echo $cp;
        if(preg_match("#^[0-9]{5}$#", $cp)){
            // echo '<div class="alert alert-success">Le code postal est au bon format.</div>'; 
        }
        else{
            $error .= "<div class='alert alert-danger'>Le code postal $cp ne correspond pas à un format de code postal.</div>";
        }



    /*  */
   

    /* Il faudra des vérifications pr chaque champ pour vérifier 
    - qu'aucun n'est vide, 
    - tester les expressions régulières pour avoir une adresse mail ou autre au bon format  
    - virer les balises
    
    */

    // Tester si le pseudo est disponible (car on ne peut pas avoir 2 fois le mm pseudo, puisque ce champ a une clé unique ds la bdd)

    $r = execute_requete(" SELECT pseudo FROM membre WHERE pseudo = '$_POST[pseudo]'");

    if($r->rowCount() >= 1){
        // SI le résultat est supérieur ou égal à 1, c'est q le pseudo a déjà été pris (car il aura trouvé une correspondance dans la table 'membre' et renverra donc une ligne de résultat et rowCount() sera égal à 1)

        $error .= "<div class='alert alert-danger'>Pseudo indisponible</div>";
    }

    // Boucle sur les saisies pour passer ds les fonctions (addslashes, htmlentities)
    foreach($_POST as $indice => $valeur){
        $_POST[$indice] = htmlentities(addslashes($valeur));
    }

    $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    // Crée une clé de hachage.
    // echo $_POST['mdp'];

    // INSERTION : 
    if(empty($error)){ //Si la variable '$error' est vide (c'est qu'on a rempli le formulaire correctement), on fait l'insertion.

        execute_requete("INSERT INTO membre(pseudo, mdp, prenom, nom, email, sexe, ville, cp, adresse) 
                        VALUES (
                                    '$_POST[pseudo]',
                                    '$_POST[mdp]',
                                    '$_POST[nom]',
                                    '$_POST[prenom]',
                                    '$_POST[email]',
                                    '$_POST[sexe]',
                                    '$_POST[ville]',
                                    '$_POST[cp]',
                                    '$_POST[adresse]'
                            )
                         ");
        echo '<div class="alert alert-success">Inscription validée. 
                            <a href="' .URL. 'connexion.php">Cliquez ici pour vous connecter.</a>
        </div>';

    }

}




?>

<h1>Inscription</h1>
<?= $error; //affichage des message d'erreurs ?>

<form method="post">

    <label>Pseudo</label>
    <input type="text" name="pseudo" class="form-control"><br>

    <label>Mot de passe</label>
    <input type="password" name="mdp" class="form-control"><br>

    <label>Nom</label>
    <input type="text" name="nom" class="form-control"><br>

    <label>Prénom</label>
    <input type="text" name="prenom" class="form-control"><br>

    <label>E-mail</label>
    <input type="text" name="email" class="form-control"><br>

    <label>Civilité</label><br>
    <input type="radio" name="sexe" value="f">Femme<br>
    <input type="radio" name="sexe" value="m">Homme<br>

    <label>Adresse</label>
    <input type="text" name="adresse" class="form-control"><br>

    <label>Code postal</label>
    <input type="text" name="cp" class="form-control"><br>

    <label>Ville</label>
    <input type="text" name="ville" class="form-control"><br>


    <input type="submit" value="S'inscrire" class="btn btn-secondary">
</form>

<?php require_once "inc/footer.inc.php"; ?>