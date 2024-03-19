<?php
require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();

$user = $page->session->get('User');
if ($user) {
    $user_nom = $page->session->getNom();

    $user_prenom = $page->session->getPrenom();
}
$action = isset($_GET['action']) ? $_GET['action'] : '';

$tableData = $columnNames = [];

$specificINTid = $page->GetIntclient($page->session->getID(), 'clientID');
//var_dump($specificINTid);
<<<<<<< HEAD
=======
var_dump($page->session->getID());
>>>>>>> 2a52b7dfe4b159ed97686c5c78a825a8c9f0b1d1

switch ($action) {

    case 'intervention':
        $tableData = [
            'intervention'=>$specificINTid,
            'urgence'=>$page->getUrgence(),
            'statut'=>$page->getStatut(),
            'intervenant'=>$page->intervenantIntervention()
        ];
        $columnNames = ['interventionID', 'date', 'titre', 'heure', 'adresse', 'urgence', 'statut', 'intervenant'];
       // var_dump($tableData);

        break; 
    default:
        break;
}
/*
if (!empty($tableData)) {
    $columnNames = array_keys($tableData);
}*/


echo $page->render('client.html.twig', [
    'NomPrenom' => $user_nom . " " . $user_prenom,
    'columnNames' => $columnNames,
    'tableData' => $tableData,
    'action' => $action,
<<<<<<< HEAD
]);
=======
]);
>>>>>>> 2a52b7dfe4b159ed97686c5c78a825a8c9f0b1d1
