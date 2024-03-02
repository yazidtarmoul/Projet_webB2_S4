<?php

namespace App;

class Page
{
    private \Twig\Environment $twig;
    public $link;
    public $session;

    function __construct()
    {  
        $this->session = new Session();
        $loader = new \Twig\Loader\FilesystemLoader('../templates');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => '../var/cache/compilation_cache',
            'debug' => true 
        ]);
        $this->link = new \PDO('mysql:host=mysql;dbname=B2-paris',"root","");
    }
    public function insert(string $table_name,array$data)
    {
        $sql = "insert into ".$table_name." (email,password) VALUES (:email, :password)";   
        $stmt = $this->link->prepare($sql);
        $stmt->execute($data); 
    }
    public function getUserByEmail(string $Email)
    {
        $sql = 'SELECT  * FROM users WHERE email = :email';
        $stmt = $this->link->prepare($sql);
        $stmt->execute(['email' => $Email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user;
    }
    
    function render(string $name, array $data) :string
    {
        return $this->twig->render($name, $data);
    }

    //pour form_intervention.php
    
    public function insert_form(string $table_name, array $data) {
        if ($this->link) {
            $sql = "INSERT INTO ".$table_name ." (titre, date, heure, adresse, codepostal, ville, pays, urgence_ID) VALUES (:titre, :date, :heure, :adresse, :codepostal, :ville, :pays, :urgence_ID)";   
            $stmt = $this->link->prepare($sql);
            $stmt->bindParam(":titre", $data["titre"]);
            $stmt->bindParam(":date", $data["date"]);
            $stmt->bindParam(":heure", $data["heure"]);
            $stmt->bindParam(":adresse", $data["adresse"]);
            $stmt->bindParam(":codepostal", $data["codepostal"]);
            $stmt->bindParam(":ville", $data["ville"]);
            $stmt->bindParam(":pays", $data["pays"]);
            $stmt->bindParam(":urgence_ID", $data["urgence_ID"]);
    
            try {
                $stmt->execute($data);
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }
    
    public function intert_urgence(string $table_name, array $data1) {
        if ($this->link) { 
            $sql1 = "INSERT INTO ". $table_name ." (type_urgence, description) VALUES (:urgence, :description)";
            $stmt2 = $this->link->prepare($sql1);
            $stmt2->bindParam(":urgence", $data1["type_urgence"]);
            $stmt2->bindParam(":description", $data1["description"]);
    
            try {
                $stmt2->execute();
            } catch (\PDOException $e) {
                var_dump($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        }
    }
    
    //Pour formprofile et page profile
    
    public function update_user(string $table_name, array $data2) {
        if ($this->link) { 
            $sql3 = "UPDATE ".$table_name." SET nom = :name, 
                             prenom = :lastname, 
                             email = :mail, 
                             image = :image, 
                             telephone = :telephone,
                             adressepostal = :adresse_post,
                             ville = :ville,
                             codepostal = :codepostal,
                             pays = :pays,
                             linkedin = :linkedin,
                             twitter = :twitter,
                             insta = :insta,
                             fb = :fb WHERE id = :user_ID"; 
            $stmt3 = $this->link->prepare($sql3);
            $stmt3->bindParam(":name", $data2["nom"]);    
            $stmt3->bindParam(":lastname", $data2["prenom"]);    
            $stmt3->bindParam(":mail", $data2["email"]);    
            $stmt3->bindParam(":image", $data2["image"]);    
            $stmt3->bindParam(":telephone", $data2["telephone"]);    
            $stmt3->bindParam(":adresse_post", $data2["adressepostal"]);  
            $stmt3->bindParam(":ville", $data2["ville"]);  
            $stmt3->bindParam(":codepostal", $data2["codepostal"]);    
            $stmt3->bindParam(":pays", $data2["pays"]);    
            $stmt3->bindParam(":linkedin", $data2["linkedin"]);    
            $stmt3->bindParam(":twitter", $data2["twitter"]);    
            $stmt3->bindParam(":insta", $data2["insta"]);    
            $stmt3->bindParam(":fb", $data2["fb"]);    
            $stmt3->bindParam(":user_ID", $data2["id"]);  
    
            try {
                $stmt3->execute(); 
            } catch (\PDOException $e) {
                var_dump($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        }
    }
    
}