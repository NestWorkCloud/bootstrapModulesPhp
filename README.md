# bootstrapModulesPhp

Modules Bootstrap réutilisables pilotés en PHP — pour créer des interfaces claires, modulaires et extensibles.

Ce dépôt regroupe des composants UI développés en PHP, compatibles avec Bootstrap, et conçus pour être facilement intégrés, configurés et documentés. Chaque module est accompagné d'une logique explicite, de commentaires pédagogiques en français, et d'une structure pensée pour les débutants comme pour les architectes techniques.

---

## 🎯 Objectifs du dépôt

- Centraliser des modules PHP pour interfaces Bootstrap
- Proposer une logique modulaire, extensible et maintenable
- Documenter chaque composant avec des commentaires clairs et pédagogiques
- Faciliter l'intégration dans des projets éducatifs, administratifs ou collaboratifs

---

## 📦 Modules disponibles

### 1. `bootstrapModalAtLevelBranchAndGroup.php`

**Module de gestion modale avancée**, basé sur des attributs personnalisés :

- `data-modal-level` : définit le niveau hiérarchique de la modale
- `data-modal-branch` : identifie la branche logique (ex. : inscription, édition)
- `data-modal-group` : regroupe les modales liées (ex. : modales d’un même processus)
- `data-returnToParentModal` : permet un retour explicite à la modale parente

**Fonctionnalités :**

- Ouverture et fermeture contrôlées selon le contexte
- Navigation fluide entre modales liées
- Prévention des boucles ou fermetures involontaires
- Compatible avec Bootstrap 5+

**Utilisation :**

```php
require_once 'bootstrapModalAtLevelBranchAndGroup.php';
createModalFlowConfig();
