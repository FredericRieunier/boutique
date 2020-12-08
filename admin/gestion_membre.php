<?php require_once '../inc/header.inc.php'; ?>

<?php

// Sécuriser cette page en empêchant l'accès aux membres non admin
if(!adminConnect()){
    header('location:../connexion.php');
    exit;
}

// ACTION de suppression d'un membre
if(isset($_GET['action']) && $_GET['action'] == 'suppression'){
    $r = execute_requete("DELETE FROM membre WHERE id_membre = '$_GET[id_membre]'");

    // Redirection vers l'affichage :
    header('location:?action=affichage');
    exit();

}

// ACTION de modification d'un membre
    // Admettons que les vérifications ont été faites (aucun champ n'est vide, chacun est rempli correctement : pseudo d'au moins 3 caractères, adresse mail ac un format correct, etc., le nouveau pseudo choisi n'existe pas encore dans la bdd) et on se passe des vérifications de sécurité puisque seul les admin peuvent modifier ces champs.

    // appliquer requête en reprenant inscription ligne 78

if(isset($_GET['action']) && $_GET['action'] == 'modification'){
    execute_requete("UPDATE membre SET pseudo = '$_POST[pseudo]', 
                                       nom = '$_POST[nom]', 
                                       prenom = '$_POST[prenom]',
                                       email = '$_POST[email]',
                                       sexe = '$_POST[sexe]',
                                       ville = '$_POST[ville]',
                                       cp = '$_POST[cp]',
                                       adresse = '$_POST[adresse]',
                                       statut = '$_POST[statut]'
    
        WHERE id_membre = '$_GET[id_membre]'
    ");

    header('location:?action=affichage');
    exit();

    
}


    

// On vérifie qu'on est bn ds le cadre de l'affichage du formulaire : 
if(isset($_GET['action']) && $_GET['action'] == 'formulaire'){


    // Affichage du formulaire de modif

    // On vérifie qu'on a bien un membre sélectionné pour éviter des messages d'erreur de PHP
    if(isset($_GET['id_membre'])){
        $r = execute_requete("SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]'");
        $infos_membre = $r->fetch(PDO::FETCH_ASSOC);
        // debug($infos_membre);

            $content .= '<form method="post" action="?action=modification&id_membre=' . $_GET['id_membre'] . '"   enctype="multipart/form-data">';
                $content .= '<label>Pseudo</label><br>
                            <input type="text" name="pseudo" class="form-control" value="' . $infos_membre['pseudo'] . '"><br>';

                $content .= '<label>Nom</label><br>
                <input type="text" name="nom" class="form-control" value="' . $infos_membre['nom'] . '"><br>';
                
                $content .= '<label>Prénom</label><br>
                <input type="text" name="prenom" class="form-control" value="' . $infos_membre['prenom'] . '"><br>';

                $content .= '<label>E-mail</label><br>
                <input type="text" name="email" class="form-control" value="' . $infos_membre['email'] . '"><br>';

                if($infos_membre['sexe'] == 'm'){
                    $homme = ' checked ';
                }
                else{
                    $homme = '';
                }

                if($infos_membre['sexe'] == 'f'){
                    $femme = ' checked ';
                }
                else{
                    $femme = '';
                }

                $content .= '<label>Sexe</label><br>
                <input type="radio" name="sexe"' . $femme . 'value="f"> Femme<br>
                <input type="radio" name="sexe"' . $homme . 'value="h"> Homme<br><br>';

                $content .= '<label>Adresse</label><br>
                <input type="text" name="adresse" class="form-control" value="' . $infos_membre['adresse'] . '"><br>';

                $content .= '<label>Code postal</label><br>
                <input type="text" name="cp" class="form-control" value="' . $infos_membre['cp'] . '"><br>';

                $content .= '<label>Ville</label><br>
                <input type="text" name="ville" class="form-control" value="' . $infos_membre['ville'] . '"><br>';

                $content .= '<label>Statut</label><br>
                <input type="text" name="statut" class="form-control" value="' . $infos_membre['statut'] . '"><br>';

                $content .= '<input type="submit" value="Modifier" class="btn btn-secondary"> <br>';



            $content .= '</form>';
    }
    else{
        $content .= '<div class="alert alert-danger">Le membre que vous voulez modifier n\'existe pas.</div>';
    }

}

// Affichage des membres
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
    $r = execute_requete("SELECT * FROM membre");
    $content .= '<h2> Liste des membres</h2>';
    $content .= '<p>Nombre de membres inscrits : ' . $r->rowCount() . '<p>';

    // Création tableau et en-tête
    $content .= '<table border="2" cellpadding="5">';
        $content .= '<tr>';
            for($i=0; $i<$r->columnCount(); $i++){
                $colonne=$r->getColumnMeta($i);
                // debug($colonne['name']);
                if($colonne['name'] != 'mdp')
                $content .= "<th>$colonne[name]</th>";
            }

            $content .= "<th>Modification</th>";
            $content .= "<th>Suppression</th>";

        $content .= '</tr>';

        // Ajout des lignes
        while($ligne = $r->fetch(PDO::FETCH_ASSOC)){
            $content .= '<tr>';

            // Pour chq ligne, ajout des cellules, mais en masquant le mdp
            foreach($ligne as $indice => $value){
                if($indice != 'mdp'){
                $content .= "<td>" .$value. "</td>";
                }
            }
            
            // Modification et suppression
            $content .= "<td class='text-center'><a href='?action=formulaire&id_membre=" . $ligne['id_membre'] . "'><i class='far fa-edit'></i></a></td>";
            $content .= "<td class='text-center'><a href='?action=suppression&id_membre=" . $ligne['id_membre'] . "' onclick='return(confirm(\"En êtes-vous certain ?\") )'><i class='far fa-trash-alt'></i></a></td>";
        //    debug($ligne['id_membre']);



            $content .= '</tr>';
        }

    $content .= '</table>';
}

// $content .= 

?>


<h1>Gestion des membres</h1>
<p><a href="?action=affichage">Afficher les membres inscrits</a></p>

<?= $content; ?>



<br><br><br><br><br><br><br>
<?php require_once '../inc/footer.inc.php'; ?>