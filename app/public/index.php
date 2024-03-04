<?php

require_once '../vendor/autoload.php';
use App\Page;
use App\Session;
$Page = new Page();
$Session = new Session();
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
                

                
                header("Location: formprofile.php");
                exit(); 
            }
        }
    } else {
        $msg = "Veuillez remplir tous les champs du formulaire.";
    }
    
}

if($Session->isConnected())
{ 
    header("Location: acceuil.php");
    exit();
}

echo $Page->render('index.html.twig', ["msg" => $msg]);
