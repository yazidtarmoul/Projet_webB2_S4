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

$specificINTid = $page->GetInt($page->session->getID(), 'standID');
//var_dump($specificINTid);
//var_dump($page->session->getID());


switch ($action) {
    case 'supprimer':
        $id = (int) $_GET['id'];
        //var_dump($id);
        $tablename = $_GET['tbname'];
        $colname = $_GET['colname'];
        $page->delete($id, $tablename ,$colname);
        $action = $tablename;
        if ($tablename == "User"){
            $action = 'utilisateurs';
        }
        //var_dump($tablename);
        header("Location: standardiste.php?action=".$tablename);
        break;
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
        break; 

    default:
        break;
}

if (isset($_POST['send'])) {
    if ($_POST['form_type'] == 'intervention') {
        $titre = filter_var($_POST['titre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $adresse = filter_var($_POST['adresse'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $IDclient= filter_var($_POST['ID_client'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $page->insert('intervention', [
                'titre'=> $titre,
                'clientID'=>$IDclient,
                'adresse'=>$adresse,
        ]);
    }
    header("Location: standardiste.php?action=".$_GET['action']);
    exit;
}

if (!empty($tableData)) {
    $columnNames = array_keys($tableData);
}


echo $page->render('standardiste.html.twig', [
    'NomPrenom' => $user_nom . " " . $user_prenom,
    'columnNames' => $columnNames,
    'tableData' => $tableData,
    'action' => $action,
    'specificAction'=> $specificINTid
]);

