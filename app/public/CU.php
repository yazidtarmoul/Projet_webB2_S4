<?php

require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();
$msg = null;

echo $page->render('home/CU.html.twig', ['msg'=> $msg]);