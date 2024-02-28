<?php

require_once '../vendor/autoload.php';

use App\Page;

$Page = new Page();
$msg = null;

if (isset($_POST['envoyer'])) {
    $titre = $_POST['titre_intervention_form'] ?? '';
    $date = $_POST['date_intervnetion'] ?? '';
    $heure = $_POST['heure_dispo'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $codepostal = $_POST['codepostal'] ?? '';
    $pays = $_POST['pays'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $description = $_POST['description'] ?? '';
    $urgence = $_POST['urgence'] ?? '';
    $Page->insert_form('intervention', [
        'titre' => $titre,
        'date' => $date,
        'heure' => $heure,
        'adresse' => $adresse,
        'codepostal' => $codepostal,
        'pays' => $pays,
        'ville' => $ville
    ]); 
    
    $Page->intert_urgence('urgence_deg', [
        'type_urgence' => $urgence,
        'description' => $description,
    ]); 
    header("Location: profile.php");
    exit;

    
}

echo $Page->render('form_intervention.html.twig' ,["msg" => $msg]);