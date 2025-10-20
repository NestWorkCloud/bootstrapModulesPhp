<?php

    /**
     * ============================================================
     * MODULE : Gestion modale Bootstrap
     * AUTEUR : Alexis GOMY
     * DATE   : 2025-10-20
     * LICENCE : MIT — voir le fichier LICENSE à la racine du dépôt
     * ============================================================
     *
     * DESCRIPTION :
     * Ce module permet de gérer l'ouverture, la fermeture et le retour
     * entre différentes modales Bootstrap, selon un système hiérarchique
     * basé sur les attributs personnalisés : niveau, groupe, branche.
     *
     * UTILISATION :
     * - Inclure ce fichier dans votre logique PHP
     * - Associer les attributs requis aux boutons et modales
     * - Configurer les options via createModalFlowConfig()
     *
     * REMARQUE :
     * Ce module fait partie du dépôt "bootstrapModulesPhp"
     * https://github.com/NestWorkCloud/bootstrapModulesPhp
     */
    
?>
<!Doctype HTML>
<HTML lang="fr">
    <Head>
        <meta charset="UTF-8">
        <Title>Référence technique : gestion modale par niveaux</Title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </Head>
    <Body class="bg-light">
        <div class="container-lg my-4">
            <h1 class="text-center mb-5">📚 Documentation du système modale Bootstrap</h1>
            <div class="alert alert-info border-start border-4 border-primary shadow-sm mt-4" role="alert">
                <h4 class="alert-heading">📘 Attributs requis pour la gestion modale</h4>
                <p class="mb-2"><strong>Côté bouton (déclencheur)</strong> :</p>
                <ul class="mb-3">
                    <li><code>data-requestedModalLevel</code> : niveau cible à ouvrir (ex : <code>"2"</code>)</li>
                    <li><code>data-modalGroup</code> : identifiant du groupe logique (ex : <code>"firstGroup"</code>)</li>
                    <li><code>data-modalBranch</code> : identifiant de la branche fonctionnelle (ex : <code>"1"</code>, <code>"2"</code>)</li>
                </ul>
                <p class="mb-2"><strong>Côté modale (cible à ouvrir)</strong> :</p>
                <ul class="mb-3">
                    <li><code>data-modalLevel</code> : niveau hiérarchique de la modale</li>
                    <li><code>data-modalGroup</code> : identifiant du groupe auquel elle appartient</li>
                    <li><code>data-modalBranch</code> : identifiant de la branche associée</li>
                </ul>
                <p class="mb-2"><strong>Côté retour (bouton de fermeture avec retour)</strong> :</p>
                <ul class="mb-3">
                    <li><code>data-returnToParentModal="true"</code> : active le retour vers le niveau précédent</li>
                </ul>
                <p class="mb-2"><strong>Côté fermeture volontaire (bouton de sortie)</strong> :</p>
                <ul class="mb-0">
                    <li><code>data-bs-dismiss="modal"</code> : déclenche une fermeture volontaire sans retour</li>
                </ul>
                <hr class="mt-4">
                <p class="mb-0">
                    <strong>Remarques :</strong> Le système utilise <em>niveau</em>, <em>groupe</em> et <em>branche</em> pour cibler précisément la modale à ouvrir.
                    En cas d’absence de branche, un fallback est tenté sans <code>data-modalBranch</code>.
                </p>
            </div>
            <div class="alert alert-warning border-start border-4 border-primary shadow-sm mt-4" role="alert">
                <h4 class="alert-heading">🔁 Comportements automatiques du système modale</h4>
                <p class="mb-2"><strong>Réouverture automatique du modale initial</strong> :</p>
                <ul class="mb-3">
                    <li>Activée si <code>autoRestore</code> est à <code>true</code> dans la configuration</li>
                    <li>Déclenchée uniquement si aucun autre modale n’est ouvert au moment de la fermeture</li>
                    <li>Ignorée si la fermeture est volontaire (ex : clic sur la croix ou bouton avec <code>data-bs-dismiss="modal"</code>)</li>
                </ul>
                <p class="mb-2"><strong>Retour vers le modale précédent</strong> :</p>
                <ul class="mb-3">
                    <li>Déclenché par un bouton avec <code>data-returnToParentModal="true"</code></li>
                    <li>Recherche un modale au niveau <em>actuel - 1</em> avec la même branche</li>
                    <li>Si non trouvé, décrémente la branche jusqu’à <code>1</code> pour tenter un fallback</li>
                    <li>Si aucun modale n’est trouvé avec branche, tente sans <code>data-modalBranch</code></li>
                </ul>
                <p class="mb-2"><strong>Fermeture volontaire (croix ou bouton dédié)</strong> :</p>
                <ul class="mb-3">
                    <li>Détectée automatiquement via les boutons <code>.btn-close</code> ou tout bouton avec <code>data-bs-dismiss="modal"</code></li>
                    <li>Empêche la réouverture automatique du modale initial</li>
                    <li>Réinitialise le suivi du modale sans déclencher d’action</li>
                </ul>
                <hr class="mt-4">
                <p class="mb-0">
                    <strong>Remarques :</strong> Ces comportements sont gérés automatiquement par le script modulaire.
                    Ils peuvent être désactivés ou adaptés via la configuration (<code>createModalFlowConfig()</code>).
                </p>
            </div>
            <div class="container py-5">
                <h2 class="text-center mb-4">🎛️ Déclencheurs de modales</h2>
                <div class="container text-center">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary w-100" data-modalGroup="firstGroup" data-modalBranch="1" data-requestedModalLevel="1">
                                📋 Modale de gestion
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary w-100" data-modalGroup="firstGroup" data-modalBranch="1" data-requestedModalLevel="2">
                              ✏️ Créer
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-secondary w-100" data-modalGroup="firstGroup" data-modalBranch="2" data-requestedModalLevel="2">
                              💾 Mettre à jour
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-secondary w-100" data-modalGroup="firstGroup" data-requestedModalLevel="3" data-modalBranch="2">
                                💾 Mettre à jour sensible
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-danger w-100" data-modalGroup="firstGroup" data-modalBranch="3" data-requestedModalLevel="2">
                              🗑️ Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="managementModal" tabindex="-1" aria-labelledby="managementModalLabel" aria-hidden="true" data-modalGroup="firstGroup" data-modalBranch="1" data-modalLevel="1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="managementModalLabel">Modale de gestion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container text-center">
                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn btn-outline-primary w-100" data-modalGroup="firstGroup" data-modalBranch="1" data-requestedModalLevel="2">
                                      ✏️ Créer
                                    </button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-outline-secondary w-100" data-modalGroup="firstGroup" data-modalBranch="2" data-requestedModalLevel="2">
                                      💾 Mettre à jour
                                    </button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-outline-danger w-100" data-modalGroup="firstGroup" data-modalBranch="3" data-requestedModalLevel="2">
                                      🗑️ Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Fermer">❌ Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true" data-modalGroup="firstGroup" data-modalBranch="1" data-modalLevel="2">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Modale de Création</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            Ceci est une modale minimaliste pour tester les comportements.
                        </div>
                        <div class="modal-footer justify-content-between flex-wrap">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-returnToParentModal="true">
                                    ⬅️ Retour
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Fermer">
                                    ❌ Fermer
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">✏️ Créer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true" data-modalGroup="firstGroup" data-modalBranch="2" data-modalLevel="2">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Modale de mise à jour</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container text-center">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-secondary w-100" data-modalGroup="firstGroup" data-requestedModalLevel="3" data-modalBranch="2">
                                            💾 Mettre à jour la partie sensible
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between flex-wrap">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-returnToParentModal="true">
                                    ⬅️ Retour
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Fermer">
                                    ❌ Fermer
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-secondary">💾 Sauvegarder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deletionModal" tabindex="-1" aria-labelledby="deletionModalLabel" aria-hidden="true" data-modalGroup="firstGroup" data-modalBranch="3" data-modalLevel="2">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deletionModalLabel">Modale de suppression</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            Ceci est une modale minimaliste pour tester les comportements.
                        </div>
                        <div class="modal-footer justify-content-between flex-wrap">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-returnToParentModal="true">
                                    ⬅️ Retour
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Fermer">
                                    ❌ Fermer
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="secondaryUpdateModale" tabindex="-1" aria-labelledby="secondaryUpdateModaleLabel" aria-hidden="true" data-modalGroup="firstGroup" data-modalBranch="2" data-modalLevel="3">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="secondaryUpdateModaleLabel">Modale de mise à jour sensible</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            Ceci est une modale minimaliste pour tester les comportements.
                        </div>
                        <div class="modal-footer justify-content-between flex-wrap">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-returnToParentModal="true">
                                    ⬅️ Retour
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Fermer">
                                    ❌ Fermer
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-secondary">💾 Sauvegarder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scripts pour le comportement des modales -->
        <script>
            // ⚙️ Fonction de configuration du système modale
            // Cette fonction permet d'initialiser les paramètres du système modale de façon centralisée et flexible.
            // Elle accepte un objet avec des valeurs personnalisées ou utilise des valeurs par défaut.
            // Cela permet de créer plusieurs configurations si nécessaire, ou de modifier dynamiquement le comportement.
            function createModalFlowConfig({
                initialLevel = null,      // Niveau du premier modale ouvert
                initialGroup = null,      // Groupe du premier modale ouvert
                initialBranch = null,     // Branche du premier modale ouvert
                manualClose = false,      // Indique si la fermeture vient d’un clic volontaire (ex: croix)
                autoRestore = true        // Active ou désactive la réouverture automatique du modale initial
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
            // Cette fonction remet à zéro tous les paramètres de suivi du système modale.
            // Elle est appelée après une fermeture volontaire ou après une réouverture automatique.
            // Elle peut aussi être utilisée manuellement pour réinitialiser l’état du système.
            function resetModalTracking() {
                modalFlowConfig.initialLevel = null;
                modalFlowConfig.initialGroup = null;
                modalFlowConfig.initialBranch = null;
                modalFlowConfig.manualClose = false;
            }

            // 🔓 Fonction d'initialisation de l’ouverture des modales
            // Cette fonction écoute les boutons qui déclenchent l’ouverture d’un modale spécifique.
            // Elle enregistre le premier modale ouvert comme point de retour potentiel.
            // Elle ferme les modales ouvertes avant d’en ouvrir une nouvelle ciblée par niveau, groupe et branche.
            function initModalOpening() {
                document.querySelectorAll('button[data-requestedModalLevel]').forEach(button => {
                    button.addEventListener('click', () => {
                        const requestedLevel = button.getAttribute('data-requestedModalLevel');
                        const requestedGroup = button.getAttribute('data-modalGroup');
                        const requestedBranch = button.getAttribute('data-modalBranch');

                        if (!modalFlowConfig.initialLevel){
                            modalFlowConfig.initialLevel = requestedLevel;
                            modalFlowConfig.initialGroup = requestedGroup;
                            modalFlowConfig.initialBranch = requestedBranch;
                        }

                        document.querySelectorAll('.modal.show').forEach(modal => {
                            const instance = bootstrap.Modal.getInstance(modal);
                            if (instance) instance.hide();
                        });

                        const targetModal = document.querySelector(`.modal[data-modalLevel="${requestedLevel}"][data-modalGroup="${requestedGroup}"][data-modalBranch="${requestedBranch}"]`);
                        if (targetModal){
                            const instance = bootstrap.Modal.getOrCreateInstance(targetModal);
                            instance.show();
                        }else{
                            console.warn("❌ Aucun modale trouvé pour :", requestedLevel, requestedGroup, requestedBranch);
                        }
                    });
                });
            }

            // 🔙 Fonction d'initialisation du retour vers le modale précédent
            // Cette fonction permet de revenir à un modale de niveau inférieur dans la même logique de groupe et branche.
            // Elle tente d’abord de retrouver le modale précédent avec la même branche, puis décrémente la branche jusqu’à 1.
            // Si aucun modale n’est trouvé avec une branche, elle tente sans branche.
            // Ce système évite les erreurs de navigation et garantit un retour cohérent.
            function initModalRollback() {
                document.querySelectorAll('button[data-returnToParentModal]').forEach(button => {
                    button.addEventListener('click', () => {
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

                            // 🔁 Boucle descendante sur les branches
                            while (currentBranch >= 1 && !targetModal){
                                targetModal = document.querySelector(`.modal[data-modalLevel="${previousLevel}"][data-modalGroup="${currentGroup}"][data-modalBranch="${currentBranch}"]`);
                                currentBranch--;
                            }

                            // 🧪 Si aucune branche ne correspond, on tente sans branche
                            if (!targetModal) {
                                targetModal = document.querySelector(`.modal[data-modalLevel="${previousLevel}"][data-modalGroup="${currentGroup}"]`);
                            }

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
            // Cette fonction détecte les fermetures de modales et décide si le modale initial doit être rouvert.
            // Elle distingue les fermetures volontaires (ex: clic sur la croix) des fermetures passives (ex: clic extérieur).
            // Si aucune autre modale n’est ouverte et que la fermeture n’est pas volontaire, elle peut rouvrir le modale initial.
            // Elle respecte l’option `autoRestore` pour activer ou désactiver ce comportement.
            function initModalCloseWatcher() {
                // ❌ Détection du clic sur la croix
                document.querySelectorAll('.modal .btn-close').forEach(closeButton => {
                    closeButton.addEventListener('click', () => {
                        modalFlowConfig.manualClose = true;
                    });
                });

                // ❌ Détection des fermetures volontaires via le bouton "Fermer" dans le footer
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

                        // ❌ Si fermeture volontaire ou fermeture du modale initial → pas de réouverture
                        if (
                            modalFlowConfig.manualClose ||
                            (modalFlowConfig.initialLevel === closedLevel &&
                             modalFlowConfig.initialGroup === closedGroup &&
                             modalFlowConfig.initialBranch === closedBranch)
                        ){
                            resetModalTracking();
                            return;
                        }

                        // ✅ Réouverture automatique du modale initial
                        if (modalFlowConfig.autoRestore && modalFlowConfig.initialLevel && modalFlowConfig.initialGroup) {
                            const targetInitialModal = document.querySelector(`.modal[data-modalLevel="${modalFlowConfig.initialLevel}"][data-modalGroup="${modalFlowConfig.initialGroup}"][data-modalBranch="${modalFlowConfig.initialBranch}"]`);
                            if (targetInitialModal) {
                                const instance = bootstrap.Modal.getOrCreateInstance(targetInitialModal);
                                instance.show();
                            }
                            resetModalTracking();
                        }
                    });
                });
            }

            // 🚀 Initialisation globale
            const modalFlowConfig = createModalFlowConfig({ initialLevel: null, initialGroup: null, initialBranch: null, manualClose: false, autoRestore: true });
            initModalOpening();
            initModalRollback();
            initModalCloseWatcher();
            </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </Body>
</HTML>