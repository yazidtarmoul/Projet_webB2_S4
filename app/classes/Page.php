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
    
}

