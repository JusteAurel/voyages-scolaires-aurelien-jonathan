> **Dépôt GitHub : https://github.com/JusteAurel/voyages-scolaires-aurelien-jonathan
> **Groupe : Aurélien & Jonathan (Binôme)

# Contributions — Phase 2
| Bloc              | Responsable   | Statut        | Commits clés |
|------             |-------------  |--------       |--------------|
| A — Auth + Rôles  | Aurélien      | Terminé       |    68c0c84   |
| B — Modèles       | Aurélien      | Terminé       |    a35eb69   |
| C — CRUD Voyages  | Jonathan      | Terminé       |    17fff5d   |
| D — Participants  | Jonathan      | Terminé       |    9744d56   |
| E — API REST      | Jonathan      | Terminé       |    651baaf   |

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
| **A** - Dockerfile prod + CI/CD        | **Aurélien** | Terminé   | 62d9203, cd7373b, 79f716b, 91e1538, c454da7, 2105431, 432f9a5, 1ae924b |
| **B** - Manifests stateless + sessions | **Aurélien** | Terminé   | 32c8932, ad5d01e, f04d553, 38b646c  |
| **C** - Stateful + backup + monitoring | **Jonathan** | Terminé   | b759174, daff156, 0a49ce7, bccb0f5, cc557ff, 69b0606 |

## Décisions d'architecture

### 1. Image unique Apache vs pod multi-conteneurs (nginx + fpm)
Le choix s'est porté sur une image de production unique basée sur Apache (`Dockerfile.prod`). Cette approche simplifie la configuration de l'infrastructure en évitant d'avoir à orchestrer un conteneur sidecar Nginx séparé pour communiquer avec un pool PHP-FPM au sein d'un même pod. Toutes les extensions PHP critiques (PDO, GD, Zip) sont directement compilées et intégrées de manière native pour optimiser les performances.

### 2. Driver de sessions (Panne Stateless résolue)
Pour permettre une réplication horizontale de l'application (haute disponibilité sur plusieurs pods simultanés), l'application a été rendue totalement *stateless*. Les sessions et caches ne sont plus stockés localement sur le système de fichiers éphémère du conteneur (ce qui déconnecterait un utilisateur si sa requête changeait de pod), mais externalisés dynamiquement vers la base de données. Dans le `ConfigMap`, le paramètre `SESSION_DRIVER` a ainsi été configuré sur `database`.

### 3. Stratégie de tag d'images (:latest vs :sha)
Nous appliquons une double stratégie d'étiquetage automatique sur le registre GitHub (`ghcr.io`) lors d'un push sur `main` :
* Un tag statique `:latest` permettant un accès rapide et constant au build stable le plus récent du projet (validé localement par un `docker pull` concluant).
* Un tag dynamique basé sur le hash unique du commit Git (`:${{ github.sha }}`). Cette méthode est indispensable dans une architecture cloud (Kubernetes) afin de forcer la mise à jour des pods (éviter le cache d'image local), assurer une traçabilité totale et permettre un rollback chirurgical en production.

### 4. Séparation des secrets et des configurations (GitOps)
Suivant les bonnes pratiques de sécurité, la configuration non sensible est centralisée dans un `ConfigMap` versionné (variables d'environnement de l'application, ports, connexions génériques). En revanche, les données hautement sensibles (`APP_KEY`, mots de passe de la base de données) sont isolées dans un objet `Secret` Kubernetes. Le fichier réel `secret.yaml` a été explicitement banni du système de versioning via le `.gitignore`, et seul un modèle de structure `secret.example.yaml` a été poussé pour documenter l'architecture sans compromettre la sécurité.

## Auto-évaluation (fin de phase)

### Aurélien (Bloc A & Bloc B)
* **Ce que j'ai réalisé :** * Création complète et industrialisation du workflow d'intégration et livraison continues (`cicd.yaml`) via GitHub Actions.
  * Validation du fonctionnement de l'artefact Docker final via un test de téléchargement public (`docker pull`) local réussi.
  * Conception intégrale de l'architecture déclarative Kubernetes pour la partie applicative : écriture du `configmap.yaml`, du `secret.example.yaml`, du service réseau d'aiguillage `service.yaml` et de la couche d'accès externe `ingress.yaml`.
  * Configuration du contrôleur de déploiement `deployment.yaml` configuré en haute disponibilité avec 2 répliques physiques de l'application s'exécutant simultanément.
  * Intégration de sondes de santé natives à Laravel 11 (`livenessProbe` et `readinessProbe` pointant vers la route `/up`) pour assurer l'auto-guérison (*self-healing*) des conteneurs par le cluster.
* **Difficulté principale :** L'analyse des journaux d'erreurs au sein d'un environnement virtualisé à distance (GitHub Actions Runner) et la gymnastique d'aiguillage des branches Git pour séparer proprement le code applicatif du code d'infrastructure sans empiéter sur le bloc de mon binôme.
* **Ce que j'ai appris :** La manipulation experte des concepts natifs Kubernetes pour concevoir une application hautement disponible et *stateless*, la mise en place d'une politique de sécurité stricte pour la gestion des secrets et la maîtrise des flux réseau internes d'un cluster (`Ingress -> Service -> Pods`).
>>>>>>> origin/Phase-3-BLOC-B
