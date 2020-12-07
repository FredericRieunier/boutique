<?php require_once 'inc/header.inc.php'; ?>

<h1>Nos produits</h1>

<div class="container-fluid">

<img src="" alt="">
<?php

    $r = execute_requete(" SELECT * FROM produit");
    $produit_affiche = $r->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($produit_affiche as $indice => $value){
        // debug($produit_affiche[$indice]);
    

        echo "<div class='row fiche-produit'>";

            echo "<div class='col-4'>";

            echo '<img src="' . $produit_affiche[$indice]['photo'] . '" alt="">';
            echo "</div>";

            echo "<div class='container col-8'><div class='row'><h3 class='col-8'>";    
            echo $produit_affiche[$indice]['titre'];
            echo "</h3>";
            
            echo "<p class='col-4 categorie'>";    
            echo $produit_affiche[$indice]['categorie'];
            echo "</p>";

            echo "<p class='col-8 pl-4'>";    
            echo $produit_affiche[$indice]['description'];
            echo "</p>";
            
            echo "<p class='col-4 prix'>";    
            echo $produit_affiche[$indice]['prix'] . ' euros';
            echo "</p>";


        echo "</div></div></div>";
    }

?>
</div>

<?php require_once 'inc/footer.inc.php'; ?>