<?php

/**
 * Classe Contrôleur des requêtes de l'interface admin
 * 
 */

class Admin extends Routeur {

    private $entite;
    private $action;
    private $film_id;

    private $oUtilisateur;
    private $oAuthUtilisateur;

    private $methodes = [
        'film' => [
            'l' => 'listerFilms',
            'a' => 'ajouterFilm',
            'm' => 'modifierFilm',
            's' => 'supprimerFilm'
        ],
        'utilisateur' => [
            'd'           => 'deconnecter',
            'l'           => 'listerUtilisateurs',
            'a'           => 'ajouterUtilisateur',
            'm'           => 'modifierUtilisateur',
            's'           => 'supprimerUtilisateur',
            'generer_mdp' => 'genererMdp'
        ],
    ];

    private $classRetour = "fait";
    private $messageRetourAction = "";

    /**
     * Constructeur qui initialise des propriétés à partir du query string
     * et la propriété oRequetesSQL déclarée dans la classe Routeur
     *
     */
    public function __construct() {
        $this->entite  = $_GET['entite']  ?? 'film';
        $this->action  = $_GET['action']  ?? 'l';
        $this->film_id = $_GET['film_id'] ?? null;
        $this->utilisateur_id = $_GET['utilisateur_id'] ?? null;
        $this->oRequetesSQL = new RequetesSQL;
    }

    /**
     * Gérer l'interface d'administration 
     */  
    public function gererAdmin() {
        //$oAuthUtilisateur = $oUtilisateur;
        if (isset($_SESSION['oAuthUtilisateur'])) {
            $this->oAuthUtilisateur = $_SESSION['oAuthUtilisateur'];
        
            if (isset($this->methodes[$this->entite])) {
                // l'entité existe dans le tableau $this->methodes
        
                if (isset($this->methodes[$this->entite][$this->action])) {
                    // l'action existe dans le tableau $this->methodes pour cette entité
        
                    // la méthode associée à l'action de cette entité peut donc être exécutée
                    $methode = $this->methodes[$this->entite][$this->action];
                    $this->$methode();
        
                } else {
                    throw new Exception("L'action $this->action de l'entité $this->entite n'existe pas.");
                }
        
            } else {
                throw new Exception("L'entité $this->entite n'existe pas.");
            }      
        
        } else {
            $this->connecter();
        }
    }

    /* GESTION DES UTILISATEURS 
       ======================== */
    /**
     * Connecter un utilisateur
     */
    public function connecter() {
        $messageErreurConnexion = ""; 
        if (count($_POST) !== 0) {
        $oAuthUtilisateur = $oUtilisateur = $this->oRequetesSQL->connecter($_POST);
        
        if ($oAuthUtilisateur !== false) {
            $_SESSION['oAuthUtilisateur'] = $oAuthUtilisateur;
            $this->oAuthUtilisateur = $_SESSION['oAuthUtilisateur'];
            $this->listerFilms();
            exit;         
        } else {
            $messageErreurConnexion = "Courriel ou mot de passe incorrect.";
        }
        }
        $oAuthUtilisateur = $oUtilisateur = new Utilisateur;
        
        new Vue('vAdminUtilisateurConnecter',
                array(
                'titre'                  => 'Connexion',
                'oUtilisateur'           => $oUtilisateur,
                'oAuthUtilisateur'       => $oAuthUtilisateur,
                'messageErreurConnexion' => $messageErreurConnexion
                ),
                'gabarit-admin-min');
    }

    /**
     * Déconnecter un utilisateur
     */
    public function deconnecter() {
        unset ($_SESSION['oAuthUtilisateur']);
        $this->connecter();
    }


    /**
     * Lister les Utilisateurs
     * 
     */
    public function listerUtilisateurs() {
            $oUtilisateurs = $this->oRequetesSQL->getUtilisateurs();

        new Vue('vAdminUtilisateurs',
                array(
                'titre'               => 'Gestion des Utilisateurs',
                'oUtilisateurs'       => $oUtilisateurs,
                'oUtilisateur'        => $this->oUtilisateur,
                'oAuthUtilisateur'    => $this->oAuthUtilisateur,
                'classRetour'         => $this->classRetour,  
                'messageRetourAction' => $this->messageRetourAction
                ),
                'gabarit-admin');
    }

