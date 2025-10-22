Guide d'utilisation du système modale

Objectif :
Ce système permet de gérer dynamiquement des modales imbriquées ou parallèles, avec suivi du contexte, retour arrière intelligent, et réouverture automatique du modale initial si nécessaire.

Structure des modales :
Chaque modale doit contenir les attributs suivants :
- data-modalLevel : niveau du modale (1 = racine, 2 = enfant, etc.)
- data-modalGroup : nom logique du groupe (exemple : gestion, utilisateur)
- data-modalBranch : branche parallèle dans le même niveau
- data-modalRequestId (optionnel) : identifiant métier pour cibler un modale précis

Exemple :
<div class="modal" data-modalLevel="1" data-modalGroup="gestion" data-modalBranch="2" data-modalRequestId="user42">

Initialisation :
À inclure une seule fois dans ton script principal :

const modalFlowConfig = createModalFlowConfig({
  initialLevel: null,
  initialGroup: null,
  initialBranch: null,
  manualClose: false,
  autoRestore: true,
  rollbackInProgress: false
});

initModalOpening();
initModalRollback();
initModalCloseWatcher();

Ouverture d’un modale :
Chaque bouton déclencheur doit contenir :

<button data-requestedModalLevel="2" data-modalGroup="gestion" data-modalBranch="1" data-modalRequestId="user42">Voir l’objet</button>

Le script :
- Enregistre le point de retour initial
- Ferme les modales ouvertes
- Ouvre le modale ciblé
- Initialise les tableaux DataTables dans le modale

Retour arrière :
Chaque bouton de retour doit contenir :
<button data-returnToParentModal>Retour</button>

Le script :
- Ferme le modale actuel
- Recherche un modale de niveau inférieur dans le même groupe
- Priorise la branche actuelle, puis décrémente jusqu’à 1
- Si aucun modale trouvé, tente sans branche

Fermeture et réouverture automatique :
Le système détecte :
- Les fermetures volontaires (croix, bouton "Fermer")
- Les fermetures passives (via JS ou clic extérieur)

Si aucune modale reste ouverte et que la fermeture n’est pas volontaire :
- Le modale initial est rouvert automatiquement
- Le suivi est réinitialisé

Cas d’usage typique :
1. Modale de gestion (niveau 1, groupe gestion)
2. → Modale de vue (niveau 2, même groupe, branche 1)
3. → Retour → modale de gestion rouvert
4. → Modale de suppression (niveau 2, branche 2)
5. → Retour → modale de gestion rouvert ✅

Bonnes pratiques :
- Toujours définir un id unique sur les tableaux DataTables
- Utiliser data-emptyrow pour personnaliser le message de tableau vide
- Ne jamais réinitialiser modalFlowConfig manuellement
- Ne pas mélanger les groupes entre modales liées
- Utiliser rollbackInProgress pour éviter les réinitialisations prématurées
