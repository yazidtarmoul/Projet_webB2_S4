<?php

    require_once '../vendor/autoload.php';

    use App\Page;
    
    $Page = new Page();
    $msg = null;
    if (isset ($_POST['send'])){
        var_dump($_POST);
        $user = $Page->getUserByEmail($_POST['email']);
        var_dump($user);
        if(!$user){
            $msg = "Email ou mot de passe incorrect !";
        }else{
            if(password_verify($_POST['password'], $user['password'])){
                $msg = "Email ou mot de passe incorrect !";
            } else {
            $msg = "Connected !";
        }
    }
}

    echo $Page->render('index.html.twig', [
        "msg"=> $msg 
    ]);