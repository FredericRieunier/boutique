<?php require_once '../inc/header.inc.php'; ?>

<!-- Restriction d'accès à la page -->
<?php
if(!adminConnect()){
    header('location:../index1.php');
    exit();
}

// Gestion de la SUPPRESSION :
// debug($_GET);

if(isset($_GET['action']) && $_GET['action'] == 'suppression'){
    // S'il y a dans l'URL une action et qu'elle vaut suppression

    $r = execute_requete("SELECT photo FROM produit WHERE id_produit = '$_GET[id_produit]'");
    $photo_a_supprimer = $r->fetch(PDO::FETCH_ASSOC);
        // debug($photo_a_supprimer);

    $chemin_photo_a_supprimer = str_replace('http://localhost', $_SERVER['DOCUMENT_ROOT'], $photo_a_supprimer['photo']);
    // str_replace(arg1, arg2, arg3) : fonction interne de php qui permet de remplacer une chaîne de caractères :
        // arg1 : chaîen qu'on vt remplacer
        // arg2 : chaîne de remplacement
        // arg3 : Sur qle chaîne on vt fr les chgts.
    
        /* Ici, je remplace : http://localhost
                    par : (env) C:/mamp/htdocs (= $_SERVER['DOCUMENT_ROOT'])
                    dans : $photo_a_supprimer['photo'] (= http://localhost/PHP/boutique/nom_photo.jpg : c'est le chemin de la photo en bdd)
    
    */

    // debug($chemin_photo_a_supprimer);

    if(!empty($chemin_photo_a_supprimer) && file_exists($chemin_photo_a_supprimer)){
        unlink($chemin_photo_a_supprimer);
        // unlink() permet de suppr un fichier
    }

    

    

    execute_requete("DELETE FROM produit WHERE id_produit = '$_GET[id_produit]'");

}

/*  */
// Suppression multiple supprimer = suppression multiple
/* if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){

} */

/*  */

// Gestion des produits (INSERTION et MODIFICATION)
// Il faudrait ajouter les contrôles à ts ces champs

if(!empty($_POST)){

    // debug($_POST);
    foreach($_POST as $key => $value){
        // Je passe ttes les infos postées ds les fonctions htmlentities et addslashes
        $_POST[$key] = htmlentities(addslashes($value));
    }

    // Gestion de la photo
    // debug($_FILES);

    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        // Si je suis ds une modification, je récupère le chemin en bdd (grâce à l'input hidden) que je stocke ds la variable $photo_bdd !
        $photo_bdd = $_POST['photo_actuelle'];
    }


    if(!empty($_FILES['photo']['name'])){
        // Si le nom de la photo dans $_FILES n'est pas vide, c'est qu'on a uploadé un fichier.

        // Ici, je renomme la photo :

        $nom_photo = $_POST['reference'] . '_' . $_FILES['photo']['name'];
        // debug($nom_photo);
        
        // Chemin pour accéder à la photo (à insérer en BDD) : 
        $photo_bdd = URL . "photo/$nom_photo";
        // debug($photo_bdd);
        // debug($_SERVER);

        // On va utiliser :     [DOCUMENT_ROOT] 'ici : => C:/MAMP/htdocs).
        // Le jour où on sera en ligne, ça permettra d'avoir la bonne adresse de manière dynamique.
        // Où on souhaite enregistrer le fichier physique de la photo  :

        $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . "/PHP/boutique/photo/$nom_photo";
        // debug($photo_dossier);

        // Enregistrement de la photo au bon endroit, ici dans le dossier photo.
        copy($_FILES['photo']['tmp_name'], $photo_dossier);
        // copy(arg1, arg2)
            // arg1 : chemin du fichier source
            // arg2 : chemin de destination
    }

    
    else{

            //$photo_bdd =''; //Si pas de message d'erreur, on insérera du 'vide'
		$error .= '<div class="alert alert-danger">Il est nécessaire de charger une image.</div>';

    }
    

    // INSERTION ou MODIFICATION:
    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        // S'il y a une action qui vaut modification, on fait la requête de modification.
        
        execute_requete("UPDATE produit SET reference = '$_POST[reference]',
                                            categorie = '$_POST[categorie]',
                                            titre = '$_POST[titre]',
                                            description = '$_POST[description]',
                                            couleur = '$_POST[couleur]',
                                            taille = '$_POST[taille]',
                                            sexe = '$_POST[sexe]',
                                            photo = '$photo_bdd',
                                            prix = '$_POST[prix]',
                                            stock = '$_POST[stock]'
                        WHERE id_produit = '$_GET[id_produit]'         
        ");

        /* $content .= "<div class='alert alert-success'>La modification est confirmée.</div>"; */

        // redirection vers l'affichage des produits
        header('location:?action=affichage');

    } 
   elseif(empty($error)){    
   
    execute_requete("INSERT INTO produit(reference, categorie, titre, description, couleur, taille, sexe, photo, prix, stock)
                    VALUES(
                            '$_POST[reference]',
                            '$_POST[categorie]',
                            '$_POST[titre]',
                            '$_POST[description]',
                            '$_POST[couleur]',
                            '$_POST[taille]',
                            '$_POST[sexe]',
                            '$photo_bdd',
                            '$_POST[prix]',
                            '$_POST[stock]'
                        )
                    ");
            header('location:?action=affichage');
    echo '<div class="alert alert-success">La fiche a été correctement créée.</div>';
    
    }
}

