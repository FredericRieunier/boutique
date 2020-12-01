<?php require_once "inc/header.inc.php"; ?>
<?php

if($_POST){
    debug($_POST);

    // Si la taille du pseudo posté 
    if(strlen($_POST['pseudo']) <= 3 || strlen($_POST['pseudo']) >= 15){
        // strlen($arg) : retourne la taille de la chaîne (ici $arg)

        $error .= "<div class='alert alert-danger'>Erreur taille pseudo</div>";

    }

    /* Il faudra des vérifications pr chaque champ pour vérifier 
    - qu'aucun n'est vide, 
    - tester les expressions régulières pour avoir une adresse mail ou autre au bon format  
    
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
                                    '$_POST[prenom]',
                                    '$_POST[nom]',
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
    <input type="text" name="mdp" class="form-control"><br>

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

    <label>Ville</label>
    <input type="text" name="ville" class="form-control"><br>

    <label>Code postal</label>
    <input type="text" name="cp" class="form-control"><br>


    <input type="submit" value="S'inscrire" class="btn btn-secondary">
</form>

<?php require_once "inc/footer.inc.php"; ?>