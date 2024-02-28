<?php

require_once '../vendor/autoload.php';

use App\Page;

$Page = new Page();
$msg = null;

echo $Page->render('acceuil.html.twig', ["msg" => $msg]);