    /**
     * Ajouter un Utilisateur
     * 
     */
    public function ajouterUtilisateur() {
        if (count($_POST) !== 0) {
            // retour de la saisie du formulaire
            $oUtilisateur = new Utilisateur($_POST);   // création d'un objet Utilisateur pour alimenter et contrôler tous les champs saisis dans les propriétés correspondantes  
            $erreurs = $oUtilisateur->erreurs;  // récupération de la propriété "tableau des erreurs issues des contrôles des setters"
            
            if (count($erreurs) === 0) {
                //verification de la présence de cet courriel  
                $verification = $this->oRequetesSQL->verifierCourriel($oUtilisateur->utilisateur_courriel);
                if ($verification !== false) {
                    $this->classRetour = 'erreur';
                    $this->messageRetourAction = "Un utilisateur avec courriel $verification déjà exist.";
                    $this->listerUtilisateurs();
                    exit;
                } else {
                     $retour = $this->oRequetesSQL->ajouterUtilisateur([
                        'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
                        'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
                        'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
                        'utilisateur_mdp'      => $oUtilisateur->randomPassword(),
                        'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
                    ]);                    
                    if (preg_match('/^[1-9]\d*$/', $retour)) {
                        $oUtilisateur->envoyerMdp($oUtilisateur);
                        $this->messageRetourAction = "Ajout du utilisateur numéro $retour effectué.";
                        
                    } else {
                        $this->classRetour = 'erreur';
                        $this->messageRetourAction = "Ajout du utilisateur numéro $retour non effectué.";
                    }
                    // retour sur la page de liste avec ou sans erreur
                    $this->listerUtilisateurs();
                    exit;
                }
            }
        } else {
            // initialisations pour le premier chargement du formulaire
            $erreurs = [];
            $oUtilisateur = new Utilisateur;
        }

        new Vue('vAdminUtilisateurAjouter',
                array(
                'titre'            => 'Ajouter un utilisateur',
                'oUtilisateur'     => $this->oUtilisateur,
                'oAuthUtilisateur' => $this->oAuthUtilisateur
                ),
                'gabarit-admin');
    }
    


    /**
     * Modifier un utilisateur
     * 
     */
    public function modifierUtilisateur() {
        if (!preg_match('/^\d+$/', $this->utilisateur_id)) 
            throw new Exception("Numéro d'utilisateur non renseigné pour une modification");
            
        if (count($_POST) !== 0) {
            // retour de la saisie du formulaire  
            $oUtilisateur = new Utilisateur($_POST);   // création d'un objet Utilisateur pour alimenter et contrôler tous les champs saisis dans les propriétés correspondantes  
            $erreurs = $oUtilisateur->erreurs;  // récupération de la propriété "tableau des erreurs issues des contrôles des setters"
           
            if (count($erreurs) === 0) {
                if($this->oRequetesSQL->modifierUtilisateur ([
                    'utilisateur_id'       => $oUtilisateur->utilisateur_id,
                    'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
                    'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
                    'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
                    'utilisateur_profil'   => $oUtilisateur->utilisateur_profil ])) {

                    $this->messageRetourAction = "Modification d'utilisateur numéro $this->utilisateur_id effectuée.";
                } else {
                    $this->classRetour = "erreur";
                    $this->messageRetourAction = "Modification d'utilisateur numéro $this->utilisateur_id non effectuée.";
                }
                // retour sur la page de liste avec ou sans erreur
                $this->listerUtilisateurs();
                exit;
            }
        } else {
            // initialisations pour le premier chargement du formulaire avec les données du utilisateur à modifier
            $oUtilisateur = $this->oRequetesSQL->getUtilisateur($this->utilisateur_id);
        }
        
        new Vue('vAdminUtilisateurModifier',
                array(
                'titre'   => "Modifier l'utilisateur numéro $this->utilisateur_id",
                'oUtilisateur'   => $oUtilisateur,
                'oAuthUtilisateur' => $this->oAuthUtilisateur
                ),
                'gabarit-admin');
    }
    
