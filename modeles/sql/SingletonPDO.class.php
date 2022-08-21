<?php
 
class SingletonPDO extends PDO
{
    private static $instance = null;

    const DB_SERVEUR  = 'localhost';
    const DB_NOM      = 'cinema';
    const DB_DSN      = 'mysql:host='. self::DB_SERVEUR .';dbname='. self::DB_NOM.';charset=utf8'; 
    const DB_LOGIN    = 'root';
    const DB_PASSWORD = '';

    /**
     * Constructeur qui instancie un objet SingletonPDO dans une propriété $instance, en l'initialisant comme un objet PDO
     */
    private function __construct() {
        $options = [
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION, // Gestion des erreurs par des exceptions de la classe PDOException
            PDO::ATTR_EMULATE_PREPARES  => false                   // Préparation des requêtes non émulée
        ];
        parent::__construct(self::DB_DSN, self::DB_LOGIN, self::DB_PASSWORD, $options);
    }

    /**
     * Méthode __clone inutilisable car private
     */    
    private function __clone (){}  

    /**
     * Instanciation d'un objet SingletonPDO s'il n'existe pas déjà dans la propriété $instance
     * @return object SingletonPDO
     */
    public static function getInstance() {  
        if(is_null(self::$instance))
        {
            self::$instance = new SingletonPDO();
        }
        return self::$instance;
    }
}




