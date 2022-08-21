<?php

/**
 * Classe de l'entité Realisateur
 *
 */

class Realisateur
{
    private $realisateur_id;
    private $realisateur_nom;
    private $realisateur_prenom;

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
    public function getRealisateur_id()     { return $this->realisateur_id; }
    public function getRealisateur_nom()    { return $this->realisateur_nom; }
    public function getRealisateur_prenom() { return $this->realisateur_prenom; }
    public function getErreurs()            { return $this->erreurs; }
    
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
     * Mutateur de la propriété realisateur_id 
     * @param int $realisateur_id
     * @return $this
     * 
     */    
    public function setRealisateur_id($realisateur_id) {
        unset($this->erreurs['realisateur_id']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $realisateur_id)) {
            $this->erreurs['realisateur_id'] = 'Numéro incorrect.';
        }
        $this->realisateur_id = $realisateur_id;
        return $this;
    }    

    /**
     * Mutateur de la propriété realisateur_nom 
     * @param string $realisateur_nom
     * @return $this
     * 
     */    
    public function setRealisateur_nom($realisateur_nom) {
        unset($this->erreurs['realisateur_nom']);
        $realisateur_nom = trim($realisateur_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $realisateur_nom)) {
            $this->erreurs['realisateur_nom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->realisateur_nom = $realisateur_nom;
        return $this;
    }

    /**
     * Mutateur de la propriété realisateur_prenom 
     * @param string $realisateur_prenom
     * @return $this
     * 
     */    
    public function setRealisateur_prenom($realisateur_prenom) {
        unset($this->erreurs['realisateur_prenom']);
        $realisateur_prenom = trim($realisateur_prenom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $realisateur_prenom)) {
            $this->erreurs['realisateur_prenom'] = 'Au moins 2 caractères alphabétiques pour chaque mot.';
        }
        $this->realisateur_prenom = $realisateur_prenom;
        return $this;
    }
}