<?php 

/**
 * Fonction qui s'exécute dès qu'une classe est manipulée pour la première fois par l'application
 * @param string $classe, nom de la classe avec son namespace éventuel 
 *
 */
function chargerClasse($classe) {
  // echo $classe."<br>"; // DEBUG DEV

  if (!stristr($classe, "mocks")) { 
    // $classe ne contient pas le nom du namespace mocks, le fichier correspondant peut donc être recherché dans la structure MVC

    $dossiers = array('modeles/sql/', 'modeles/entites/', 'vues/', 'controleurs/'); 
    foreach ($dossiers as $dossier) {
      if (file_exists('./'.$dossier.$classe.'.class.php')) {
        require_once('./'.$dossier.$classe.'.class.php');
      }
    }

  } else {
    // $classe est dans le namespace mocks et peut donc être chargée directement à partir de son chemin "namespace"

    $classe = str_replace('\\', '/', $classe); // pour la compatibilité avec les paths unix
    require_once($classe . '.class.php');
  }
}

spl_autoload_register('chargerClasse');