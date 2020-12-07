<?php require_once 'inc/header.inc.php'; ?>
<?php

/*  */
/* if(!empty($_GET) && isset($_GET['action']) && $_GET['action'] == 'suppression'){
    // debug($_GET['id_produit']);
    // debug($_SESSION['panier']['id_produit']);


    foreach($_SESSION['panier']['id_produit'] as $article_du_panier){
        if($article_du_panier == $_GET['id_produit']){
            // $article_du_panier = '';
            debug($article_du_panier);
        }
    
    }
} */

debug($_SESSION);

/*  */
     
if(isset($_POST['ajout_panier'])){
    // On vérifie l'existence d'un submit ds le fichier fiche_produit.php (ajout_panier vient de l'attribut name du submit ds fiche_produit.php)
    // debug($_POST);

    $r = execute_requete("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]' ");
    $produit = $r->fetch(PDO::FETCH_ASSOC);
        // debug($produit);

    ajout_panier($produit['titre'], $produit['id_produit'], $_POST['quantite'], $produit['prix']);
    // $_POST['quantite'] : provient du select de fiche_produit.php
   
}
// debug($_SESSION['panier']['id_produit']);

// debug($_SESSION);
// debug($_SESSION['panier']);
// ---------------------------------------
//EXERCICE : gérer la validation du panier ! SI on valide le panier
	//insertion dans la table 'commande'
	//récupération du numéro de commande : lastInsertId()
	//insertion du detail de la commande dans la table 'details_commande' (for) 
		//modification du stock en conséquence de la commande
	//et a la fin on vide le panier
// debug( $_SESSION['membre']['id_membre'] );
// debug( $_POST );

if(isset($_SESSION['membre'])){
    $id_membre = $_SESSION['membre']['id_membre'];
}

if(isset($_POST['payer']) && $_POST['payer'] == 'Payer'){
    
    $content .= '<div class="alert alert-success">Votre commande est validée.</div>';

    execute_requete("INSERT INTO commande(id_membre, montant, date)
                    VALUES('$id_membre',"
                           . montant_total() . ",
                           NOW()
                        )    
                    ");

    $id_derniere_commande = $pdo->lastInsertId();

    $content .= '<div class="alert">Votre numéro de commande est le ' . $id_derniere_commande . '.</div>';
    
    $id_produit = $_SESSION['panier']['id_produit'];

    // debug($_SESSION);

    for($i=0; $i < sizeof($id_produit); $i++){
        execute_requete("INSERT INTO details_commande(id_commande, id_produit, quantite, prix)
        VALUES('$id_derniere_commande',
                '$id_produit[$i]',"
                . $_SESSION['panier']['quantite'][$i] . ","
                . $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i]
            . ")    
        ");

        $r = execute_requete("SELECT stock FROM produit WHERE id_produit = '$id_produit[$i]'");
        $stock = $r->fetch(PDO::FETCH_ASSOC);
        $stock = $stock['stock'];
        $quantite = $_SESSION['panier']['quantite'][$i];
        $stock = $stock - $quantite;
        
        $r = execute_requete("UPDATE produit SET stock = '$stock' WHERE id_produit = '$id_produit[$i]'");


    }

    //et a la fin on vide le panier
    unset($_SESSION['panier']);

    /* foreach($_SESSION['panier'] as $value){
        unset($value);
    } */



}

// debug($_SESSION['panier']['quantite'][$i]);


// ---------------------------------------



// Afichage du panier

$content .= '<table class="table">';
        $content .= '<tr>
                        <th>Titre</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Supprimer</th>
                    </tr>';
    if(empty($_SESSION['panier']['id_produit'])){
        // Si ma session panier id_produit est vide, c'est q je n'ai rien ds mon panier.

    $content .= '<tr>
                    <td colspan="4">Votre panier est vide</td>
                </tr>';
    }
    else{
        // Sinon, il y a des prdts dsd le panier, dc on les affiche.

       for($i=0; $i<sizeof($_SESSION['panier']['id_produit']); $i++){
           $content .= '<tr>';
                $content .= '<td>' . $_SESSION['panier']['titre'][$i] . '</td>';
                $content .= '<td>' . $_SESSION['panier']['quantite'][$i] . '</td>';
                $content .= '<td>' . $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i] . ' euros</td>';

                /*  */

                /* $content .= '<td class="text-center">
                                <a href="?action=suppression&id_produit=' . $_SESSION['panier']['id_produit'][$i] . '" onclick="return(confirm(\'En êtes-vous certain ?\'))">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </td>'; */

                /*  */

           $content .= '</tr>';
       }
       $content .= "<tr>
                   <th colspan='2'>Montant total : </th>
                    <th>" . montant_total(). " euros</th></tr>";

        

        if(userConnect()){
            $content .= '<form method="post">';
                $content .= '<tr>';
                    $content .= '<td>';
                        $content .= '<input type="submit" value="Payer" name="payer" class="btn btn-secondary">';
                    $content .= '<td>';
                $content .= '</tr>';
            $content .= '</from';
        }
        else{
            $content .= '<tr>';
                $content .= '<td>';
                    $content .= 'Veuillez vous <a href="connexion.php">connecter</a> ou vous <a href="inscription.php">inscrire</a> pour pouvoir valider votre commande.';
                $content .= '<td>';
            $content .= '</tr>';
        }
    // $content .= ''
    }
        

$content .= '</table>';

?>


<h1>Panier</h1>
<?= $content; ?>


<?php require_once 'inc/footer.inc.php'; ?>