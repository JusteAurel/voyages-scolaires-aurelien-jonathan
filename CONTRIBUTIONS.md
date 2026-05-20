> **Dépôt GitHub : https://github.com/JusteAurel/voyages-scolaires-aurelien-jonathan
> **Groupe : Aurélien & Jonathan (Binôme)

# Contributions — Phase 2
| Bloc              | Responsable   | Statut        | Commits clés |
|------             |-------------  |--------       |--------------|
| A — Auth + Rôles  | Aurélien      | Terminé       |    68c0c84   |
| B — Modèles       | Aurélien      | Terminé       |    a35eb69   |
| C — CRUD Voyages  | Jonathan      | En cours      |       —      |
| D — Participants  | Jonathan      | À faire       |       —      |
| E — API REST      | Binôme        | À faire       |       —      |

## Auto-évaluation (à remplir en fin de phase)
### Prénom1
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