// Affichage des produits
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
    // S'il y a une action qui vaut affichage ds l'url

    // Je récupère les produits en bdd
    $r = execute_requete("SELECT * FROM produit");
    $content .= '<h2> Liste des produits</h2>';
    $content .= '<p>Nombre de produits dans la boutique : ' . $r->rowCount() . '<p>';

    $content .= '<table border="2" cellpadding="5">';
        $content .= '<tr>';
            for($i=0; $i<$r->columnCount(); $i++){
                $colonne = $r->getColumnMeta($i);
                    // debug($colonne);
                    $content .= "<th>$colonne[name]</th>";
            }
            $content .= '<th>Suppression</th>';
            $content .= '<th>Modification</th>';
            /*  */
            // Ajout des suppressions multiples
            $content .= '<th>Sélection</th>';

            /*  */
        $content .= '</tr>';

        while($ligne = $r->fetch(PDO::FETCH_ASSOC)){
            $content .= '<tr>';
                // debug($ligne);

                // Exercice affichez les infos et la photo
           
                foreach($ligne as $value){
                    if($value != $ligne['photo']){
                        $content .= '<td>';
                        $content .= $value;
                        $content .= '</td>';
                    }
                    else{
                        $content .= '<td>';
                        $content .= '<img src="' . $ligne['photo'] . '" alt="" width="50">';
                        $content .= '</td>';
                    }
                }
                $content .= '<td class="text-center">
                                <a href="?action=suppression&id_produit=' . $ligne['id_produit'] . '" onclick="return(confirm(\'En êtes-vous certain ?\'))">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </td>';

                $content .= '<td class="text-center">
                                <a href="?action=modification&id_produit=' . $ligne['id_produit'] . '">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>';

                            /*  */
                // Ajout des suppressions multiples
                $content .= '<td class="text-center">
                                <form method="post" action="panier.php" name="">
                                    <input type="checkbox" name="supprim[]" value="' . $ligne['id_produit'] . '">
                                </form>
                            </td>';

                


                            /*  */

            $content .= '</tr>';
        }


    $content .= '</table>';

    /*  */
    $content .= '<br><div class="row justify-content-end"><form method="post" action="gestion_boutique.php?action=supprimer&id_produit=" name=""><input type="submit" class="mr-5" value="Supprimer les produits sélectionnés"></form></div>';
   
    
    /*  */
    // $r->fetchAll(PDO::FETC_ASSOC);


}

debug($_GET);
?>


<h1>Gestion de la boutique</h1>

<!-- 2 liens pour gérer le formulaire d'ajout ou le formulaire, selon l'action passée ds l'url -->
<a href="?action=ajout">Ajout produit</a><br>
<a href="?action=affichage">Affichage des produits</a><br>
<hr>


<?= $error; ?>
<?= $content; ?>

