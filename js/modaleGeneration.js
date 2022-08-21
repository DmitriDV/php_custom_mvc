document.querySelectorAll('.confirmerG').forEach(e => e.onclick = afficherFenetreModale);

/**
 * Affichage d'une fenêtre modale
 */
function afficherFenetreModale() {
    let locationHref = () => {location.href = this.dataset.href};
    let annuler      = () => {document.getElementById('modaleGeneration').close()}; 
    document.querySelector('#modaleGeneration .OK').onclick = locationHref;
    document.querySelector('#modaleGeneration .KO').onclick = annuler;
    document.getElementById('modaleGeneration').showModal();
    document.querySelector('#modaleGeneration .focus').focus();
}
