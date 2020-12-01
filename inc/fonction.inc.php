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