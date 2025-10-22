  // ⚙️ Fonction de configuration du système modale
  // Cette fonction initialise les paramètres de suivi du système modale de manière centralisée et flexible.
  // Elle accepte un objet de configuration avec des valeurs personnalisées ou utilise des valeurs par défaut.
  // Elle permet de définir le point d’entrée du flux modale (niveau, groupe, branche), de suivre les fermetures volontaires,
  // et d’activer ou non la réouverture automatique du modale initial.
  // Ce système facilite la gestion de scénarios complexes avec plusieurs modales imbriquées ou parallèles.
  function createModalFlowConfig({
      initialLevel = null,        // Niveau du premier modale ouvert
      initialGroup = null,        // Groupe du premier modale ouvert
      initialBranch = null,       // Branche du premier modale ouvert
      manualClose = false,        // Indique si la fermeture vient d’un clic volontaire (ex: croix)
      autoRestore = true ,        // Active ou désactive la réouverture automatique du modale initial
      rollbackInProgress = false  // Empêche reset si rollback actif
  } = {}) {
      return {
          initialLevel,
          initialGroup,
          initialBranch,
          manualClose,
          autoRestore
      };
  }

  // 🔄 Fonction de réinitialisation du suivi modale
  // Réinitialise tous les paramètres du système modale (niveau, groupe, branche, fermeture volontaire).
  // Utilisée après une fermeture volontaire, une réouverture automatique ou manuellement pour repartir d’un état neutre.
  function resetModalTracking() {
      modalFlowConfig.initialLevel = null;
      modalFlowConfig.initialGroup = null;
      modalFlowConfig.initialBranch = null;
      modalFlowConfig.manualClose = false;
  }

  // 🔓 Fonction d’ouverture dynamique des modales
  // Écoute les boutons de déclenchement, enregistre le point de retour initial, ferme les modales actives,
  // puis ouvre la modale ciblée par niveau/groupe/branche et initialise ses tableaux marqués data-datatable="true".
  function initModalOpening() {
      document.querySelectorAll('button[data-requestedModalLevel]').forEach(button => {
          button.addEventListener('click', () => {
              const requestedLevel = button.getAttribute('data-requestedModalLevel');
              const requestedGroup = button.getAttribute('data-modalGroup');
              const requestedBranch = button.getAttribute('data-modalBranch');
              const requestId = button.getAttribute('data-modalRequestId');
  
              // Stockage conditionnel de l'ID métier
              modalFlowConfig.requestId = requestId ? requestId : null;
  
              // Enregistrement du point de retour initial
              if (!modalFlowConfig.initialLevel){
                  modalFlowConfig.initialLevel = requestedLevel;
                  modalFlowConfig.initialGroup = requestedGroup;
                  modalFlowConfig.initialBranch = requestedBranch;
              }
  
              // Fermeture des modales ouvertes
              document.querySelectorAll('.modal.show').forEach(modal => {
                  const instance = bootstrap.Modal.getInstance(modal);
                  if (instance) instance.hide();
              });
  
              // Construction du sélecteur modale
              let selector = `.modal[data-modalLevel="${requestedLevel}"][data-modalGroup="${requestedGroup}"][data-modalBranch="${requestedBranch}"]`;
  
              // Si un ID métier est fourni, on le cible aussi
              if (requestId) {
                  selector += `[data-modalRequestId="${requestId}"]`;
              }
  
              // Ouverture du modale
              const targetModal = document.querySelector(selector);
              if (targetModal){
  
                  // 🪄 Affichage et initialisation
                  const instance = bootstrap.Modal.getOrCreateInstance(targetModal);
                  instance.show();
                  initTableInModal(targetModal);
  
                  // 🔍 Interception de la fermeture pour éviter le focus bloqué
                  targetModal.addEventListener('hide.bs.modal', () => {
                      if (document.activeElement instanceof HTMLElement){
                          document.activeElement.blur(); // ✅ Évite le conflit aria-hidden + focus
                      }
                  });
  
              }else{
                  console.warn("❌ Aucun modale trouvé pour :", requestedLevel, requestedGroup, requestedBranch);
              }
          });
      });
  }

  // 🔙 Fonction de retour vers le modale précédent
  // Permet de revenir à un modale de niveau inférieur dans le même groupe, en testant d’abord la branche actuelle,
  // puis en décrémentant jusqu’à 1, avec fallback sans branche si aucun modale n’est trouvé.
  function initModalRollback() {
      document.querySelectorAll('button[data-returnToParentModal]').forEach(button => {
          button.addEventListener('click', () => {
              modalFlowConfig.rollbackInProgress = true;
              const currentModal = button.closest('.modal');
              if (currentModal){
                  const currentLevel = parseInt(currentModal.getAttribute('data-modalLevel'));
                  const currentGroup = currentModal.getAttribute('data-modalGroup');
                  let currentBranch = parseInt(currentModal.getAttribute('data-modalBranch'));

                  document.querySelectorAll('.modal.show').forEach(modal => {
                      const instance = bootstrap.Modal.getInstance(modal);
                      if (instance) instance.hide();
                  });

                  const previousLevel = currentLevel - 1;
                  let targetModal = null;

                  // Boucle descendante sur les branches
                  while (currentBranch >= 1 && !targetModal){
                      targetModal = document.querySelector(`.modal[data-modalLevel="${previousLevel}"][data-modalGroup="${currentGroup}"][data-modalBranch="${currentBranch}"]`);
                      currentBranch--;
                  }

                  // Si aucune branche ne correspond, on tente sans branche
                  if (!targetModal) {
                      targetModal = document.querySelector(`.modal[data-modalLevel="${previousLevel}"][data-modalGroup="${currentGroup}"]`);
                  }

                  // Ouverture du modale
                  if (targetModal){
                      const instance = bootstrap.Modal.getOrCreateInstance(targetModal);
                      instance.show();
                  }else{
                      console.warn("❌ Aucun modale précédent trouvé pour :", previousLevel, currentGroup);
                  }
              }
          });
      });
  }

  // 🕵️‍♂️ Fonction de surveillance des fermetures de modales
  // Détecte les fermetures volontaires ou passives, et décide si le modale initial doit être rouvert.
  // Respecte l’option `autoRestore` et réinitialise le suivi si la fermeture est volontaire ou finale.
  function initModalCloseWatcher() {
      // Détection du clic sur la croix
      document.querySelectorAll('.modal .btn-close').forEach(closeButton => {
          closeButton.addEventListener('click', () => {
              modalFlowConfig.manualClose = true;
          });
      });

      // Détection des fermetures volontaires via le bouton "Fermer" dans le footer
      document.querySelectorAll('button[data-bs-dismiss="modal"]').forEach(button => {
          button.addEventListener('click', () => {
              modalFlowConfig.manualClose = true;
          });
      });

      // 🔍 Écoute des fermetures de modales
      document.querySelectorAll('.modal').forEach(modal => {
          modal.addEventListener('hidden.bs.modal', () => {
              if (document.querySelector('.modal.show')) {
                  modalFlowConfig.manualClose = false;
                  return;
              }

              const closedLevel = modal.getAttribute('data-modalLevel');
              const closedGroup = modal.getAttribute('data-modalGroup');
              const closedBranch = modal.getAttribute('data-modalBranch');

              // Si fermeture volontaire ou fermeture du modale initial → pas de réouverture
              const isFinalClosure =
                  modalFlowConfig.manualClose || (
                      modalFlowConfig.initialLevel === closedLevel &&
                      modalFlowConfig.initialGroup === closedGroup &&
                      modalFlowConfig.initialBranch === closedBranch &&
                      !modalFlowConfig.rollbackInProgress // ✅ empêche reset si rollback actif
                  );
              if (isFinalClosure) {
                  resetModalTracking();
                  return;
              }

              // Réouverture automatique du modale initial
              if (modalFlowConfig.autoRestore && modalFlowConfig.initialLevel && modalFlowConfig.initialGroup) {
                  const targetInitialModal = document.querySelector(`.modal[data-modalLevel="${modalFlowConfig.initialLevel}"][data-modalGroup="${modalFlowConfig.initialGroup}"][data-modalBranch="${modalFlowConfig.initialBranch}"]`);
                  if (targetInitialModal) {
                      const instance = bootstrap.Modal.getOrCreateInstance(targetInitialModal);
                      instance.show();
                  }
                  modalFlowConfig.rollbackInProgress = false;
                  resetModalTracking();
              }
          });
      });
  }
