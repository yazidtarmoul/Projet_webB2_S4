<?php

namespace App;

class Page
{
    private \Twig\Environment $twig;
    private $link;

    function __construct()
    {
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
}