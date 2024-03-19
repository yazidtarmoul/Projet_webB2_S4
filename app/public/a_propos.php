<?php

require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();
$msg = null;
echo $page->render('home/a_propos.html.twig', ['msg'=> $msg]);