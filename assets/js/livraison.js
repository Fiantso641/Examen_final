// Réinitialiser le formulaire après succès
if (window.location.search.includes("success=1")) {
  // Réinitialiser le formulaire
  setTimeout(function () {
    document.getElementById("formLivraison").reset();
    document.getElementById("chiffre_affaire").value = "";

    // Supprimer le paramètre success de l'URL sans recharger la page
    const url = new URL(window.location);
    url.searchParams.delete("success");
    window.history.replaceState({}, "", url);
  }, 100);
}

// Réinitialiser le formulaire
function resetForm() {
  document.getElementById("formLivraison").reset();
  document.getElementById("chiffre_affaire").value = "";
}

// Auto-remplir les coûts quand on sélectionne un véhicule
document.getElementById("vehicule_id").addEventListener("change", function () {
  const option = this.options[this.selectedIndex];
  const cout = option.getAttribute("data-cout");
  if (cout) {
    document.getElementById("cout_vehicule").value = cout;
    calculateCA();
  }
});

// Auto-remplir le salaire quand on sélectionne un chauffeur
document.getElementById("chauffeur_id").addEventListener("change", function () {
  const option = this.options[this.selectedIndex];
  const salaire = option.getAttribute("data-salaire");
  if (salaire) {
    document.getElementById("salaire_chauffeur").value = salaire;
    calculateCA();
  }
});

// Calculer le CA quand on sélectionne un colis
document.getElementById("colis_id").addEventListener("change", function () {
  calculateCA();
});

function calculateCA() {
  const colisSelect = document.getElementById("colis_id");
  const option = colisSelect.options[colisSelect.selectedIndex];
  const poids = parseFloat(option.getAttribute("data-poids")) || 0;
  const prix = parseFloat(option.getAttribute("data-prix")) || 0;

  if (poids && prix) {
    const ca = poids * prix;
    document.getElementById("chiffre_affaire").value = ca.toFixed(2);
  }
}
