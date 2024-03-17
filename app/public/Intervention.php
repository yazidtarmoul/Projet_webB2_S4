<?php

require_once '../vendor/autoload.php';

use App\Page;

$page = new Page();
$msg = null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id !== null) {
$interv = $page->selectIntervention($id);
$urgdeg = $page->selecturgencedeg_intervention($id);
$statut = $page->selctstatut_interv($id);
$comment = $page->selctcmntr_interv($id);
$commentTexts = [];
foreach ($comment as $cmt) {
    $commentTexts[] = $cmt['texte'];
}
echo $page->render('intervention/intervention.html.twig',[
    'idintervention' => $interv['interventionID'],
    'titre'=> $interv['titre'],
    'date'=> $interv['date'],
    'heure'=> $interv['heure'],
    'adresse'=> $interv['adresse'],
    'codepostal'=> $interv['codepostal'],
    'ville'=> $interv['ville'],
    'pays'=> $interv['pays'],
    'urgence'=> $urgdeg['type_urgence'],
    'description'=> $urgdeg['description'],
    'statut'=> $statut['typeStatut'],
    'comments'=> $commentTexts
]);
}