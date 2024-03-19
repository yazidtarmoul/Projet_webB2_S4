<?php

namespace App;

class Session 

{ 
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();  
    }
}
   
    public function add(string $key, $data)
    {
        $_SESSION[$key] = $data;
    }
    public function get(string $key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }
    
    public function destroy()
    {
        unset($_SESSION);
        session_destroy();
    }
    function isConnected()
    {   
        
        return isset($_SESSION['User']);
    }
    public function hasRole(string $role)
    {
        if(!$this->isConnected()){
            return false;
        }
        /*
        if (!isset($_SESSION['user'])){
            return false;
        }*/
        return $_SESSION['user']['role'] == $role ? true : false;
    }
    public function getPrenom(){
        if (!$this->isConnected()){
            return false;
        }
        return $_SESSION['User']['prenom'];
    }

    public function getNom(){
        if (!$this->isConnected()){
            return false;
        }
        return $_SESSION['User']['nom'];
    }
        public function getID(){
        if (!$this->isConnected()){
            return false;
        }
        return $_SESSION['User']['UserID'];
    }
}