<?php

/**
 * Classe de l'entité Pays
 *
 */

class Pays
{
    private $pays_id;
    private $pays_nom;

    private $erreurs = array();

    /**
     * Constructeur de la classe 
     * @param array $proprietes, tableau associatif des propriétés 
     * 
     */ 
    public function __construct($proprietes = []) {
        $t = array_keys($proprietes);
        foreach ($t as $nom_propriete) {
            $this->__set($nom_propriete, $proprietes[$nom_propriete]);
        } 
    }

    /**
     * Hydratation des propriétés de la classe sans passer par les setters ()
     * quand les données sont sûres car elles proviennent de la base de données 
     * @param array $proprietes, tableau associatif des propriétés
     *  
     */ 
    public function hydrater($proprietes = []) {
        foreach ($proprietes as $nom_propriete => $val_propriete) {
            $this->$nom_propriete = $val_propriete;
        } 
    }
    
    /**
     * Accesseur magique d'une propriété de l'objet
     * @param string $prop, nom de la propriété
     * @return property value
     * 
     */     
    public function __get($prop) {
        return $this->$prop;
    }

    // Getters explicites nécessaires au moteur de templates TWIG pour accéder aux propriétés private
    public function getPays_id()  { return $this->pays_id; }
    public function getPays_nom() { return $this->pays_nom; }
    public function getErreurs()  { return $this->erreurs; }
    
    /**
     * Mutateur magique qui exécute le mutateur de la propriété en paramètre 
     * @param string $prop, nom de la propriété
     * @param $val, contenu de la propriété à mettre à jour
     * 
     */   
    public function __set($prop, $val) {
        $setProperty = 'set'.ucfirst($prop);
        $this->$setProperty($val);
    }

    /**
     * Mutateur de la propriété pays_id 
     * @param int $pays_id
     * @return $this
     * 
     */    
    public function setPays_id($pays_id) {
        unset($this->erreurs['pays_id']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $pays_id)) {
            $this->erreurs['pays_id'] = 'Numéro incorrect.';
        }
        $this->pays_id = $pays_id;
        return $this;
    }    

    /**
     * Mutateur de la propriété pays_nom 
     * @param string $pays_nom
     * @return $this
     * 
     */    
    public function setPays_nom($pays_nom) {
        unset($this->erreurs['pays_nom']);
        $pays_nom = trim($pays_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $pays_nom)) {
            $this->erreurs['pays_nom'] = "Au moins 2 caractères alphabétiques pour chaque mot.";
        }
        $this->pays_nom = $pays_nom;
        return $this;
    }
}