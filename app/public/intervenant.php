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
$test= $specificINTid = $page->getIntintervenant($page->session->getID());
//var_dump($test);

switch ($action) {

    case 'intervention':
        $tableData = [
            'intervention'=>$test,
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

$columnNames = ['intervention ID', 'date', 'titre', 'heure','adresse','client', 'standardiste', 'urgence', 'statut', 'intervenant'];


echo $page->render('intervenant.html.twig', [
    'NomPrenom' => $user_nom . " " . $user_prenom,
    'columnNames' => $columnNames,
    'tableData' => $tableData,
    'action' => $action,
    'specificAction'=> $specificINTid
]);