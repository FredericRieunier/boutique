<?php require_once 'inc/header.inc.php' ?>

<?php 

if(!userConnect()){
    // Si l'utilisateur n'est pas connecté.
    
    // redirection vers la page de connexion
    header('location:connexion.php');

    exit();
    // exit() : permet de terminer la lecture du script courant et à cet endroit précis, je quitte la page. Le reste du script n'est dc pas lu. Ca améliore les performances du site.   
}

if(adminConnect()){
    //Si c'est un admin qui est connecté, on affiche un titre

    $content .= '<h3 style="color: red;">Administrateur</h3>';
}




// ----------------------------------------------------
    // debug($_SESSION);

    $pseudo = $_SESSION['membre']['pseudo'];

    $content .= '<h3>Vos informations personnelles :</h3>';

    foreach($_SESSION['membre'] as $indice => $value){
        $content .= "<p> Votre $indice : $value </p>"; 
    }

    $content .= '<p>Votre prénom : ' . $_SESSION['membre']['prenom'] . '</p>';

?>

<h1>Profil</h1>

<p>Bonjour, <?= $_SESSION['membre']['pseudo'];  ?> !</p>
<p>Vous vous appelez en réalité <?= $_SESSION['membre']['prenom'] . ' ' . $_SESSION['membre']['nom']; ?> et vous habitez à <?= $_SESSION['membre']['ville']; ?>. </p>

<?= $content; ?>


<?php require_once 'inc/footer.inc.php' ?>