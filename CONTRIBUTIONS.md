> **Dépôt GitHub : https://github.com/JusteAurel/voyages-scolaires-aurelien-jonathan
> **Groupe : Aurélien & Jonathan (Binôme)

# Contributions — Phase 2
| Bloc              | Responsable   | Statut        | Commits clés |
|------             |-------------  |--------       |--------------|
| A — Auth + Rôles  | Aurélien      | Terminé       |    68c0c84   |
| B — Modèles       | Aurélien      | Terminé       |    a35eb69   |
| C — CRUD Voyages  | Jonathan      | Terminé       |    17fff5d   |
| D — Participants  | Jonathan      | À faire       |       —      |
| E — API REST      | Binôme        | À faire       |       —      |

## Auto-évaluation (à remplir en fin de phase)
### Aurélien
- Ce que j'ai réalisé : 
    Blocs Réalisés :
        Bloc A (Authentification, Rôles, Policies) et Bloc B (Modèle, Migrations, Relations Eloquent).
    Ce que j'ai implémenté : 
        - Installation et configuration de l'échafaudage d'authentification Laravel Breeze.
        - Altération de la table `users` via une migration pour injecter un champ de type `enum` gérant les rôles (`eleve`, `parent`, `enseignant`, `admin`).
        - Génération et codage des fichiers de migrations et des modèles Eloquent pour les entités `Voyage` et `Participant`.
        - Configuration de l'ensemble des relations de clés étrangères réciproques (`HasMany` / `BelongsTo`) entre `User`, `Voyage` et `Participant` pour assurer l'intégrité des données.
        - Implémentation fine de la sécurité applicative dans `VoyagePolicy` pour restreindre la création, la modification et la suppression de voyages selon le rôle de l'utilisateur connecté.
- Difficulté principale : 
    L'adaptation aux nouveautés syntaxiques majeures de Laravel 13. Contrairement aux versions précédentes, Laravel 13 utilise désormais nativement les **Attributs PHP** (`#[Fillable]`, `#[Hidden]`) directement au-dessus des classes de modèles au lieu des propriétés protégées classiques (`protected $fillable`). Il a fallu appréhender cette structure moderne pour déclarer proprement le nouveau champ `role` dans le modèle `User`.
- Ce que j'ai appris : 
    - Compréhension de l'architecture éphémère de Docker : les conteneurs peuvent être détruits et recréés (`docker compose down / up`) sans perte de données tant que les volumes nommés persistants et les Bind Mounts sont correctement configurés.
    - Manipulation avancée du framework ORM Eloquent et gestion fine des autorisations de requêtes côté serveur avec les *Policies*.



# Contributions - Phase 3

| Bloc                                   | Responsable  | Statut    | Commits clés  |
|--------------------------------------  | -----------  | --------  | ------------  |
| **A** - Dockerfile prod + CI/CD        | **Aurélien** | Terminé  | 62d9203, cd7373b, 79f716b, 91e1538, c454da7, 2105431, 432f9a5, 1ae924b |
| **B** - Manifests stateless + sessions | **Aurélien** | À faire   |               |
| **C** - Stateful + backup + monitoring | **Jonathan** | À faire   |               |

## Décisions d'architecture

### 1. Image unique Apache vs pod multi-conteneurs (nginx + fpm)
Le choix s'est porté sur une image de production unique basée sur Apache (`Dockerfile.prod`). Cette approche simplifie la configuration de l'infrastructure en évitant d'avoir à orchestrer un conteneur sidecar Nginx séparé pour communiquer avec un pool PHP-FPM au sein d'un même pod. Toutes les extensions PHP critiques (PDO, GD, Zip) sont directement compilées et intégrées de manière native pour optimiser les performances.

### 2. Driver de sessions
Pour éliminer les dépendances d'infrastructure physiques et isoler l'exécution du pipeline de validation, les variables d'environnement de test ont été découplées. Les sessions et caches applicatifs basculent dynamiquement sur le driver `array` (en mémoire volatile), tandis que la base de données exploite un driver SQLite éphémère (`:memory:`). Cela garantit des tests unitaires et de feature rapides, répétables et totalement étanches.

### 3. Stratégie de tag d'images (:latest vs :sha)
Nous appliquons une double stratégie d'étiquetage automatique sur le registre GitHub (`ghcr.io`) lors d'un push sur `main` :
* Un tag statique `:latest` permettant un accès rapide et constant au build stable le plus récent du projet.
* Un tag dynamique basé sur le hash unique du commit Git (`:${{ github.sha }}`). Cette méthode est indispensable dans une architecture cloud (Kubernetes) afin de forcer la mise à jour des pods (éviter le cache d'image local) et assurer une traçabilité ainsi qu'un rollback chirurgical en production.

## Auto-évaluation (fin de phase)
### Aurélien (Bloc A)
* **Ce que j'ai réalisé :** * Création complète et industrialisation du workflow d'intégration et livraison continues (`cicd.yaml`) via GitHub Actions.
  * Configuration et optimisation d'un processus d'assemblage multi-architecture (`linux/amd64`, `linux/arm64`) s'appuyant sur l'émulateur QEMU et Docker Buildx pour garantir la portabilité des livrables sur des environnements serveurs modernes ou architectures à puces ARM.
  * Automatisation rigoureuse de la validation applicative (installation isolée des packages Composer, intégration de Node.js v20 pour compiler les assets front-end via Vite, génération de clés de sécurité et exécution des tests automatisés).
  * Résolution successive et itérative de plusieurs blocages majeurs du runner : correction des droits d'écriture et de structure des répertoires de l'application (chemin manquant sur l'instruction de copie `www/`), gestion de la casse pour la publication sur `ghcr.io` (conversion forcée en minuscules), et échappement des variables SQLite via guillemets.
* **Difficulté principale :** L'analyse des journaux d'erreurs au sein d'un environnement virtualisé à distance (GitHub Actions Runner) et la gestion de la lenteur extrême induite par l'émulation matérielle logicielle lors de la compilation des extensions natives PHP.
* **Ce que j'ai appris :** La manipulation experte des outils natifs de build de l'écosystème Docker (Buildx/QEMU), le paramétrage fin des droits d'API (`packages: write`) et la conception d'un pipeline CI/CD résistant aux contraintes de production modernes.