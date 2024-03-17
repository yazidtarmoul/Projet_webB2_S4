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
            $sql = "INSERT INTO ".$table_name ." (titre, date, heure, adresse, codepostal, ville, pays, urgence_ID, statutID) VALUES (:titre, :date, :heure, :adresse, :codepostal, :ville, :pays, :urgence_ID, :statutID)";   
            $stmt = $this->link->prepare($sql);
            $stmt->bindParam(":titre", $data["titre"]);
            $stmt->bindParam(":date", $data["date"]);
            $stmt->bindParam(":heure", $data["heure"]);
            $stmt->bindParam(":adresse", $data["adresse"]);
            $stmt->bindParam(":codepostal", $data["codepostal"]);
            $stmt->bindParam(":ville", $data["ville"]);
            $stmt->bindParam(":pays", $data["pays"]);
            $stmt->bindParam(":statutID", $data["statutID"]);
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
    public function selectidstatuttype(String $statutid){
        $sql1 = "SELECT statutID FROM statut where typeStatut=:Typec";
        $stmt1 = $this->link->prepare($sql1);
        $stmt1->execute(['Typec' => $statutid]);
        $type = $stmt1->fetch(\PDO::FETCH_ASSOC);
        return $type;

    }
    
    //Pour formprofile et page profile
    
    public function updateUser(string $table_name, array $newUserData) {
        $userId = $this->session->get('id');
        $setClause = '';
        
        foreach ($newUserData as $key => $value) {
            if (array_key_exists($key, $newUserData)) {
                $setClause .= $key . ' = :' . $key . ', ';
            }
        }
        
        // Suppression de la virgule en fin de chaîne
        $setClause = rtrim($setClause, ', ');
        
        $sql = "UPDATE $table_name SET " . $setClause . " WHERE id = :userID";
        
        try {
            $this->link->beginTransaction();
        
            $stmt = $this->link->prepare($sql);
        
            foreach ($newUserData as $key => $value) {
                if (array_key_exists($key, $newUserData)) {
                    if ($key !== 'image') { // Si ce n'est pas l'image, liez normalement
                        $stmt->bindValue(':' . $key, $value);
                    } else { // Si c'est l'image, liez le nom de l'image
                        $stmt->bindValue(':image', $value['name']);
                    }
                }
            }
            
            $stmt->bindValue(':userID', $userId);
        
            $stmt->execute();
        
            $this->link->commit();
        } catch (\PDOException $e) {
            $this->link->rollBack();
            throw new \Exception( $e->getMessage());
        }
    }
    public function selectUser(int $userId) {
        $sql ='SELECT `nom`, `prenom`, `email`, `image`,`sexe`, `telephone`, `adressepostal`, `ville`, `codepostal`, `pays`, `linkedin`, `twitter`, `insta`, `fb` 
        FROM users WHERE id = :id';
           $stmt = $this->link->prepare($sql);
           $stmt->execute(['id' => $userId]);
           $donn = $stmt->fetch(\PDO::FETCH_ASSOC);
           return $donn;
       }
       
    
    public function getAllStatus(){
        $sql = 'SELECT * FROM statut';
        $stmt = $this->link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function selectIntervention(int $userId) {
           $sql = 'SELECT `interventionID`, `titre`, `date`, `heure`, `adresse`, `codepostal`, `codepostal`, `ville`, `codepostal`,`pays`
           FROM intervention WHERE interventionID = :id';
           $stmt = $this->link->prepare($sql);
           $stmt->execute(['id'=> $userId]);
           $donn = $stmt->fetch(\PDO::FETCH_ASSOC);
           return $donn;
    } 
    public function selecturgencedeg_intervention(int $userId){
        $sql = "SELECT intervention.urgence_ID , urgence_deg.type_urgence AS type_urgence, urgence_deg.description AS description FROM intervention LEFT JOIN urgence_deg ON  urgence_deg.urgence_ID = intervention.urgence_ID WHERE intervention.interventionID =:id ";
        $stmt = $this->link->prepare($sql);
        $stmt->execute(['id'=> $userId]);
        $urgdeg = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $urgdeg;
    }  
    
    public function selctstatut_interv(int $userId) {
        $sql="SELECT intervention.statutID, statut.typeStatut AS typeStatut
        FROM intervention
        LEFT JOIN statut ON intervention.statutID = statut.statutID
        WHERE intervention.interventionID =:id";
        $stmt = $this->link->prepare($sql);
        $stmt->execute(["id"=> $userId]);
        $statut = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $statut;
    }
    public function selctcmntr_interv(int $userId){
        $sql= "SELECT texte FROM commentaire  WHERE commentaire.interventionID = :id";
        $stmt = $this->link->prepare($sql);
        $stmt->execute(["id"=> $userId]);
        $comment = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $comment;
    }
    public function insert_cmnt(string $table_name, array $data) {
        if ($this->link) {
        $sql = "INSERT INTO " . $table_name . "(texte,interventionID) values (:commentaire, :id)";
        $stmt = $this->link->prepare($sql);
        $stmt->bindParam("commentaire", $data["commentaire"]);
        $stmt->bindParam("id", $data["id"]);
        try{
            $stmt->execute();

        }catch( \PDOException $e){
              throw new \Exception($e->getMessage());
        }
    }
}
       
    public function delete(int $id, String $table_name, String $colname){
        $sql = "DELETE FROM ".$table_name." WHERE ".$colname."= :id";
        $stmt = $this->link->prepare($sql);
        try {
            $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
     public function getInterventionIDs() {
        $sql = "SELECT interventionID FROM intervention";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getDates() {
        $sql = "SELECT date FROM intervention";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
 
    public function getTitre() {
        $sql = "SELECT titre FROM intervention";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getHeure() {
        $sql = "SELECT heure FROM intervention";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getAdresse() {
        $sql = "SELECT adresse FROM intervention";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getClient() {
        $sql = "SELECT intervention.clientID, users.nom AS client 
                FROM intervention
                LEFT JOIN users ON intervention.clientID = users.id";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getStand() {
        $sql = "SELECT intervention.standID, users.nom AS stand
                FROM intervention
                LEFT JOIN users ON intervention.standID = users.id";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getUrgence() {
        $sql = "SELECT intervention.urgence_ID, urgence_deg.type_urgence AS type_urgence
                FROM intervention
                LEFT JOIN urgence_deg ON intervention.urgence_ID = urgence_deg.urgence_ID";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getStatut() {
        $sql = "SELECT intervention.statutID, statut.typeStatut AS typeStatut
                FROM intervention
                LEFT JOIN statut ON intervention.statutID = statut.statutID";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getUserID(){
        $sql = "SELECT id FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserEmail(){
        $sql = "SELECT email FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserNom(){
        $sql = "SELECT nom FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserPrenom(){
        $sql = "SELECT prenom FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserRole(){
        $sql = "SELECT role FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserTel(){
        $sql = "SELECT telephone FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserPays(){
        $sql = "SELECT pays FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserVille(){
        $sql = "SELECT ville FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserDate1(){
        $sql = "SELECT created_at FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserDate2(){
        $sql = "SELECT updated_at FROM users";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getUserIntervention(){
        $sql = "SELECT intervention.interventionID
                 FROM intervention 
                 JOIN users ON intervention.clientID = users.id;";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
        
}