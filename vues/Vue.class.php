<?php

/**
 * Classe Vue qui gère la génération de toutes les pages html en utilisant le moteur de templates Twig
 * 
 */

class Vue {


  /**
   * Constructeur qui génère et affiche la page html complète associée à la vue
   * --------------------------------------------------------------------------
   * @param string $vue 
   * @param array $donnees, variables à insérer dans la page
   * @param string $gabarit
   */
  public function __construct($vue, $donnees=array(), $gabarit) {

    require_once 'vues/vendor/autoload.php';
    $loader = new \Twig\Loader\FilesystemLoader('vues/templates');
    $twig = new \Twig\Environment($loader, [
      // 'cache' => 'vues/templates/cache',
    ]);
    
    $donnees['templateMain'] = "$vue.html";
    echo $twig->render("$gabarit.html", $donnees);
  }
}