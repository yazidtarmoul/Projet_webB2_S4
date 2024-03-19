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
    public function insert(string $table_name, array $data){
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO " . $table_name . " (" . $columns . ") VALUES (" . $placeholders . ")";

        $stmt = $this->link->prepare($sql);
        $stmt->execute($data);
    }
    public function updateUser2(array $currentUser, array $newUserData) {
        $setClause = '';

        foreach ($newUserData as $key => $value) {
            if (array_key_exists($key, $currentUser)) {
                $setClause .= $key . ' = :' . $key . ', ';
            }
        }
        // Supp virgule de fin
        $setClause = rtrim($setClause, ', ');
        $sql = "UPDATE users SET " . $setClause . " WHERE id = :userID";
        try {
            $this->link->beginTransaction();
            $stmt = $this->link->prepare($sql);

            foreach ($newUserData as $key => $value) {
                if (array_key_exists($key, $currentUser)) {
                    $stmt->bindValue(':' . $key, $value);
                }
            }
            $stmt->bindValue(':userID', $currentUser['id']);
            $stmt->execute();
            $this->link->commit();
        } catch ( \PDOException $e) {
            $this->link->rollBack();
            throw new \Exception("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
        }
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
    public function selectidstatuttype(String $statutid){
        $sql1 = "SELECT statutID FROM statut where typeStatut=:Typec";
        $stmt1 = $this->link->prepare($sql1);
        $stmt1->execute(['Typec' => $statutid]);
        $type = $stmt1->fetch(\PDO::FETCH_ASSOC);
        return $type;

    }
    public function selecturgencetype(String $urgneceid){
        $sql1 = "SELECT urgence_ID FROM urgence_deg where type_urgence=:Typec";
        $stmt1 = $this->link->prepare($sql1);
        $stmt1->execute(['Typec' => $urgneceid]);
        $type1 = $stmt1->fetch(\PDO::FETCH_ASSOC);
        return $type1;

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
        public function getAllUrgence(){
        $sql = 'SELECT * FROM urgence_deg';
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
        $sql="SELECT intervention.urgence_ID, urgence_deg.type_urgence AS type_urgence
        FROM intervention
        LEFT JOIN urgence_deg ON intervention.urgence_ID = urgence_deg.urgence_ID
        WHERE intervention.interventionID =:id";
        $stmt = $this->link->prepare($sql);
        $stmt->execute(["id"=> $userId]);
        $urgence = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $urgence;
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
    /*
    public function selectclient(){
        $sql= "SELECT id,nom,prenom from users where role = client";
        $stmt = $this->link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function selectintervenant(){
        $sql= "SELECT id,nom,prenom from users where role = intervenant";
        $stmt = $this->link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }*/
       
    public function delete(int $id, string $table_name, string $colname) {
        try {
            // Démarrer une transaction
            $this->link->beginTransaction();
            
            // Supprimer d'abord les commentaires associés à l'intervention
            if ($table_name === 'intervention') {
                $sqlComments = "DELETE FROM commentaire WHERE interventionID = :id";
                $stmtComments = $this->link->prepare($sqlComments);
                $stmtComments->execute(['id' => $id]);
            }
            
            // Ensuite, supprimer l'intervention elle-même
            $sql = "DELETE FROM $table_name WHERE $colname = :id";
            $stmt = $this->link->prepare($sql);
            $stmt->execute(['id' => $id]);
    
            // Valider les modifications
            $this->link->commit();
        } catch (\PDOException $e) {
            // En cas d'erreur, annuler les modifications
            $this->link->rollBack();
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
                LEFT JOIN urgence_deg ON intervention.urgence_ID = urgence_deg.urgence_ID
                ORDER BY intervention.ordre";
        $statement = $this->link->prepare($sql); 
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getStatut() {
        $sql = "SELECT intervention.statutID, statut.typeStatut AS typeStatut
                FROM intervention
                LEFT JOIN statut ON intervention.statutID = statut.statutID
                ORDER BY intervention.ordre";
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
    public function getUserById(int $userId) {
        $sql = "SELECT * FROM users WHERE id = :userId";
        $stmt = $this->link->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user;
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

        public function GetInt(int $ID, String $table_name){
        $sql = "SELECT interventionID FROM intervention WHERE intervention.".$table_name."= :id";
        $stmt = $this->link->prepare($sql);
        try {
            $res = $stmt->execute(['id' => $ID]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function GetIntclient(int $ID, String $table_name){
        $sql = "SELECT interventionID, date, titre, heure, adresse, inter_ID, clientID, standID, urgence_ID, statutID
            FROM intervention
            WHERE ".$table_name." = :id";
        $stmt = $this->link->prepare($sql);
        try {
            $res = $stmt->execute(['id' => $ID]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function intervenantIntervention() {   
        $sql = "SELECT i.interventionID, GROUP_CONCAT(CONCAT(u.nom, ' ', u.prenom)) as users 
                FROM intervenant i 

                JOIN users u ON i.id = u.id GROUP BY i.interventionID;";

    
        $stmt = $this->link->prepare($sql);
        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function getIntintervenant(int $ID){
        $sql ="SELECT intervention.interventionID, 
                intervention.date, 
                intervention.titre, 
                intervention.heure, 
                intervention.adresse, 
                intervention.clientID, 
                intervention.urgence_ID,
                intervention.statutID,
                client.nom AS nom_client,
                urgence_deg.type_urgence,  
                statut.typeStatut          
                FROM intervention
                INNER JOIN intervenant ON intervention.interventionID = intervenant.interventionID
                INNER JOIN users ON intervenant.id = users.id
                LEFT JOIN urgence_deg ON intervention.urgence_ID = urgence_deg.urgence_ID  
                LEFT JOIN statut ON intervention.statutID = statut.statutID
                LEFT JOIN users AS client ON intervention.clientID = client.id 

                
                WHERE users.id = :id
                ORDER BY intervention.ordre";
            
        $stmt = $this->link->prepare($sql);
        $stmt->bindParam(':id', $ID, \PDO::PARAM_INT);
        try {
            $res = $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
          
}
