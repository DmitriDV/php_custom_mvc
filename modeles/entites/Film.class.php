<?php

/**
 * Classe de l'entité Film
 *
 */

class Film
{
    private $film_id;
    private $film_titre;
    private $film_duree;
    private $film_annee_sortie;
    private $film_resume;
    private $film_affiche;
    private $film_bande_annonce;
    private $film_statut;
    private $film_genre_id;
    private $genre_nom;

    public $nb_realisateurs;
    public $nb_acteurs;
    public $nb_pays;
    
    private $erreurs = array();

    const ANNEE_PREMIER_FILM = 1895;
    const DUREE_MIN = 1;
    const DUREE_MAX = 600;     
    const STATUT_INVISIBLE = 0;
    const STATUT_VISIBLE   = 1;
    const STATUT_ARCHIVE   = 2;

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
    public function getFilm_id()            { return $this->film_id; }
    public function getFilm_titre()         { return $this->film_titre; }
    public function getFilm_duree()         { return $this->film_duree; }
    public function getFilm_annee_sortie()  { return $this->film_annee_sortie; }
    public function getFilm_resume()        { return $this->film_resume; }
    public function getFilm_affiche()       { return $this->film_affiche; }
    public function getFilm_bande_annonce() { return $this->film_bande_annonce; }
    public function getFilm_statut()        { return $this->film_statut; }
    public function getFilm_genre_id()      { return $this->film_genre_id; }
    public function getGenre_nom()          { return $this->genre_nom; }
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

    // ********************************************************************************************************************************************************************************
    // Dans chaque setter :
    // - la propriété est systématiquement mise à jour avec la valeur passée en paramètre, 
    //   pour pouvoir être réaffichée dans les formulaires qui l'utilisent
    // - si la valeur contrôlée est erronée, un message est ajouté dans $this->erreurs pour cette propriété, 
    //   et pourra être affiché dans les formulaires qui saisissent cette propriété 
    //
    // ********************************************************************************************************************************************************************************

    /**
     * Mutateur de la propriété film_id 
     * @param int $film_id
     * @return $this
     * 
     */    
    public function setFilm_id($film_id) {
        unset($this->erreurs['film_id']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $film_id)) {
            $this->erreurs['film_id'] = 'Numéro incorrect.';
        }
        $this->film_id = $film_id;
        return $this;
    }    

    /**
     * Mutateur de la propriété film_titre 
     * @param string $film_titre
     * @return $this
     * 
     */    
    public function setFilm_titre($film_titre) {
        unset($this->erreurs['film_titre']);
        $film_titre = trim($film_titre);
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $film_titre)) {
            $this->erreurs['film_titre'] = 'Au moins un caractère.';
        }
        $this->film_titre = mb_strtoupper($film_titre);
        return $this;
    }

    /**
     * Mutateur de la propriété film_duree 
     * @param int $film_duree, en minutes
     * @return $this
     * 
     */        
    public function setFilm_duree($film_duree) {
        unset($this->erreurs['film_duree']);
        if (!preg_match('/^\d+$/', $film_duree) || $film_duree < self::DUREE_MIN || $film_duree > self::DUREE_MAX) {
            $this->erreurs['film_duree'] = "Entre ".self::DUREE_MIN." et ".self::DUREE_MAX.".";
        }
        $this->film_duree = $film_duree;
        return $this;
    }

    /**
     * Mutateur de la propriété film_annee_sortie 
     * @param int $film_annee_sortie
     * @return $this
     * 
     */        
    public function setFilm_annee_sortie($film_annee_sortie) {
        unset($this->erreurs['film_annee_sortie']);
        if (!preg_match('/^\d+$/', $film_annee_sortie) ||
            $film_annee_sortie < self::ANNEE_PREMIER_FILM  || 
            $film_annee_sortie > date("Y")) {
        $this->erreurs['film_annee_sortie'] = "Entre ".self::ANNEE_PREMIER_FILM." et l'année en cours.";
        }
        $this->film_annee_sortie = $film_annee_sortie;
        return $this;
    }

    /**
     * Mutateur de la propriété film_resume
     * @param string $film_resume
     * @return $this
     * 
     */    
    public function setFilm_resume($film_resume) {
        unset($this->erreurs['film_resume']);
        $film_resume = trim($film_resume);
        $regExp = '/^\S+(\s+\S+){4,}$/';
        if (!preg_match($regExp, $film_resume)) {
            $this->erreurs['film_resume'] = 'Au moins 5 mots.';
        }
        $this->film_resume = $film_resume;
        return $this;
    }

    /**
     * Mutateur de la propriété film_affiche
     * @param string $film_affiche
     * @return $this
     * 
     */    
    public function setFilm_affiche($film_affiche) {
        unset($this->erreurs['film_affiche']);
        $film_affiche = trim($film_affiche);
        $regExp = '/^.+\.jpg$/';
        if (!preg_match($regExp, $film_affiche)) {
            $this->erreurs['film_affiche'] = "Vous devez téléverser un fichier de type jpg";
        }
        $this->film_affiche = $film_affiche;
        return $this;
    }

    /**
     * Mutateur de la propriété film_bande_annonce
     * @param string $film_bande_annonce
     * @return $this
     * 
     */    
    public function setFilm_bande_annonce($film_bande_annonce) {
        unset($this->erreurs['film_bande_annonce']);
        $film_bande_annonce = trim($film_bande_annonce);
        $regExp = '/^.+\.mp4$/';
        if (!preg_match($regExp, $film_bande_annonce) && $film_bande_annonce !== "" ) {
            $this->erreurs['film_bande_annonce'] = true;
        }
        $this->film_bande_annonce = $film_bande_annonce;
        return $this;
    }

    /**
     * Mutateur de la propriété film_statut
     * @param int $film_statut
     * @return $this
     * 
     */        
    public function setFilm_statut($film_statut) {
        unset($this->erreurs['film_statut']);
        if ($film_statut != Film::STATUT_INVISIBLE &&
            $film_statut != Film::STATUT_VISIBLE   && 
            $film_statut != Film::STATUT_ARCHIVE) {
        $this->erreurs['film_statut'] = 'Statut incorrect.';
        }
        $this->film_statut = $film_statut;
        return $this;
    }

    /**
     * Mutateur de la propriété film_genre_id 
     * @param int $film_genre_id
     * @return $this
     * 
     */    
    public function setFilm_genre_id($film_genre_id) {
        unset($this->erreurs['film_genre_id']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $film_genre_id)) {
            $this->erreurs['film_genre_id'] = 'Genre non sélectionné.';
        }
        $this->film_genre_id = $film_genre_id;
        return $this;
    }    
}