<?php require_once "inc/header.inc.php"; ?>
<?php
// Affichage des produits

$r = execute_requete(" SELECT DISTINCT(categorie) FROM produit");

// Ici, je récupère les dftes catégorie de la table produit
$content .= '<div class="row">';

// Affichage  des catégories
        $content .= '<div class="col-6 col-lg-3">';
                $content .= '<div class="list-group-item">';

                        while($info=$r->fetch(PDO::FETCH_ASSOC)){
                                // debug($info);
                                $content .= "<a href='?categorie=$info[categorie]' class='list-group-item'>" . ucfirst($info['categorie']) .
                                             "</a>";
                        }
                $content .= '</div>';
        $content .= '</div>';

// Affichage des produits correspondant à la catégorie sélectionnée.
        $content .= '<div class="col-8 offset-1">';   
                $content .= '<div class="row">';

                // debug($_GET);
                if(isset($_GET['categorie'])){
                        $cat = htmlentities($_GET['categorie']);
                        // Ici, htmlentities permet de gérer les accents pour obtenir un formatage identique à celui dans la bdd.
                        $r = execute_requete("SELECT * FROM produit WHERE categorie =  '$cat'");

                        while($produit = $r->fetch(PDO::FETCH_ASSOC)){
                                // debug($produit);
                                $content .= '<div class="col-4">';
                                        $content .= '<div class="thumbnail" style="border:1px solid #eee;">';
                                        $content .= "<a href='fiche_produit.php?id_produit=$produit[id_produit]'>";
                                        $content .= "<img src='$produit[photo]' width='100'>";
                                        $content .= "<p>$produit[titre]</p>";
                                        $content .= "<p>$produit[prix]</p>";
                                        $content .= '</a>';
                                        $content .= '</div>';
                                $content .= '</div>';
                        }
                }

                $content .= '</div>';        
        $content .= '</div>';

$content .= '</div>';








?>

        <h1>Accueil du site</h1>
<?= $error ?>
<?= $content ?>



<?php require_once "inc/footer.inc.php"; ?>