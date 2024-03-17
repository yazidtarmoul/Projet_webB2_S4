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
                $Page->session->add('id', $user['id']);

                
                header("Location: Edit.php");
                exit(); 
            }
        }
    } else {
        $msg = "Veuillez remplir tous les champs du formulaire.";
    }
    
}

if($Page->session->isConnected() &&  $Page->session->isConnected()=== true)
{ 
    header("Location: acceuil.php");
    exit();
}

echo $Page->render('index.html.twig', ["msg" => $msg]);
