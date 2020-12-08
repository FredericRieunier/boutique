<?php require_once "init.inc.php"; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--  CDN de BOOTSTRAP -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- CDN FONT AWESOME-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

<!-- CSS PERSO ( en dernière position ) -->
<link rel="stylesheet" href="">

<!--  -->
<style>

.fiche-produit{
  width: 100%;
  min-height: 2rem;
  background-color: lightgray;
  border: 1px solid black;
  margin-bottom: 1rem;
  padding: 1rem;
}

.fiche-produit h3{
  text-align: center;
}

.fiche-produit p{
  text-align: justify;
}

.categorie{
  text-align: right !important;
  font-weight: bold;
}

.prix{
  display: flex;
  align-items: flex-end;
  justify-content: flex-end;
  font-weight: bold;
}

</style>
<!--  -->

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= URL ?>index1.php">LOGO</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>index1.php">Accueil</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>panier.php">Panier</a>
      </li>

      <?php if(userConnect()) : //Si l'internaute est connecté, on affiche les liens profil et déconnexion
      ?>

        <li class="nav-item">
          <a class="nav-link" href="<?= URL ?>profil.php">Profil</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= URL ?>connexion.php?action=deconnexion">Déconnexion</a>
        </li>

      <?php else : //Sinon, c'est qu'on n'est pas connecté, on affiche le lien inscription. ?>

        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL ?>inscription.php">Inscription</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL ?>connexion.php">Connexion</a>
        </li>

      <?php endif; ?>
      
      
          <!-- *** Back office *** -->

      <?php if(adminConnect()) : //Si l'admin est connecté :
      ?>
          <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          BackOffice
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion boutique</a>
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion membres</a>
          <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion commandes</a>
        </div> 

      <?php endif; ?>
       

      </li> 
    </ul>
  </div>
</nav>

    <div class="container">