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

$specificINTid = $page->GetInt($page->session->getID(), 'inter_ID');
//var_dump($specificINTid);
//var_dump($page->session->getID());
<<<<<<< HEAD
$test= $specificINTid = $page->getIntintervenant($page->session->getID());
//var_dump($test);
=======
>>>>>>> 2a52b7dfe4b159ed97686c5c78a825a8c9f0b1d1

switch ($action) {

    case 'intervention':
        $tableData = [
<<<<<<< HEAD
            'intervention'=>$test,
=======
            'interventionIDs' => $page->getInterventionIDs(),
            'dates' => $page->getDates(),
            'titre' => $page->getTitre(),
            'heure' => $page->getHeure(),
            'adresse' => $page->getAdresse(),
            'client' => $page->getClient(),
            'stand' => $page->getStand(),
            'urgence' => $page->getUrgence(),
            'statut' => $page->getStatut(),
>>>>>>> 2a52b7dfe4b159ed97686c5c78a825a8c9f0b1d1
            'intervenant'=>$page->intervenantIntervention()
        ];
        //var_dump($tableData);

        break; 

    default:
        break;
}
/*
if (isset($_POST['send'])) {
    if ($_POST['form_type'] == 'intervention') {
        $commentaire = filter_var($_POST['commentaire'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $statut = filter_var($_POST['statut'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $page->insert('intervention', [
                'commentaire'=> $commentaire,
                'statut'=>$statut,
        ]);
    }
    header("Location: intervenant.php?action=".$_GET['action']);
    exit;
}*/

<<<<<<< HEAD
$columnNames = ['intervention ID', 'date', 'titre', 'heure','adresse','client', 'standardiste', 'urgence', 'statut', 'intervenant'];
=======
if (!empty($tableData)) {
    $columnNames = array_keys($tableData);
}
>>>>>>> 2a52b7dfe4b159ed97686c5c78a825a8c9f0b1d1


echo $page->render('intervenant.html.twig', [
    'NomPrenom' => $user_nom . " " . $user_prenom,
    'columnNames' => $columnNames,
    'tableData' => $tableData,
    'action' => $action,
    'specificAction'=> $specificINTid
<<<<<<< HEAD
]);
=======
]);
>>>>>>> 2a52b7dfe4b159ed97686c5c78a825a8c9f0b1d1
