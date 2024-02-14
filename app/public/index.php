<?php

require_once '../vendor/autoload.php';

use App\Page;

$Page = new Page();
$msg = null;

if (isset($_POST['send'])) {
    //var_dump($_POST);

    if (isset($_POST['email'], $_POST['password'])) {
        $user = $Page->getUserByEmail($_POST['email']);
        //var_dump($user);

        if (!$user) {
            $msg = "Utilisateur ou mot de passe incorrect";
        } else {
            if (!password_verify($_POST['password'], $user['password'])) {
                $msg = "Email ou mot de passe incorrect";
            } else {
                $msg = "Connecté";
                $Page->session->add('User', $user);
                header('Location: profile.php');
            }
        }
    } else {
        $msg = "Veuillez remplir tous les champs du formulaire.";
    }

}
echo $Page->render('index.html.twig', ["msg" => $msg]);
?>
