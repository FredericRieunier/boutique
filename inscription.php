<?php require_once "inc/header.inc.php"; ?>

<h1>Inscription</h1>

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

    <label>Civilité</label>
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