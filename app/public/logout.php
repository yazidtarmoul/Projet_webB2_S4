<?php

require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();

if (!$page->session->isConnected()) {
    header('Location: index.php');
    exit();
}

$page->session->destroy();
header('Location: index.php');
