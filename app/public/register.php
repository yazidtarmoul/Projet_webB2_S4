<?php

    require_once '../vendor/autoload.php';

    use App\Page;
    $msg = NULL;
    
    $Page = new Page();
    if(isset($_POST['send'])){
        if($_POST['password'] !== $_POST['passwordcfg']) {
            $msg1 = "Les mots de passe ne correspondent pas.";
        }else if($Page->getUserByEmail($_POST['email'])){
                $msg1 = "CE COMPTE EXISTE DEJA";
        }else{
       $Page->insert('users',[
           'email'    => $_POST['email'],
           'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
           
       ]);
       $msg1 = "Compte created";
       header( "refresh:5; url=index.html.twig" ); 
    }
}

    echo $Page->render('register.html.twig', ["msg" => $msg]);
?>