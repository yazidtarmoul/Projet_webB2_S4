<?php
require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();
$userId = $page->session->get('id');
$donn = $page->selectUser($userId);

/*$action = $_GET['action'] ?? '';
if ($action == 'edit') {
    

     header('Location: formprofile.php');
     exit();
}*/

if (isset($_POST['enregistrer'])) {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $mail = $_POST['mail'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $adresse_post = $_POST['adresse_post'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $codepostal = $_POST['codepostal'] ?? '';
    $pays = $_POST['pays'] ?? '';
    $image = $_FILES['image'] ?? '';
    $sexe = $_POST['sexe'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';
    $twitter = $_POST['twitter'] ?? '';
    $insta = $_POST['insta'] ?? '';
    $fb = $_POST['fb'] ?? '';

    $userdonneesData = [
        'id' => $userId,
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $mail,
        'telephone' => $telephone,
        'adressepostal' => $adresse_post,
        'ville' => $ville,
        'codepostal' => $codepostal,
        'pays' => $pays,
        'image' => $image,
        'sexe' => $sexe,
        'linkedin' => $linkedin,
        'twitter' => $twitter,
        'insta' => $insta,
        'fb' => $fb
    ];

    
    $page->updateUser('users', $userdonneesData);

    header("Location: profile.php");
    exit;
}

echo $page->render('profile/formprofile.html.twig', [
    "nom" => $donn['nom'],
    "prenom" => $donn['prenom'],
    "email" => $donn['email'],
    "image" => $donn['image'], 
    "telephone" => $donn['telephone'],
    "adresse_post" => $donn['adressepostal'],
    "ville" => $donn['ville'],
    "codepostal" => $donn['codepostal'],
    "pays" => $donn['pays'],
    "linkedin" => $donn['linkedin'],
    "twitter" => $donn['twitter'],
    "insta" => $donn['insta'],
    "fb" => $donn['fb']
]);