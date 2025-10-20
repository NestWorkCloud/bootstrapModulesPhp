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

- Structure HTML Bootstrap (`<head>`, `<body>`) prête à l’emploi
- Panneaux d'information explicites pour guider l’utilisateur
- Boutons d’appel modale avec attributs personnalisés (`data-requestedModalLevel`, `data-modalGroup`, etc.)
- Modales hiérarchisées selon niveau, branche, groupe et identifiant métier (`data-modalRequestId`)
- Tableaux interactifs activés automatiquement via `data-datatable="true"` et initialisés à l’ouverture
- Gestion d’accessibilité renforcée :
  - Désactivation des modales non actives via `inert`
  - Suppression de `aria-hidden` sur la modale ouverte
  - Retrait du focus (`blur()`) à la fermeture pour éviter les conflits avec les technologies d’assistance

🔍 **Fonctionnalités illustrées :**

- Navigation fluide entre modales liées par niveau, groupe et branche
- Retour volontaire à la modale parente via `data-returnToParentModal`
- Réouverture automatique du modale initial si fermeture passive (option `autoRestore`)
- Prévention des boucles ou fermetures involontaires grâce au suivi centralisé
- Initialisation automatique des tableaux DataTables dans les modales ouvertes
  - Activation via `data-datatable="true"`
  - Message personnalisé en cas de tableau vide via `data-emptyrow="📭 Aucun utilisateur enregistré."`
- Sécurisation du focus et de l’accessibilité à la fermeture (clic extérieur ou bouton) via `blur()` et nettoyage des attributs
- Attribution sémantique claire :  
  `data-modal-level`, `data-modal-branch`, `data-modal-group`, `data-modalRequestId`,  
  `data-returnToParentModal`, `data-datatable`, `data-emptyrow`

📎 **Utilisation :**

> Ce fichier est une **page autonome** à ouvrir directement dans le navigateur.  
> Il sert de **base pédagogique** pour comprendre, tester et reproduire la logique modale dans vos propres projets.  
> Il illustre également les bonnes pratiques d’accessibilité et d’interface dans les systèmes modaux personnalisés.

❌ **Ne pas inclure via `require_once`** — ce n’est pas un module logique mais un exemple complet, conçu pour démontrer le fonctionnement du système modulaire.

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
