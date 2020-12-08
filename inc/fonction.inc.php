<?php
// fonction de débugage : debug() permet d'effectuer un print "amélioré"
function debug( $arg ){
    echo '<div style="background:#fda500; z-index: 1000; padding:15px">';

    $trace = debug_backtrace();
    // debug_backtrace() : foonction interne de php qui retourne un array contenant des infos.

    echo "Debug demandé dans le fichier : <strong>" . $trace[0]['file'] . '</strong> à la ligne <strong>' . $trace[0]['line'] . '</strong>';

        print '<pre>';
            print_r($arg);
        print '</pre>';

    echo '</div>';
}

$ok = array('ok', 'ouioui', 'popo');

// debug($ok);

// fonction execute_requete() : permet d'effectuer une requête

function execute_requete($req){

    global $pdo;
    $r = $pdo->query($req);

    return $r;

}

$r = execute_requete('SELECT * FROM membre');

// debug($r);

// Fonction userConnect() : si l'internaute est connecté.

function userConnect(){

    if(!isset($_SESSION['membre'])){
    // Si la session membre n'existe pas, on n'est donc pas connecté. La fonction renvoie donc false.
    // On crée et remplit la session membre lors de la connexion.
        return false;
    }
    else{
        // Sinon, la session membre existe, on est dc connecté.
        return true;
    }
}

// Fonction adminConnect() : si l'internaute est connecté ET qu'il est administrateur.

function adminConnect(){

    if(userConnect() && $_SESSION['membre']['statut'] == 1){ 
        // Si l'internaute est connecté et qu'il est admin (donc q son statut vaut 1)
        return true;
    }
    else{
        return false;
    }

}

// Fonction pour créer un panier : 
function creation_panier(){
    if(!isset($_SESSION['panier'])){
        // SI la session panier n'existe pas, on la crée.
        $_SESSION['panier'] = array();
        // Création d'une session panier

            $_SESSION['panier']['titre'] = array();
            $_SESSION['panier']['id_produit'] = array();
            $_SESSION['panier']['quantite'] = array();
            $_SESSION['panier']['prix'] = array();
    }
}

// Fonction d'ajout d'un produit dans le panier
function ajout_panier($titre, $id_produit, $quantite, $prix){
    creation_panier();
    // Ici, on fait appel à la fonction déclarée au-dessus. 
        // SOIT le panier n'existe pas et on le crée (LA première fois qu'on tente d'ajouter un produit à notre panier)
        // SOIT il existe, et donc on l'utilise.

    $index = array_search($id_produit, $_SESSION['panier']['id_produit']);
    // array_search(arg1, arg2);
        // arg1 : ce que l'on cherche
        // arg2 : ds ql tableau on ft la rech.
    // Valeur de retour : la fonction renverra la "clé" (correspondant à l'indice du tableau SI il y a une correspondance) ou "false".

    if($index !== false){
        // Si $index est dft de false, c' q le produit est dà prst ds le panier.

        $_SESSION['panier']['quantite'][$index] += $quantite;
        // Ici, on va précément à l'indice du prdt dà prst ds le panier et on y ajoute la nvl quantité.

    }
    else{
        // Sinon, c'est q le prdt n' p ds le panier, dc on insère toutes les info nécessaires
    $_SESSION['panier']['titre'][] = $titre;
    $_SESSION['panier']['id_produit'][] = $id_produit;
    $_SESSION['panier']['quantite'][] = $quantite;
    $_SESSION['panier']['prix'][] = $prix;
    }
}

// Création d'une fonction pour le montant total
function montant_total(){
    $total = 0;
    for($i=0; $i<sizeof($_SESSION['panier']['id_produit']); $i++){
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return $total;
}

// Création d'une fonction pour retirer un produit du panier
function retirer_produit_panier($id_produit_a_supprimer){
    $index = array_search($id_produit_a_supprimer, $_SESSION['panier']['id_produit']);

    if($index !== false){
        // Si le produit existe
        array_splice($_SESSION['panier']['titre'], $index, 1);
        array_splice($_SESSION['panier']['id_produit'], $index, 1);
        array_splice($_SESSION['panier']['quantite'], $index, 1);
        array_splice($_SESSION['panier']['prix'], $index, 1);

        // array_splice(arg1, arg2, arg3) Permet de supprimer un/des élements d'un tableau :
            // arg1 : le tableau où on va fr une suppression
            // arg2 : l'élément qu'on cherche à supprimer
            // arg3 : le nbr d'élément qu'on vt suppr (à partir de l'indice $arg2)

    }

}