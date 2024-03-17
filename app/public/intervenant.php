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

switch ($action) {

    case 'intervention':
        $tableData = [
            'interventionIDs' => $page->getInterventionIDs(),
            'dates' => $page->getDates(),
            'titre' => $page->getTitre(),
            'heure' => $page->getHeure(),
            'adresse' => $page->getAdresse(),
            'client' => $page->getClient(),
            'stand' => $page->getStand(),
            'urgence' => $page->getUrgence(),
            'statut' => $page->getStatut(),
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

if (!empty($tableData)) {
    $columnNames = array_keys($tableData);
}


echo $page->render('intervenant.html.twig', [
    'NomPrenom' => $user_nom . " " . $user_prenom,
    'columnNames' => $columnNames,
    'tableData' => $tableData,
    'action' => $action,
    'specificAction'=> $specificINTid
]);
