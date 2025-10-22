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
