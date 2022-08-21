<?php
namespace mocks;

/**
 * Classe DateTime qui émule la classe standard PHP
 */

class DateTime {

    private $instance; // instance d'un objet de la classe DateTime standard

    /**
     * Constructeur qui crée un objet de la classe DateTime standard
     * dans la propriété $instance en forçant la date MOCK_NOW si $date = 'now'
     * @param string $date (mot-clé ou format valide)
     * 
     */
    public function __construct($date = "now") {
        if ($date === "now") $date = MOCK_NOW; 
        $this->instance = new \DateTime($date);
    }  

    /**
     * Méthode magique __call() qui récupère les appels de méthodes inaccessibles
     * exécutera par exemple les méthodes format et modify
     * Ces méthodes sont en fait des méthodes de l'objet DateTime standard, qu'il faut exécuter
     * @param $name   = nom de la méthode inaccessible,
     *        $params = paramètres associés à l'appel de cette méthode    
     * @return retour de la méthode de l'instance \DateTime
     * 
     */
    function __call($name, $params) {
        return $this->instance->$name(...$params);
    }
}