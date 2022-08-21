<?php

/**
 * Classe de l'entité Genre
 *
 */

class Genre
{
    private $genre_id;
    private $genre_nom;

    private $erreurs = [];

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
    public function getGenre_id()  { return $this->genre_id; }
    public function getGenre_nom() { return $this->genre_nom; }
    public function getErreurs()   { return $this->erreurs; }
    
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
     * Mutateur de la propriété genre_id 
     * @param int $genre_id
     * @return $this
     * 
     */    
    public function setGenre_id($genre_id) {
        unset($this->erreurs['genre_id']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $genre_id)) {
            $this->erreurs['genre_id'] = 'Numéro incorrect.';
        }
        $this->genre_id = $genre_id;
        return $this;
    }    

    /**
     * Mutateur de la propriété genre_nom 
     * @param string $genre_nom
     * @return $this
     * 
     */    
    public function setGenre_nom($genre_nom) {
        unset($this->erreurs['genre_nom']);
        $genre_nom = trim($genre_nom);
        $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
        if (!preg_match($regExp, $genre_nom)) {
            $this->erreurs['genre_nom'] = "Au moins 2 caractères alphabétiques pour chaque mot.";
        }
        $this->genre_nom = $genre_nom;
        return $this;
    }
}