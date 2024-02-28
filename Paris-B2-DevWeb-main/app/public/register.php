<?php

    require_once '../vendor/autoload.php';

    use App\Page;
    
    $Page = new Page();
    if(isset($_POST['send'])){
       //var_dump($_POST);
       $Page->insert('users',[
           'email'    => $_POST['email'],
           'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
       ]);
       header('location: index.php');
    }

    echo $Page->render('register.html.twig', []);