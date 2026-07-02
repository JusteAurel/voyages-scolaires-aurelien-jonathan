# Voyages Scolaires

Projet réalisé dans le cadre du module Laravel / DevOps.

## Équipe

- Jonathan Asset
- Aurélien Wiart

Repository GitHub :

https://github.com/JusteAurel/voyages-scolaires-aurelien-jonathan

---

# Technologies utilisées

## Backend

- Laravel 13
- PHP 8.3
- MariaDB

## Frontend

- Blade
- Tailwind CSS
- Vite

## Conteneurisation

- Docker
- Docker Compose

## DevOps

- GitHub Actions
- GitHub Container Registry (GHCR)
- Kubernetes
- ConfigMap
- Secret
- Deployment
- Service
- Ingress
- StatefulSet
- PersistentVolumeClaim
- Job
- CronJob

---

# Fonctionnalités

- Authentification avec Laravel Breeze
- Gestion des rôles (Admin / Élève)
- CRUD des voyages
- Gestion des participants
- API REST sécurisée avec Sanctum
- Déploiement Docker
- Déploiement Kubernetes
- Sauvegarde automatique MariaDB
- Migrations automatiques

---

# Installation en développement

## Cloner le projet

```bash
git clone https://github.com/JusteAurel/voyages-scolaires-aurelien-jonathan.git
cd voyages-scolaires-aurelien-jonathan
```

## Copier le fichier d'environnement

```bash
cp www/.env.example www/.env
```

## Lancer Docker

```bash
docker compose up -d --build
```

## Installer les dépendances

```bash
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build
```

## Générer la clé Laravel

```bash
docker compose exec app php artisan key:generate
```

## Lancer les migrations

```bash
docker compose exec app php artisan migrate
```

L'application est ensuite disponible sur :

http://localhost:8080

---

# Déploiement Kubernetes

Créer le cluster Kubernetes.

Puis appliquer tous les manifests :

```bash
kubectl apply -f k8s/
```

Déployer ensuite MariaDB :

```bash
kubectl apply -f k8s/mariadb-service.yaml
kubectl apply -f k8s/mariadb-statefulset.yaml
```

Lancer les migrations :

```bash
kubectl apply -f k8s/migrate-job.yaml
```

Créer la sauvegarde automatique :

```bash
kubectl apply -f k8s/backup-cronjob.yaml
```

---

# Vérification

Pods :

```bash
kubectl get pods
```

Services :

```bash
kubectl get svc
```

Jobs :

```bash
kubectl get jobs
```

CronJobs :

```bash
kubectl get cronjobs
```

PVC :

```bash
kubectl get pvc
```

---

# CI/CD

Le projet utilise GitHub Actions.

À chaque push sur la branche `main` :

- exécution des tests Laravel
- compilation des assets
- construction de l'image Docker
- publication automatique sur GitHub Container Registry

---

# Comptes de test

## Administrateur

Créer un utilisateur puis modifier son rôle dans la base de données :

```
role = admin
```

## Élève

Inscription classique via Laravel Breeze.

---

# Répartition des tâches

### Aurélien

- Bloc A
- Bloc B
- Phase 3 Bloc A
- Phase 3 Bloc B

### Jonathan

- Bloc C
- Bloc D
- Bloc E
- Phase 3 Bloc C

---

# Licence

Projet pédagogique réalisé dans le cadre de la formation.