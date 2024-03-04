<?php
require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();

$user = $page->session->get('users');
$action = isset($_GET['action']) ? $_GET['action'] : '';

$tableData = $columnNames = [];

switch ($action) {
    case 'interventions':
        $tableData = [
            'interventionIDs' => $page->getInterventionIDs(),
            'dates' => $page->getDates(),
            'titre' => $page->getTitre(),
            'heure' => $page->getHeure(),
            'adresse' => $page->getAdresse(),
            'client' => $page->getClient(),
            'stand' => $page->getStand(),
            'urgence' => $page->getUrgence(),
            'statut' => $page->getStatut()
        ];
        //var_dump($tableData);

        break; 

    case 'utilisateurs':
        $tableData = [
            'UserID'=> $page->getUserId(),
            'UserEmail'=>$page->getUserEmail(),
            'UserNom'=>$page->getUserNom(),
            'UserPrenom'=>$page->getUserPrenom(), 
            'UserRole'=>$page->getUserRole(), 
            'UserTel'=>$page->getUserTel(),
            'UserPays'=>$page->getUserPays(),
            'UserVille'=>$page->getUserVille(), 
            'UserCreated'=>$page->getUserDate1(),
            'UserUpdated'=>$page->getUserDate2(),
            'UserIntervention'=>$page->getUserIntervention()
        ];
        //var_dump($tableData);
    case 'ajouter':
        

        break; 

    default:
        break;
}

if (!empty($tableData)) {
    $columnNames = array_keys($tableData);
}

echo $page->render('admin/admin.html.twig', [
    //'NomPrenom' => $user_nom . " " . $user_prenom,
    'columnNames' => $columnNames,
    'tableData' => $tableData,
    'action' => $action 
]);