    /**
     * Supprimer un utilisateur
     * 
     */
    public function supprimerUtilisateur() {

        if (!preg_match('/^\d+$/', $this->utilisateur_id))
            throw new Exception("Numéro d'utilisateur incorrect pour une suppression.");

        if ($this->oRequetesSQL->supprimerUtilisateur($this->utilisateur_id)) {
            $this->messageRetourAction = "Suppression d'utilisateur numéro $this->utilisateur_id effectuée.";
        } else {
            $this->classRetour = "erreur";
            $this->messageRetourAction = "Suppression d'utilisateur numéro $this->utilisateur_id non effectuée.";
        }
        // retour sur la page de liste avec ou sans erreur
        $this->listerUtilisateurs();
    }

    /**
     * Generer un mot de passe d'utilisateur
     * 
     */
    public function genererMdp() {
        if (!preg_match('/^\d+$/', $this->utilisateur_id))
        throw new Exception("Numéro d'utilisateur incorrect pour une génération.");
        
        $oUtilisateur = new Utilisateur;
        if($this->oRequetesSQL->changerMdpUtilisateur ([
            'utilisateur_id'  => $this->utilisateur_id,
            'utilisateur_mdp' => $oUtilisateur->randomPassword()
        ])) { 
            $oUtilisateur->envoyerMdp($oUtilisateur);
            $this->messageRetourAction = "Modification d'utilisateur numéro $this->utilisateur_id effectuée.";
        } else {
            $this->classRetour = "erreur";
            $this->messageRetourAction = "Modification d'utilisateur numéro $this->utilisateur_id non effectuée.";
        }
        // retour sur la page de liste avec ou sans erreur
        $this->listerUtilisateurs();
    }


    /* GESTION DES FILMS 
     ================== */

    /**
     * Lister les films
     * 
     */
    public function listerFilms() {
        $oFilms = $this->oRequetesSQL->getFilms('admin');

        new Vue('vAdminFilms',
                array(
                'titre'               => 'Gestion des films',
                'oFilms'              => $oFilms,
                'classRetour'         => $this->classRetour,  
                'messageRetourAction' => $this->messageRetourAction,
                'oUtilisateur'        => $this->oUtilisateur,
                'oAuthUtilisateur'    => $this->oAuthUtilisateur
                ),
                'gabarit-admin');
    }

    /**
     * Ajouter un film
     * 
     */
    public function ajouterFilm() {
        if (count($_POST) !== 0) {
            // retour de la saisie du formulaire
            $oFilm = new Film($_POST);   // création d'un objet Film pour alimenter et contrôler tous les champs saisis dans les propriétés correspondantes  
            $oFilm->setFilm_affiche($_FILES['film_affiche']['name']); //pour contrôler le suffixe
            
            $erreurs = $oFilm->erreurs;  // récupération de la propriété "tableau des erreurs issues des contrôles des setters"
            
            if (count($erreurs) === 0) {
                $retour = $this->oRequetesSQL->ajouterFilm([
                'film_titre'        => $oFilm->film_titre,
                'film_duree'        => $oFilm->film_duree,
                'film_annee_sortie' => $oFilm->film_annee_sortie,
                'film_resume'       => $oFilm->film_resume,
                'film_statut'       => $oFilm->film_statut,
                'film_genre_id'     => $oFilm->film_genre_id
                ]);                
                if (preg_match('/^[1-9]\d*$/', $retour)) {
                    $this->messageRetourAction = "Ajout du film numéro $retour effectué.";
                } else {
                    $this->classRetour = 'erreur';
                    $this->messageRetourAction = "Ajout du film numéro $retour non effectué.";
                }
                // retour sur la page de liste avec ou sans erreur
                $this->listerFilms();
                exit;
            }
        } else {
            // initialisations pour le premier chargement du formulaire
            $erreurs = [];
            $oFilm = new Film;
        }

        // récupération de tous les genres pour alimenter la select dans le formulaire
        $oGenres = $this->oRequetesSQL->getGenres();

        new Vue('vAdminFilmAjouter',
                array(
                'titre'   => 'Ajouter un film',
                'oUtilisateur' => $this->oUtilisateur,
                'oAuthUtilisateur' => $this->oAuthUtilisateur,
                'oFilm'   => $oFilm,
                'oGenres' => $oGenres
                ),
                'gabarit-admin');
    }

