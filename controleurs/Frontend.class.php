<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */

class Frontend extends Routeur {

    private $film_id;
    
    /**
     * Constructeur qui initialise des propriétés à partir du query string
     * et la propriété oRequetesSQL déclarée dans la classe Routeur
     * 
     */
    public function __construct() {
        $this->film_id = $_GET['film_id'] ?? null; 
        $this->oRequetesSQL = new RequetesSQL;
    }


    /**
     * Lister les films à l'affiche
     * 
     */  
    public function listerAlaffiche() {
        $oFilms = $this->oRequetesSQL->getFilms('enSalle');
        new Vue("vListeFilms",
                array(
                'titre'  => "À l'affiche",
                'oFilms' => $oFilms
                ),
                "gabarit-frontend");
    }

    /**
     * Lister les films à l'affiche
     * 
     */  
    public function listerProchainement() {
        $oFilms = $this->oRequetesSQL->getFilms('prochainement');
        new Vue("vListeFilms",
                array(
                'titre'  => "Prochainement",
                'oFilms' => $oFilms
                ),
                "gabarit-frontend");
    }

    /**
     * Voir les informations d'un film
     * 
     */  
    public function voirFilm() {
        $oFilm = new Film;
        if (!is_null($this->film_id)) {
            $oFilm = $this->oRequetesSQL->getFilm($this->film_id);
            $oRealisateurs = $this->oRequetesSQL->getRealisateursFilm($this->film_id);
            $oPays         = $this->oRequetesSQL->getPaysFilm($this->film_id);
            $oActeurs      = $this->oRequetesSQL->getActeursFilm($this->film_id);
            $oSeances      = $this->oRequetesSQL->getSeancesFilm($this->film_id);
        }
        new Vue("vFilm",
                array(
                'titre'         => $oFilm->film_titre,
                'oFilm'         => $oFilm,
                'oRealisateurs' => $oRealisateurs,
                'oPays'         => $oPays,
                'oActeurs'      => $oActeurs,
                'oSeances'      => $oSeances
                ),
                "gabarit-frontend");
    }
}