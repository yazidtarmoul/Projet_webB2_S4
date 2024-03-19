<?php

require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();
$msg = null;


if (!$page->session->isConnected()) {
    header("Location: index.php");
    exit();
}

$userId = $page->session->get('id');
$donn = $page->selectUser($userId);

echo $page->render('home/factu.html.twig', ['nom'=> $donn['nom'],
                                         'prenom'=>$donn['prenom']]);