    /**
     * Modifier un film
     * 
     */
    public function modifierFilm() {
        if (!preg_match('/^\d+$/', $this->film_id))
            throw new Exception("Numéro du film non renseigné pour une modification");
            
        if (count($_POST) !== 0) {
            // retour de la saisie du formulaire  
            $oFilm = new Film($_POST);   // création d'un objet Film pour alimenter et contrôler tous les champs saisis dans les propriétés correspondantes  
            $oFilm->setFilm_affiche($_FILES['film_affiche']['name']); //pour contrôler le suffixe
            
            // pour contrôler le format
            if ($_FILES['film_affiche']['tmp_name'] !== "") 
            $oFilm->setFilm_affiche($_FILES['film_affiche']['name']);
            $erreurs = $oFilm->erreurs;  // récupération de la propriété "tableau des erreurs issues des contrôles des setters"
           
            if (count($erreurs) === 0) {
                if ($_FILES['film_affiche']['tmp_name'] !== "") {
                    $oFilm->film_affiche = "medias/affiches/a-$oFilm->film_id.jpg";
                    // tester si ce move s'est bien passé
                    move_uploaded_file($_FILES['film_affiche']['tmp_name'], $oFilm->film_affiche);
                }
                if($this->oRequetesSQL->modifierFilm ([
                    'film_id'           => $oFilm->film_id, 
                    'film_titre'        => $oFilm->film_titre,
                    'film_duree'        => $oFilm->film_duree,
                    'film_annee_sortie' => $oFilm->film_annee_sortie,
                    'film_resume'       => $oFilm->film_resume,
                    'film_statut'       => $oFilm->film_statut,
                    'film_genre_id'     => $oFilm->film_genre_id
                ])) { 
                    $this->messageRetourAction = "Modification du film numéro $this->film_id  effectuée.";
                } elseif ($this->film_id) {
                    $this->messageRetourAction = "Modification du film numéro $this->film_id  effectuée.";
                } else {
                    $this->classRetour = "erreur";
                    $this->messageRetourAction = "Modification du film numéro $this->film_id non effectuée.";
                }
                // retour sur la page de liste avec ou sans erreur
                $this->listerFilms();
                exit;
            }
        } else {
            // initialisations pour le premier chargement du formulaire avec les données du film à modifier
            $oFilm = $this->oRequetesSQL->getFilm($this->film_id);
        }

        // récupération de tous les genres pour alimenter la select dans le formulaire
        $oGenres = $this->oRequetesSQL->getGenres();
        
        new Vue('vAdminFilmModifier',
                array(
                'titre'   => "Modifier le film numéro $this->film_id",
                'oAuthUtilisateur' => $this->oAuthUtilisateur,
                'oFilm'   => $oFilm,
                'oGenres' => $oGenres
                ),
                'gabarit-admin');
    }
    
    /**
     * Supprimer un film
     * 
     */
    public function supprimerFilm() {

        if (!preg_match('/^\d+$/', $this->film_id))
        throw new Exception("Numéro de film incorrect pour une suppression.");

        if ($this->oRequetesSQL->supprimerFilm($this->film_id)) {
            $this->messageRetourAction = "Suppression du film numéro $this->film_id effectuée.";
        } else {
            $this->classRetour = "erreur";
            $this->messageRetourAction = "Suppression du film numéro $this->film_id non effectuée.";
        }
        // retour sur la page de liste avec ou sans erreur
        $this->listerFilms();
    }
}