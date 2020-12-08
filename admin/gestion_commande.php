<?php require_once '../inc/header.inc.php'; ?>

<?php

// Sécuriser cette page en empêchant l'accès aux membres non admin
if(!adminConnect()){
    header('location:../connexion.php');
    exit;
}

// Affichage des commandes
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
    $r = execute_requete("SELECT commande.id_commande, commande.id_membre, commande.montant, commande.date, commande.etat, membre.pseudo, membre.adresse, membre.ville, membre.cp
     FROM commande, membre
     WHERE commande.id_membre = membre.id_membre");
    $content .= '<h2> Liste des commandes</h2>';
    $content .= '<p>Nombre de commandes effectuées : ' . $r->rowCount() . '<p>';


    // Création tableau et en-tête
    $content .= '<table border="2" cellpadding="5">';
        $content .= '<tr>';
            for($i=0; $i<$r->columnCount(); $i++){
                $colonne=$r->getColumnMeta($i);
                // debug($colonne['name']);
                if($colonne['name'] != 'mdp')
                $content .= "<th>$colonne[name]</th>";
            }
        /*     $content .= "<th>Pseudo</th>";
            $content .= "<th>Adresse</th>";
            $content .= "<th>Ville</th>";
            $content .= "<th>Code postal</th>"; */


        $content .= '</tr>';

        // Ajout des lignes
        while($ligne = $r->fetch(PDO::FETCH_ASSOC)){
            $content .= '<tr>';

            // Pour chq ligne, ajout des cellules, mais en masquant le mdp
            foreach($ligne as $indice => $value){
                if($indice == 'id_commande'){
                $content .= "<td><a href='?action=affichage&id_commande=" . $ligne['id_commande'] . "'>Voir la commande "  .$value. "</a></td>";
                }
                else {
                    $content .= "<td>" .$value. "</td>";
                }
            }


            
           

            $content .= '</tr>';
        }

    $content .= '</table>';

    // Affichage du CA
    $r = execute_requete("SELECT SUM(montant) FROM commande");
    $chiffre_affaire = $r->fetch(PDO::FETCH_ASSOC)['SUM(montant)'];
//    debug($r->fetch(PDO::FETCH_ASSOC)['SUM(montant)']); 
    $content .= '<div class="alert alert-secondary">Chiffre d\'affaires : ' . $chiffre_affaire . ' euros</div>';

}

// Affichage du détail des commandes
if(isset($_GET['id_commande'])){
    $content .= "<h3>Voici le détail de la commande $_GET[id_commande]</h3>";

    $detail_commande = execute_requete("SELECT d.*, p.titre 
    FROM details_commande as d, produit as p 
    WHERE d.id_commande = '$_GET[id_commande]'
    AND d.id_produit = p.id_produit
    ");
    // $affiche_commande = $r->fetch(PDO::FETCH_ASSOC);

    $content .= '<table border="2" cellpadding="5">';
        $content .= '<tr>';
            for($i=0; $i<$detail_commande->columnCount(); $i++){
                $colonne=$detail_commande->getColumnMeta($i);
                // debug($colonne['name']);
                // if($colonne['name'] != 'mdp')
                $content .= "<th>$colonne[name]</th>";
            }
        $content .= '</tr>';
        // debug($colonne);

        // Ajout des lignes

        // Voir ac la correction pq l'affichage du détiail des commande marche pas chez moi.

        // debug($r->fetch(PDO::FETCH_ASSOC));
        while($ligne = $detail_commande->fetch(PDO::FETCH_ASSOC)){
            $content .= '<tr>';

            // Pour chq ligne, ajout des cellules, mais en masquant le mdp
            foreach($ligne as $indice => $value){
                
                    $content .= "<td> $value </td>";
                
            }

            $content .= '</tr>';
        }

    $content .= '</table>';



    // $content .= $affiche_commande;
}

// $content .= 

?>


<h1>Gestion des commandes</h1>
<p><a href="?action=affichage">Afficher les commandes effectuées</a></p>

<?= $content; ?>



<br><br><br><br><br><br><br>
<?php require_once '../inc/footer.inc.php'; ?>