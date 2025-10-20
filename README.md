# 📦 bootstrapModulesPhp

💡 Modules Bootstrap réutilisables pilotés en PHP  
🔧 Logique modale, boutons d’action, configuration explicite et documentation pédagogique

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

🔧 **Page démonstrative complète** avec :

- Structure HTML Bootstrap (`<head>`, `<body>`)
- Panneaux d'information explicites
- Boutons d'appel modale avec attributs personnalisés
- Modales hiérarchisées selon niveau, branche et groupe

🔍 **Fonctionnalités illustrées :**

- Navigation fluide entre modales liées
- Retour volontaire à la modale parente
- Prévention des boucles ou fermetures involontaires
- Attribution sémantique : `data-modal-level`, `data-modal-branch`, `data-modal-group`, `data-returnToParentModal`

📎 **Utilisation :**

> Ce fichier est une **page autonome** à ouvrir directement dans le navigateur.  
> Il sert de **base pédagogique** pour comprendre et reproduire la logique modale dans vos propres projets.

❌ **Ne pas inclure via `require_once`** — ce n’est pas un module logique mais un exemple complet.

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` à la racine du dépôt.

---

## ✍️ Auteur

Alexis GOMY — développeur PHP polyvalent, à la fois UI et traitement, passionné par les systèmes clairs, modulaires et bien documentés.

---

## 🛠️ Modules à venir

- 🔐 Générateur de mot de passe aléatoire

---

## 🤝 Contributions

Les suggestions, retours et améliorations sont les bienvenus.  
Ce dépôt vise à rester clair, ouvert et utile à tous.
