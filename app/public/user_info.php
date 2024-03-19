<?php
require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();

$id = (int) $_GET['id'];
$userinfo = $page->getUserByID($id);
//var_dump($userinfo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $telephone = $_POST['telephone'];
    $pays = $_POST['pays'];
    $ville = $_POST['ville'];


    $page->updateUser2($userinfo, [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'role' => $role,
        'ville'=>$ville,
        'pays'=>$pays,
        'telephone'=>$telephone,
    ]);
    header("Location: admin.php?action = utilisateur");
}

echo $page->render('user_info.html.twig', [
    'tableData' => $userinfo
]); 
