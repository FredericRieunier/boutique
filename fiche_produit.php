<?php require_once 'inc/header.inc.php'; ?>
<?php

// Affiche des infos du prdt concerné
if(isset($_GET['id_produit'])){
    // S'il y a id_produit ds l'url, c' qu'on choisit d'afficher la fiche d'un prdt en particulier. Dc, je récupère les infos du prdt concerné.
 
    $r = execute_requete("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]'");
}
else{
// Sinon on redirige vers la page d'accueil.
    header('location:index1.php');
    exit();
}

$produit = $r->fetch(PDO::FETCH_ASSOC);
    // debug($produit);

    $content .= '<a href="index1.php">Retour vers l\'accueil</a><br>';
    $content .= "<a href='index1.php?categorie=$produit[categorie]'>Retour vers la catégorie</a>";


    foreach($produit as $cle => $valeur){
        // SI l'indice de $produit vaut id_produit, on ne l'affiche pas
        if($cle == 'photo'){
            $content .= "<p><img src='$valeur' width='200'></p>";
        }
        else{
            if($cle != 'id_produit' && $cle != 'reference' && $cle != 'stock'){
                $content .= "<p>$valeur</p>";
            }
        }
    }

// ----------------------------------------------------------------------
    // Gestion du panier
if($produit['stock'] > 0){
    // Si le stock est supérieur à 0
    $content .= "<p>Nombre de produits disponibles : $produit[stock]</p>";
    $content .= '<form method="post" action="panier.php">';

        $content .= "<input type='hidden' name='id_produit' value='$produit[id_produit]'>";
    // Quand on cliquera sur le bouton d'ajout au panier, on sera basculé vers la page panier
        $content .= '<label>Quantité :</label><br>';
        $content .= '<select name="quantite">';
            for($i=1; $i<=$produit['stock']; $i++){
                $content .= "<option> $i </option>";
            }
        $content .= '</select><br><br>';

    $content .= '<input type="submit" name="ajout_panier" value="Ajouter au panier" class="btn btn-secondary">';
    $content .= '</form>';
}
else{
    $content .= '<p>Rupture de stock </p>';
}

?>

<h1><?= $produit['titre'] ?></h1>
<?= $content; ?>


<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>


<?php require_once 'inc/footer.inc.php'; ?>