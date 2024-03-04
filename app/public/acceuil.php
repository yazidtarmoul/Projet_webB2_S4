<?php

require_once '../vendor/autoload.php';

use App\Page;
use App\Session;
$Page = new Page();
$Session = new Session();
$msg = null;

echo $Page->render('acceuil.html.twig', ["msg" => $msg]);

