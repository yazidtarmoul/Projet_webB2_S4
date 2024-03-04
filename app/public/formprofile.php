<?php

require_once '../vendor/autoload.php';
use App\Page;
use App\Session;
$Page = new Page();
$Session = new Session();
$msg = null;



$userId = $Page->session->get('id');
 //var_dump($userId);
if (isset($_POST['enregistrer'])) {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $mail = $_POST['mail'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $adresse_post = $_POST['adresse_post'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $codepostal = $_POST['codepostal'] ?? '';
    $pays = $_POST['pays'] ?? '';
    $image = $_POST['image'] ?? '';
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
        'linkedin' => $linkedin,
        'twitter' => $twitter,
        'insta' => $insta,
        'fb' => $fb
    ];

    
    $Page->updateUser('users', $userdonneesData);

    header("Location: profile.php");
    exit;
}

// Render formprofile.html.twig
echo $Page->render('profile/formprofile.html.twig', ["msg" => $msg]);