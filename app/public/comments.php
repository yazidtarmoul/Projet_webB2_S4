<?php
require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();
$msg = null;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (isset($_POST['Send'])){
    $commentaire = $_POST['Commentaire'] ?? '';
    $datacomnt = [
        'commentaire'=> $commentaire,
        'id'=> $id
    ];
    $page->insert_cmnt('commentaire', $datacomnt);
    header('Location: admin.php');
    
}




 echo $page->render('admin/commentaire.html.twig',[
                                                  'msg'=>$msg]);