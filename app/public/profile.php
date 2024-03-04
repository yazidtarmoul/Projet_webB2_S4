<?php

require_once '../vendor/autoload.php';

use App\Page;
$page = new Page();
$userId = $page->session->get('id');
$donn = $page->selectUser($userId);
echo $page->render('profile/pageprofile.html.twig', ["nom" => $donn['nom'],
                                              "prenom"=>$donn['prenom'],
                                              "email"=>$donn['email'],
                                              "image"=>$donn['image'],
                                              "telephone"=>$donn['telephone'],
                                              "adresse_post"=>$donn['adressepostal'],
                                              "ville"=>$donn['ville'],
                                              "codepostal"=>$donn['codepostal'],
                                              "pays"=>$donn['pays'],
                                               "linkedin"=>$donn['linkedin'],
                                               "twitter"=>$donn['twitter'],
                                               "insta"=>$donn['insta'],
                                               "fb"=>$donn['fb']
   ]);
echo $page->render('acceuil.html.twig', ['nom'=> $donn['nom'],
                                         'prenom'=>$donn['prenom']]);