<?php

/**
 * Classe de l'entité Acteur
 *
 */

class Acteur
{
    private $acteur_id;
    private $acteur_nom;
    private $acteur_prenom;

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
    public function getActeur_id()     { return $this->acteur_id; }
    public function getActeur_nom()    { return $this->acteur_nom; }
    public function getActeur_prenom() { return $this->acteur_prenom; }
    public function getErreurs()       { return $this->erreurs; }
    
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
     * Mutateur de la propriété acteur_id 
     * @param int $acteur_id
     * @return $this
     * 
     */    
    public function setActeur_id($acteur_id) {
        unset($this->erreurs['acteur_id']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $acteur_id)) {
            $this->erreurs['acteur_id'] = 'Numéro incorrect.';
        }
        $this->acteur_id = $acteur_id;
        return $this;
    }    

    /**
     * Mutateur de la propriété acteur_nom 
     * @param string $acteur_nom
     * @return $this
     * 
     */    
    public function setActeur_nom($acteur_nom) {
        unset($this->erreurs['acteur_nom']);
        $acteur_nom = trim($acteur_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $acteur_nom)) {
            $this->erreurs['acteur_nom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->acteur_nom = $acteur_nom;
        return $this;
    }

    /**
     * Mutateur de la propriété acteur_prenom 
     * @param string $acteur_prenom
     * @return $this
     * 
     */    
    public function setActeur_prenom($acteur_prenom) {
        unset($this->erreurs['acteur_prenom']);
        $acteur_prenom = trim($acteur_prenom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $acteur_prenom)) {
            $this->erreurs['acteur_prenom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->acteur_prenom = $acteur_prenom;
        return $this;
    }
}