<?php

/**
 * Classe de l'entité Utilisateur
 *
 */
class Utilisateur
{
    private $utilisateur_id;
    private $utilisateur_nom;
    private $utilisateur_prenom;
    private $utilisateur_courriel;
    private $utilisateur_mdp;
    private $utilisateur_profil;

    const PROFIL_ADMINISTRATEUR = "administrateur";
    const PROFIL_EDITEUR =        "editeur";
    const PROFIL_UTILISATEUR =    "utilisateur";

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
     * hydratation des propriétés de la classe sans passer par les setters ()
     * quand les données sont sûres car elles proviennent de la base de données 
     * @param array $proprietes, tableau associatif des propriétés 
     */ 
    public function hydrater($proprietes = []) {
        foreach ($proprietes as $nom_propriete => $val_propriete) {
            $this->$nom_propriete = $val_propriete;
        }
        return $this; 
    }
    
    /**
     * Accesseur magique d'une propriété de l'objet
     * @param string $prop, nom de la propriété
     * @return property value
     */     
    public function __get($prop) {
        return $this->$prop;
    }

    public function getUtilisateur_id()       { return $this->utilisateur_id; }
    public function getUtilisateur_nom()      { return $this->utilisateur_nom; }
    public function getUtilisateur_prenom()   { return $this->utilisateur_prenom; }
    public function getUtilisateur_courriel() { return $this->utilisateur_courriel; }
    public function getUtilisateur_mdp()      { return $this->utilisateur_mdp; }
    public function getUtilisateur_profil()   { return $this->utilisateur_profil; }
    public function getErreurs()              { return $this->erreurs; }
    
    /**
     * Mutateur magique qui exécute le mutateur de la propriété en paramètre 
     * @param string $prop, nom de la propriété
     * @param $val, contenu de la propriété à mettre à jour    
     */   
    public function __set($prop, $val) {
        $setProperty = 'set'.ucfirst($prop);
        $this->$setProperty($val);
    }

    /**
     * Mutateur de la propriété utilisateur_id 
     * @param int $utilisateur_id
     * @return $this
     */    
    public function setUtilisateur_id($utilisateur_id) {
        unset($this->erreurs['utilisateur_id']);
        $regExp = '/^\d+$/';
        if (!preg_match($regExp, $utilisateur_id)) {
            $this->erreurs['utilisateur_id'] = 'Numéro incorrect.';
        }
        $this->utilisateur_id = $utilisateur_id;
        return $this;
    }    

    /**
     * Mutateur de la propriété utilisateur_nom 
     * @param string $utilisateur_nom
     * @return $this
     */    
    public function setUtilisateur_nom($utilisateur_nom) {
        unset($this->erreurs['utilisateur_nom']);
        $utilisateur_nom = trim($utilisateur_nom);
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $utilisateur_nom)) {
            $this->erreurs['utilisateur_nom'] = 'Au moins un caractère.';
        }
        $this->utilisateur_nom = $utilisateur_nom;
        return $this;
    }

    /**
     * Mutateur de la propriété utilisateur_prenom 
     * @param string $utilisateur_prenom
     * @return $this
     */    
    public function setUtilisateur_prenom($utilisateur_prenom) {
        unset($this->erreurs['utilisateur_prenom']);
        $utilisateur_prenom = trim($utilisateur_prenom);
        $regExp = '/^.+$/';
        if (!preg_match($regExp, $utilisateur_prenom)) {
            $this->erreurs['utilisateur_prenom'] = 'Au moins un caractère.';
        }
        $this->utilisateur_prenom = $utilisateur_prenom;
        return $this;
    }

    /**
     * Mutateur de la propriété utilisateur_courriel
     * @param string $utilisateur_courriel
     * @return $this
     */    
    public function setUtilisateur_courriel($utilisateur_courriel) {
        unset($this->erreurs['utilisateur_courriel']);
        $utilisateur_courriel = trim(strtolower($utilisateur_courriel));
        if (!filter_var($utilisateur_courriel, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs['utilisateur_courriel'] = 'Courriel incorrect.';
        } 
        $this->utilisateur_courriel = $utilisateur_courriel;
        return $this;
    }

    /**
     * Mutateur de la propriété utilisateur_profil
     * @param string $utilisateur_profil
     * @return $this
     */    
    public function setUtilisateur_profil($utilisateur_profil) {
        unset($this->erreurs['utilisateur_profil']);
        if ($utilisateur_profil != Utilisateur::PROFIL_ADMINISTRATEUR &&
            $utilisateur_profil != Utilisateur::PROFIL_EDITEUR   && 
            $utilisateur_profil != Utilisateur::PROFIL_UTILISATEUR) {
            $this->erreurs['utilisateur_profil'] = 'Profil incorrect.';
        }
        $this->utilisateur_profil = $utilisateur_profil;
        return $this;
    }

    /**
     * Fonction de génération utilisateur_mdp
     * @param string $utilisateur_mdp
     * @return $this
     *///https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php#6101969
    public function randomPassword() {
        $alphabet = '!@#$%?&*abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $mdp = implode($pass); //turn the array into a string
        return $this->utilisateur_mdp = $mdp;
    }

    /**
     * Fonction fonction d'envoi de courriel avec mot de passe
     * @param string Utilisateur $oUtilisateur
     * @return $this
     */
    public function envoyerMdp($oUtilisateur) {
        $mdp = $oUtilisateur->utilisateur_mdp;
        //echo 'mdp = '.$mdp;//debug
        $destinataire = $oUtilisateur->utilisateur_courriel;
        $objetCourriel = 'Votre mot de passe :'.$mdp;
        $message = '<p>Nous avons le plaisir de vous transmettre votre identifiant de connexion et
                    votre mot de passe pour accéder à l\'administration de notre site Le Méliès.</p>
                    
                    <p>Identifiant : '.$oUtilisateur->utilisateur_courriel.'</p>
                    <p>Mot de passe : '.$mdp.'</p>
                    <p><a href="admin?entite=utilisateur&action=l">
                    Cliquez sur ce lien pour accéder à notre interface d\'administration</a></p>
                    <p>Cordialement</p>
                    <p>L\'équipe du Méliès</p>';
       
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: Le Méliès <support@lemelies.com>' . "\r\n";
 
       mail($destinataire, $objetCourriel, $message, $headers);
    }
}
