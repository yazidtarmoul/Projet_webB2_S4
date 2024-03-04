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

    // Insérer d'abord l'urgence
    $urgenceData = [
        'type_urgence' => $urgence,
        'description' => $description,
    ];
    $Page->intert_urgence('urgence_deg', $urgenceData);

    // Récupérer l'ID de l'urgence nouvellement insérée
    $urgence_ID = $Page->link->lastInsertId();

    // Insérer ensuite l'intervention avec l'ID d'urgence approprié
    $interventionData = [
        'titre' => $titre,
        'date' => $date,
        'heure' => $heure,
        'adresse' => $adresse,
        'codepostal' => $codepostal,
        'pays' => $pays,
        'ville' => $ville,
        'urgence_ID' => $urgence_ID
    ];
    $Page->insert_form('intervention', $interventionData);

    // Rediriger après l'insertion
    header("Location: profile.php");
    exit;
}

// Afficher le formulaire
echo $Page->render('form_intervention.html.twig', ["msg" => $msg]);
?>
