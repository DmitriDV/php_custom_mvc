<?php

/**
 * Classe des requêtes SQL
 *
 */

class RequetesSQL extends RequetesPDO {

    /* GESTION DES UTILISATEURS 
        ======================== */

    /**
     * Connecter un utilisateur
     * @param array $champs, tableau avec les champs utilisateur_courriel et utilisateur_mdp  
     * @return object|false objet Utilisateur si trouvé dans la table, false sinon 
     */ 
    public function connecter($champs) {
        $this->sql = "
        SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
        FROM utilisateur
        WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_mdp = SHA2(:utilisateur_mdp, 512)";
        
        $utilisateur = $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
        return $utilisateur !== false ? (new Utilisateur)->hydrater($utilisateur) : false; 
    }  

    /**
     * Récupération de tous les utilisateurs de la table utilisateur
     * @return array tableau d'objets Utilisateur
     */ 
    public function getUtilisateurs() {
        $this->sql = '
        SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel,
            utilisateur_mdp, utilisateur_profil
        FROM utilisateur
        ORDER BY utilisateur_id ASC';
        
        $utilisateurs = $this->getLignes();
        $oUtilisateurs = [];
        foreach ($utilisateurs as $utilisateur) {
            $oUtilisateur = new Utilisateur;
            $oUtilisateur->hydrater($utilisateur);
            $oUtilisateurs[] = $oUtilisateur;
        }
        return $oUtilisateurs;
    }

    /**
     * Récupération d'utilisateur
     * @param int $utilisateur_id 
     * @return object Utilisateur
     * 
     */ 
    public function getUtilisateur($utilisateur_id) {
        $this->sql = '
        SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel,
            utilisateur_mdp, utilisateur_profil
        FROM utilisateur
        WHERE utilisateur_id = :utilisateur_id';

        $utilisateur = $this->getLignes(['utilisateur_id' => $utilisateur_id], RequetesPDO::UNE_SEULE_LIGNE);
        $oUtilisateur = new Utilisateur;
        if ($utilisateur !== false) $oUtilisateur->hydrater($utilisateur); 
        return $oUtilisateur;   
    }


    /**
     * Verification de courriel
     * @param int $utilisateur_courriel  
     * @return object Utilisateur
     * 
     */ 
    public function verifierCourriel($utilisateur_courriel) {
        $this->sql = '
        SELECT * FROM utilisateur WHERE utilisateur_courriel = :utilisateur_courriel';
        $result = $this->getLignes(['utilisateur_courriel' => $utilisateur_courriel], RequetesPDO::UNE_SEULE_LIGNE);
        if($result) return $utilisateur_courriel; 
        else return false;
    }

    /**
     * Ajouter un utilisateur
     * @param array $champs, tableau des champs du utilisateur 
     * @return int|boolean clé primaire de la ligne ajoutée, false sinon
     * 
     */ 
    public function ajouterUtilisateur($champs) {
        //var_dump($champs);exit;//debug
        try {
            $this->debuterTransaction();
            $this->sql = '
            INSERT INTO utilisateur SET
                utilisateur_nom       = :utilisateur_nom,
                utilisateur_prenom    = :utilisateur_prenom,
                utilisateur_courriel  = :utilisateur_courriel,
                utilisateur_mdp       = SHA2(:utilisateur_mdp, 512),
                utilisateur_profil    = :utilisateur_profil';
           
            $utilisateur_id = $this->CUDLigne($champs); 

            $this->validerTransaction();
            return $utilisateur_id;

        } catch (Exception $e) {
            $this->annulerTransaction();
            return $e->getMessage();
        }
    }
    
    /**
     * Modifier un utilisateur
     * @param array $champs, tableau avec les champs à modifier et la clé utilisateur_id
     * @return boolean true si modification effectuée, false sinon
     * 
     */ 
    public function modifierUtilisateur ($champs) { 
        //var_dump($champs);exit;//debug
        $this->sql = 'UPDATE utilisateur SET';
        foreach ($champs as $nom => $valeur) {
            if ($nom != 'utilisateur_id')
            $this->sql .= " $nom = :$nom,";
        }
        $this->sql = substr($this->sql, 0, -1);
        $this->sql .= ' WHERE utilisateur_id = :utilisateur_id'; //var_dump($this->sql);exit;//debug
        return $this->CUDLigne($champs); 
    }

