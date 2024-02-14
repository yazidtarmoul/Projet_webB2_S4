<?php
require_once '../vendor/autoload.php';

use App\Page();
$Page = new Page();
var_dump($page->session->get('users'));
?> 