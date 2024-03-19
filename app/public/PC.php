<?php

require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();
$msg = null;
echo $page->render('home/PC.html.twig', ['msg'=> $msg]);