    /**
     * Supprimer un utilisateur
     * @param int $utilisateur_id, clé primaire
     * @return boolean true si suppression effectuée, false sinon
     * 
     */ 
    public function supprimerUtilisateur($utilisateur_id) {
        try {
            $this->debuterTransaction();
            $this->sql = 'DELETE FROM utilisateur WHERE utilisateur_id = :utilisateur_id';
            $this->validerTransaction();
            return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]); 
            
        } catch (Exception $e) {
            $this->annulerTransaction();
            return $e->getMessage();
        }
    }


    /**
     * Générer une mot de passe d'utilisateur
     * @param int $utilisateur_id, clé primaire
     * @return boolean true si suppression effectuée, false sinon
     * 
     */ 
    public function changerMdpUtilisateur($champs) {
        try {
            $this->debuterTransaction();
            $this->sql =    'UPDATE utilisateur 
                            SET utilisateur_mdp = SHA2(:utilisateur_mdp, 512) 
                            WHERE utilisateur_id = :utilisateur_id';
            $this->validerTransaction();
            return $this->CUDLigne($champs); 
        } catch (Exception $e) {
            $this->annulerTransaction();
            return $e->getMessage();
        }
    }


      /* GESTION DES FILMS 
     ================== */
    /**
     * Récupération des films à l'affiche ou prochainement ou pour l'interface admin
     * @param  string $critere, chaine 'enSalle', 'prochainement' ou 'admin'
     * @return array tableau d'objets Film
     * 
     */ 
    public function getFilms($critere = 'enSalle') {
        $oAujourdhui = ENV === "DEV" ? new mocks\DateTime() : new DateTime();
        $aujourdhui  = $oAujourdhui->format('Y-m-d');
        $dernierJour = $oAujourdhui->modify('+6 day')->format('Y-m-d');
        $this->sql = "
        SELECT film_id, film_titre, film_duree, film_annee_sortie, film_resume,
            film_affiche, film_bande_annonce, film_statut, genre_nom";

        if ($critere === 'admin') {
            $this->sql .= ", nb_realisateurs, nb_acteurs, nb_pays";   
        }

        $this->sql .= " FROM film
                        INNER JOIN genre ON genre_id = film_genre_id";

        if ($critere === 'admin') {
            $this->sql .= " LEFT JOIN (SELECT COUNT(*) AS nb_realisateurs, f_r_film_id FROM film_realisateur GROUP BY f_r_film_id) AS FR
                            ON f_r_film_id = film_id
                            LEFT JOIN (SELECT COUNT(*) AS nb_acteurs, f_a_film_id FROM film_acteur GROUP BY f_a_film_id) AS FA
                            ON f_a_film_id = film_id
                            LEFT JOIN (SELECT COUNT(*) AS nb_pays, f_p_film_id FROM film_pays GROUP BY f_p_film_id) AS FP
                            ON f_p_film_id = film_id";
        } else {
            $this->sql .= " WHERE film_statut = ".Film::STATUT_VISIBLE;
            switch($critere) {
                case 'enSalle':
                $this->sql .= " AND film_id IN (SELECT DISTINCT seance_film_id FROM seance
                                                WHERE seance_date >='$aujourdhui' AND seance_date <= '$dernierJour')";
                break;
                case 'prochainement':
                $this->sql .= " AND film_id NOT IN (SELECT DISTINCT seance_film_id FROM seance
                                                    WHERE seance_date <= '$dernierJour')";
                break;
            }      
        }

        if ($critere === 'admin') {
            $this->sql .= " ORDER BY film_id DESC";
        }

        $films = $this->getLignes();
        $oFilms = [];
        foreach ($films as $film) {
            $oFilm = new Film;
            $oFilm->hydrater($film);
            $oFilms[] = $oFilm; 
        } 
        return $oFilms;   
    }

    /**
     * Récupération d'un film
     * @param int $film_id, clé du film  
     * @return object Film
     * 
     */ 
    public function getFilm($film_id) {
        $this->sql = "
        SELECT film_id, film_titre, film_duree, film_annee_sortie, film_resume,
            film_affiche, film_bande_annonce, film_statut, film_genre_id, genre_nom
        FROM film
        INNER JOIN genre ON genre_id = film_genre_id
        WHERE film_id = :film_id";

        $film = $this->getLignes(['film_id' => $film_id], RequetesPDO::UNE_SEULE_LIGNE);
        $oFilm = new Film;
        if ($film !== false) $oFilm->hydrater($film); 
        return $oFilm;   
    }

    /**
     * Récupération du genre d'un film
     * @param int $film_id, clé du film 
     * @return object Genre
     * 
     */ 
    public function getGenreFilm($film_id) {
        $this->sql = "
        SELECT genre_id, genre_nom
        FROM genre
        INNER JOIN film ON film_genre_id = genre_id
        WHERE film_id = :film_id";
    
        $genre = $this->getLignes(['film_id' => $film_id], RequetesPDO::UNE_SEULE_LIGNE);
        $oGenre = new Genre;
        if ($genre !== false) $oGenre->hydrater($genre); 
        return $oGenre; 
    }

    /**
     * Récupération des réalisateurs d'un film
     * @param int $film_id, clé du film 
     * @return array tableau d'objets Realisateur
     * 
     */ 
    public function getRealisateursFilm($film_id) {
        $this->sql = "
        SELECT realisateur_nom, realisateur_prenom
        FROM realisateur
        INNER JOIN film_realisateur ON f_r_realisateur_id = realisateur_id
        WHERE f_r_film_id = :film_id";

        $realisateurs = $this->getLignes(['film_id' => $film_id]);
        $oRealisateurs = [];
        foreach ($realisateurs as $realisateur) {
            $oRealisateur = new Realisateur;
            $oRealisateur->hydrater($realisateur);
            $oRealisateurs[] = $oRealisateur;
        } 
        return $oRealisateurs; 
    }

    /**
     * Récupération des pays d'un film
     * @param int $film_id, clé du film  
     * @return array tableau d'objets Pays
     * 
     */ 
    public function getPaysFilm($film_id) {
        $this->sql = "
        SELECT pays_nom
        FROM pays
        INNER JOIN film_pays ON f_p_pays_id = pays_id
        WHERE f_p_film_id = :film_id";

        $pays = $this->getLignes(['film_id' => $film_id]);
        $oPays = [];
        foreach ($pays as $unPays) {
            $oUnPays = new Pays;
            $oUnPays->hydrater($unPays);
            $oPays[] = $oUnPays;
        } 
        return $oPays; 
    }

    /**
     * Récupération des acteurs d'un film
     * @param int $film_id, clé du film  
     * @return array tableau d'objets Acteur
     * 
     */ 
    public function getActeursFilm($film_id) {
        $this->sql = "
        SELECT acteur_nom, acteur_prenom
        FROM acteur
        INNER JOIN film_acteur ON f_a_acteur_id = acteur_id
        WHERE f_a_film_id = :film_id
        ORDER BY f_a_priorite ASC";

        $acteurs = $this->getLignes(['film_id' => $film_id]);
        $oActeurs = [];
        foreach ($acteurs as $acteur)  {
            $oActeur = new Acteur;
            $oActeur->hydrater($acteur);
            $oActeurs[] = $oActeur;
        } 
        return $oActeurs; 
    }

    /**
     * Récupération des séances d'un film
     * @param int $film_id, clé du film 
     * @return array tableau d'objets Seance
     * 
     */ 
    public function getSeancesFilm($film_id) {
        $oAujourdhui = ENV === "DEV" ? new mocks\DateTime() : new DateTime();
        $aujourdhui  = $oAujourdhui->format('Y-m-d');
        $dernierJour = $oAujourdhui->modify('+6 day')->format('Y-m-d');
        $this->sql = "
        SELECT seance_date, seance_heure
        FROM seance
        INNER JOIN film ON seance_film_id = film_id
        WHERE seance_film_id = :film_id AND seance_date >='$aujourdhui' AND seance_date <= '$dernierJour'";

        $seances = $this->getLignes(['film_id' => $film_id]);
        $oSeances = [];
        foreach ($seances as $seance) {
            $oSeance = new Seance;
            $oSeance->hydrater($seance);
            $oSeances[] = $oSeance;
        } 
        return $oSeances; 
    }

    /**
     * Ajouter un film
     * @param array $champs, tableau des champs du film 
     * @return int|boolean clé primaire de la ligne ajoutée, false sinon
     * 
     */ 
    public function ajouterFilm($champs) {
        //var_dump($champs);exit;//debug
        try {
            $this->debuterTransaction();
            $this->sql = '
            INSERT INTO film SET
                film_titre         = :film_titre,
                film_duree         = :film_duree,
                film_annee_sortie  = :film_annee_sortie,
                film_resume        = :film_resume,
                film_statut        = :film_statut,
                film_genre_id      = :film_genre_id';
           
            $film_id = $this->CUDLigne($champs); 

            $this->modifierFilm (['film_id' => $film_id, 'film_affiche' => "medias/affiches/a-$film_id.jpg"]);
            
            //var_dump(@move_uploaded_file($_FILES['film_affiche']['tmp_name'], "medias/affiches/a-$film_id.jpg"));exit;//debug
            if (!@move_uploaded_file($_FILES['film_affiche']['tmp_name'], "medias/affiches/a-$film_id.jpg"))
                throw new Exception("Le stockage du fichier affiche du film a échoué.");
                $this->validerTransaction();
                return $film_id;
        } catch (Exception $e) {
            $this->annulerTransaction();
            return $e->getMessage();
        }
        
    }
    
    /**
     * Modifier un film
     * @param array $champs, tableau avec les champs à modifier et la clé film_id
     * @return boolean true si modification effectuée, false sinon
     * 
     */ 
    public function modifierFilm ($champs) {
        //var_dump($champs);exit;//debug
            $this->sql = 'UPDATE film SET';
            foreach ($champs as $nom => $valeur) {
                if ($nom != 'film_id') $this->sql .= " $nom = :$nom,";
            }
            $this->sql = substr($this->sql, 0, -1);
            $this->sql .= ' WHERE film_id = :film_id';
            return $this->CUDLigne($champs); 
    }

    /**
     * Supprimer un film
     * @param int $film_id, clé primaire
     * @return boolean true si suppression effectuée, false sinon
     * 
     */ 
    public function supprimerFilm($film_id) {
        try {
            $this->debuterTransaction();
            $this->sql = '
            DELETE FROM film WHERE film_id = :film_id
            AND film_id NOT IN (SELECT DISTINCT f_r_film_id FROM film_realisateur)
            AND film_id NOT IN (SELECT DISTINCT f_a_film_id FROM film_acteur)
            AND film_id NOT IN (SELECT DISTINCT f_p_film_id FROM film_pays)';
            
            $affiche = 'medias/affiches/a-'.$film_id.'.jpg';
            if (unlink($affiche)) $this->validerTransaction();
            return $this->CUDLigne(['film_id' => $film_id]); 
        } catch (Exception $e) {
            $this->annulerTransaction();
            return $e->getMessage();
        }
    }

    /**
     * Récupération des genres
     * @return array tableau d'objets Genre
     * 
     */ 
    public function getGenres() {
        $this->sql = "SELECT genre_id, genre_nom FROM genre";
        $genres = $this->getLignes();
        $oGenres = [];
        foreach ($genres as $genre)  {
            $oGenre = new Genre;
            $oGenre->hydrater($genre);
            $oGenres[] = $oGenre;
        } 
        return $oGenres; 
    }
}