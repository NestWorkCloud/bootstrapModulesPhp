<?php

    /**
     * ============================================================
     * MODULE  : Gestion modale Bootstrap
     * AUTEUR  : Alexis GOMY
     * DATE    : 2025-10-20
     * VERSION : V1
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
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    </Head>
    <Body class="bg-light">
        <div class="container-lg my-4">

            <!-- Cadres d'explications -->
            <h1 class="text-center mb-5">📚 Documentation du système modale Bootstrap</h1>
            <div class="alert alert-info border-start border-4 border-primary shadow-sm mt-4" role="alert">
                <h4 class="alert-heading">📘 Attributs requis pour la gestion modale</h4>
                <p class="mb-2"><strong>Côté bouton (déclencheur)</strong> :</p>
                <ul class="mb-3">
                    <li><code>data-requestedModalLevel</code> : niveau cible à ouvrir (ex : <code>"2"</code>)</li>
                    <li><code>data-modalGroup</code> : identifiant du groupe logique (ex : <code>"firstGroup"</code>)</li>
                    <li><code>data-modalBranch</code> : identifiant de la branche fonctionnelle (ex : <code>"1"</code>, <code>"2"</code>)</li>
                    <li><code>data-modalRequestId</code> (optionnel) : identifiant métier pour cibler une variante spécifique</li>
                </ul>
                <p class="mb-2"><strong>Côté modale (cible à ouvrir)</strong> :</p>
                <ul class="mb-3">
                    <li><code>data-modalLevel</code> : niveau hiérarchique de la modale</li>
                    <li><code>data-modalGroup</code> : identifiant du groupe auquel elle appartient</li>
                    <li><code>data-modalBranch</code> : identifiant de la branche associée</li>
                    <li><code>data-modalRequestId</code> (optionnel) : identifiant métier pour différencier plusieurs modales similaires</li>
                </ul>
                <p class="mb-2"><strong>Côté retour (bouton de fermeture avec retour)</strong> :</p>
                <ul class="mb-3">
                    <li><code>data-returnToParentModal="true"</code> : active le retour vers le niveau précédent</li>
                </ul>
                <p class="mb-2"><strong>Côté fermeture volontaire (bouton de sortie)</strong> :</p>
                <ul class="mb-3">
                    <li><code>data-bs-dismiss="modal"</code> : déclenche une fermeture volontaire sans retour</li>
                    <li><code>.btn-close</code> : fermeture volontaire via la croix Bootstrap</li>
                </ul>
                <p class="mb-2"><strong>Côté tableau (activation DataTables)</strong> :</p>
                <ul class="mb-0">
                    <li><code>data-datatable="true"</code> : active automatiquement DataTables sur ce tableau à l’ouverture de la modale</li>
                    <li><strong>⚠️</strong> Le tableau doit obligatoirement avoir un attribut <code>id</code> unique</li>
                    <li><code>data-emptyrow="..."</code> : message personnalisé à afficher si le tableau est vide (injecté via <code>language.emptyTable</code>) — exemple : <code>📭 Aucun utilisateur enregistré.</code></li>
                </ul>
                <hr class="mt-4">
                <p class="mb-0">
                    <strong>Remarques :</strong> Le système utilise <em>niveau</em>, <em>groupe</em>, <em>branche</em> et éventuellement <em>requestId</em> pour cibler précisément la modale à ouvrir.
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
                <p class="mb-2"><strong>Initialisation automatique des tableaux</strong> :</p>
                <ul class="mb-3">
                    <li>Tout tableau avec <code>data-datatable="true"</code> est automatiquement activé à l’ouverture du modale</li>
                    <li>Si le tableau est déjà actif, il n’est pas réinitialisé ni recalculé</li>
                    <li>Le système utilise <code>initTableInModal(modalElement)</code> pour gérer cette logique</li>
                </ul>
                <hr class="mt-4">
                <p class="mb-0">
                    <strong>Remarques :</strong> Ces comportements sont gérés automatiquement par le script modulaire.
                    Ils peuvent être désactivés ou adaptés via la configuration (<code>createModalFlowConfig()</code>).
                </p>
              </div>
            </div>

            <!-- Appels des modales -->
            <div class="container py-5">
                <h2 class="text-center mb-4">🎛️ Déclencheurs de modales</h2>
                <div class="container text-center">
                    <h5 class="text-start mt-4 mb-3">Cas 1 : 🧩 Modales simples (déclarées statiquement)</h5>
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
                    <h5 class="text-start mt-4 mb-3">Cas 2 : 🧠 Modale dynamique générée depuis PHP (gestion utilisateur)</h5>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary w-100" data-modalGroup="secondGroup" data-modalBranch="1" data-requestedModalLevel="1">
                                📋 Modale de gestion
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Les modales -->
        <div class="modal fade" id="managementModal" tabindex="-1" aria-labelledby="managementModalLabel" data-modalGroup="firstGroup" data-modalBranch="1" data-modalLevel="1">
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
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" data-modalGroup="firstGroup" data-modalBranch="1" data-modalLevel="2">
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
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" data-modalGroup="firstGroup" data-modalBranch="2" data-modalLevel="2">
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
        <div class="modal fade" id="deletionModal" tabindex="-1" aria-labelledby="deletionModalLabel" data-modalGroup="firstGroup" data-modalBranch="3" data-modalLevel="2">
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
        <div class="modal fade" id="secondaryUpdateModale" tabindex="-1" aria-labelledby="secondaryUpdateModaleLabel" data-modalGroup="firstGroup" data-modalBranch="2" data-modalLevel="3">
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


        <?php 

            // Base de données utilisateur
            $utilisateurs = [
                ['id' => 101, 'nom' => 'Alice Dupont', 'email' => 'alice@example.com'],
                ['id' => 102, 'nom' => 'Bruno Martin', 'email' => 'bruno@example.com'],
                ['id' => 103, 'nom' => 'Chloé Bernard', 'email' => 'chloe@example.com']
            ];

        ?>

        <!-- Modale de gestion des utilisateurs -->
        <div class="modal fade" id="managementModal" tabindex="-1" aria-labelledby="managementModalLabel" data-modalGroup="secondGroup" data-modalBranch="1" data-modalLevel="1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="managementModalLabel">👥 Modale de gestion des utilisateurs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="userTable" class="table table-striped table-bordered table-hover align-middle text-center" data-datatable="true" data-emptyrow="💤 Rien à afficher ici pour l’instant.">
                                <thead>
                                    <tr>
                                        <th class="text-center">Identifiant</th>
                                        <th class="text-center">Nom</th>
                                        <th class="text-center">Adresse mail</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($utilisateurs as $user){
                                    ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= $user['nom'] ?></td>
                                        <td><?= $user['email'] ?></td>
                                        <td style="white-space: nowrap;">
                                            <div class="container text-center">
                                                <div class="row">
                                                    <div class="col">
                                                        <button type="button" class="btn btn-outline-secondary w-100" data-modalGroup="secondGroup" data-modalBranch="2" data-requestedModalLevel="2" data-modalRequestId="<?= $user['id'] ?>">
                                                          💾 Mettre à jour
                                                        </button>
                                                    </div>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-outline-danger w-100" data-modalGroup="secondGroup" data-modalBranch="3" data-requestedModalLevel="2" data-modalRequestId="<?= $user['id'] ?>">
                                                          🗑️ Supprimer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <button type="button" class="btn btn-outline-primary w-100" data-modalGroup="secondGroup" data-modalBranch="1" data-requestedModalLevel="2">
                                                ✏️ Créer un utilisateur
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Fermer">❌ Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modale de création des utilisateurs -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" data-modalGroup="secondGroup" data-modalBranch="1" data-modalLevel="2">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Modale de création d'utilisateur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="nomInput">Nom : </label>
                                <input type="text" class="form-control" id="nomInput" name="nom" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="emailInput">Adresse mail : </label>
                                <input type="email" class="form-control" id="emailInput" name="email" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="newPasswordInput">Mot de passe</label>
                                <input type="password" class="form-control" id="newPasswordInput" name="password" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="confirmPasswordInput">Confirmation du mot de passe</label>
                                <input type="password" class="form-control" id="confirmPasswordInput" name="confirmPassword" />
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
                                <button type="submit" class="btn btn-primary">✏️ Créer l'utilisateur</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php

            // Boucle des utilisateurs
            foreach ($utilisateurs as $user){
        ?>
        <!-- Modale de mise à jour -->
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" data-modalGroup="secondGroup" data-modalBranch="2" data-modalLevel="2" data-modalRequestId="<?= $user['id'] ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Modale de mise à jour</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label" for="nomInput<?= $user['id'] ?>">Nom : </label>
                                            <input type="text" class="form-control" id="nomInput<?= $user['id'] ?>" name="nom" value="<?= $user['nom'] ?>" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="emailInput<?= $user['id'] ?>">Adresse mail : </label>
                                            <input type="email" class="form-control" id="emailInput<?= $user['id'] ?>" name="email" value="<?= $user['email'] ?>" />
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary w-100" data-modalGroup="secondGroup" data-requestedModalLevel="3" data-modalBranch="2" data-modalRequestId="<?= $user['id'] ?>">
                                            💾 Mise à jour du mot de passe
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

        <!-- Modale de mise à jour du mot de passe -->
        <div class="modal fade" id="secondaryUpdateModale" tabindex="-1" aria-labelledby="secondaryUpdateModaleLabel" data-modalGroup="secondGroup" data-modalBranch="2" data-modalLevel="3" data-modalRequestId="<?= $user['id'] ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="secondaryUpdateModaleLabel">Modale de mise à jour du mot de passe</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                Mise à jour du mot de passe de l'utilisateur : </br> <ul><li><em><?= $user['nom'] ?> (<?= $user['email'] ?>)</em></li></ul>
                                <div class="mb-3">
                                    <label class="form-label" for="newPasswordInput<?= $user['id'] ?>">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="newPasswordInput<?= $user['id'] ?>" name="newPassword" placeholder="******" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="confirmPasswordInput<?= $user['id'] ?>">Confirmation du nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="confirmPasswordInput<?= $user['id'] ?>" name="confirmPassword" placeholder="******" />
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

        <!-- Modale de suppression -->
        <div class="modal fade" id="deletionModal" tabindex="-1" aria-labelledby="deletionModalLabel" data-modalGroup="secondGroup" data-modalBranch="3" data-modalLevel="2" data-modalRequestId="<?= $user['id'] ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deletionModalLabel">Modale de suppression</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                Suppression de l'utilisateur : </br> <ul><li><em><?= $user['nom'] ?> (<?= $user['email'] ?>)</em></li></ul>
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
                                <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
            }

        ?>

        <!-- Scripts pour le comportement des modales -->
        <script>
            // ⚙️ Fonction de configuration du système modale
            // Cette fonction initialise les paramètres de suivi du système modale de manière centralisée et flexible.
            // Elle accepte un objet de configuration avec des valeurs personnalisées ou utilise des valeurs par défaut.
            // Elle permet de définir le point d’entrée du flux modale (niveau, groupe, branche), de suivre les fermetures volontaires,
            // et d’activer ou non la réouverture automatique du modale initial.
            // Ce système facilite la gestion de scénarios complexes avec plusieurs modales imbriquées ou parallèles.
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
                        if (
                            modalFlowConfig.manualClose ||
                            (modalFlowConfig.initialLevel === closedLevel &&
                             modalFlowConfig.initialGroup === closedGroup &&
                             modalFlowConfig.initialBranch === closedBranch)
                        ){
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
                            resetModalTracking();
                        }
                    });
                });
            }

            // 📊 Initialise les tableaux DataTables marqués `data-datatable="true"` dans une modale donnée
            // Chaque tableau doit avoir un `id` unique. Si non encore actif, il est initialisé avec les options standard.
            // Aucun recalcul n’est effectué sur les tableaux déjà actifs pour éviter les conflits visuels ou logiques.
            // Si l’attribut `data-emptyrow` est présent, son contenu est utilisé comme message personnalisé
            // à afficher lorsque le tableau est vide, via l’option native `language.emptyTable` de DataTables.
            // Exemple : <table data-datatable="true" data-emptyrow="📭 Aucun utilisateur enregistré.">
            function initTableInModal(modalElement) {
                const tables = modalElement.querySelectorAll('table[data-datatable="true"]');
                tables.forEach(table => {
                    const tableId = table.id;
                    if (!tableId) {
                        console.warn("❌ Le tableau avec datatable=\"true\" doit avoir un id pour être initialisé.");
                        return;
                    }
                    if (!$.fn.DataTable.isDataTable(`#${tableId}`)) {
                        const emptyMessage = table.getAttribute('data-emptyrow') || "📭 Ce tableau est vide pour le moment.";

                        $(`#${tableId}`).DataTable({
                            responsive: true,
                            responsive: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
                                emptyTable: emptyMessage
                            }
                        });
                        console.log(`✅ DataTables initialisé dans ${tableId}`);
                    }
                });
            }

            // 🚀 Initialisation globale
            const modalFlowConfig = createModalFlowConfig({ initialLevel: null, initialGroup: null, initialBranch: null, manualClose: false, autoRestore: true });
            initModalOpening();
            initModalRollback();
            initModalCloseWatcher();
        </script>

        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    </Body>
</HTML>