<?php if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')) : 
// S'il y a une action ds l'URL et qu'elle vaut 'ajout OU modification, on affiche le formulaire

    if(isset($_GET['id_produit'])){
        // S'il y a id_produit dans l'url, c'est q je suis ds le cadre d'une modification.
        $r = execute_requete("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]'");

        $article_actuel = $r->fetch(PDO::FETCH_ASSOC);
            // debug($article_actuel);
    }

    // Condition pr vérifier l'existence des variables :
        if(isset($article_actuel['reference'])){
            $reference = $article_actuel['reference'];
            // On stocke la valeur ds une variable
        }
        else{
            // Sinon, on crée cette variable à vide
            $reference = '';
        }

        $categorie = (isset($article_actuel['categorie'])) ?  $article_actuel['categorie'] : $categorie = '';
        
        $titre = (isset($article_actuel['titre'])) ?  $article_actuel['titre'] : $titre = '';

        $description = (isset($article_actuel['description'])) ?  $article_actuel['description'] : $description = '';

        $couleur = (isset($article_actuel['couleur'])) ?  $article_actuel['couleur'] : $couleur = '';

        // $taille = (isset($article_actuel['taille'])) 
        
        if(isset($article_actuel['taille']) && $article_actuel['taille'] == 'S'){
            $tailleS = 'selected';
        }
        else{
            $tailleS = '';
        }

        $tailleM = (isset($article_actuel['taille']) && $article_actuel['taille'] == 'M') ? 'selected' : '';
        $tailleL = (isset($article_actuel['taille']) && $article_actuel['taille'] == 'L') ? 'selected' : '';
        $tailleXL = (isset($article_actuel['taille']) && $article_actuel['taille'] == 'XL') ? 'selected' : '';


        if(isset($article_actuel['sexe']) && isset($article_actuel['sexe']) == 'm'){
            $sexeM = 'checked';
        }
        else{
            $sexeM = '';
        }
        $sexeF = (isset($article_actuel['sexe']) && isset($article_actuel['sexe']) == 'f') ? 'checked' : '';

        

        // debug($article_actuel['photo']);
        
        $prix = (isset($article_actuel['prix'])) ?  $article_actuel['prix'] : $prix = '';
        $stock = (isset($article_actuel['stock'])) ?  $article_actuel['stock'] : $stock = '';

       


?>

<form method="post" enctype="multipart/form-data">
<!-- enctype="multipart/form-data" est un attribut obligatoire lorqu'on veut uploader des fichiers -->

    <label>Référence</label><br>
    <input type="text" name="reference" class="form-control" value="<?= $reference?>"><br><br>

    <label>Catégorie</label><br>
    <input type="text" name="categorie" class="form-control" value="<?= $categorie?>"><br><br>

    <label>Titre</label><br>
    <input type="text" name="titre" class="form-control" value="<?= $titre?>"><br><br>

    <label>Description</label><br>
    <input type="text" name="description" class="form-control" value="<?= $description?>"><br><br>

    <label>Couleur</label><br>
    <input type="text" name="couleur" class="form-control" value="<?= $couleur?>"><br><br>

    <label>Taille</label><br>
    <select name="taille" value="L">
        <option value="S" <?= $tailleS ?>>S</option>
        <option value="M" <?= $tailleM ?>>M</option>
        <option value="L" <?= $tailleL ?>>L</option>
        <option value="XL" <?=$tailleXL ?>>XL</option>

    </select><br><br>

    <label>Sexe</label><br>
    <input type="radio" name="sexe" value="m" <?=$sexeM ?>>Homme<br>
    <input type="radio" name="sexe" value="f" <?=$sexeF ?>>Femme<br><br>

    <label>Photo</label><br>
    <input type="file" name="photo"><br>
    <?php 
    if(isset($article_actuel['photo'])){
        /* $affichage_photo = '<img src="' . $article_actuel['photo'] . '" alt="" width="80">';
        $chemin_photo = $article_actuel['photo']; */

        echo "<i>Vous pouvez uploader une nouvelle photo</i>";

        echo "<img src='$article_actuel[photo]' width='80' ><br><br>";
        
        echo "<input type='hidden' name='photo_actuelle' value='$article_actuel[photo]'>";
    }
    
    ?><br><br>

    <label>Prix</label><br>
    <input type="text" name="prix" class="form-control" value="<?= $prix?>"><br><br>

    <label>Stock</label><br>
    <input type="text" name="stock" class="form-control" value="<?= $stock?>"><br><br>

    <input type="submit" value="<?= ucfirst($_GET['action']); ?>" class="btn btn-secondary">
    <!-- ucfirst() permet de mettre la 1re lettre en majuscule -->

</form>

<?php endif; ?>

<br><br><br><br>


<?php require_once '../inc/footer.inc.php'; ?>