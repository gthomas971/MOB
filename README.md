# Mise en Place du Projet

## Prérequis

- Docker  
- Docker Compose  

---

## Installation et Lancement

### 1. Cloner le dépôt GitHub

```bash
git clone https://github.com/gthomas971/MOB.git
cd MOB
````

### 2. Construire et Lancer les Services
#### Construction des images

```bash
docker-compose -f docker-compose.yml build
````

#### Démarrage des services

```bash
docker-compose -f docker-compose.yml up -d
````


⚠️ Attention : La première fois, ça peut prendre plusieurs minutes car les conteneurs exécutent automatiquement des tâches d'initialisation essentielles
(telles que composer install, npm install, migrations Symfony, chargement des fixtures, etc.).

## Accès aux Applications

Une fois que tous les services sont démarrés et initialisés :

### Frontend (Vue.js)

➡️ https://app.localhost/

### Backend Symfony (Documentation Swagger)

➡️ https://api.localhost/api/docs/index.html

⚠️ Note sur HTTPS en local :

Ce projet utilise Traefik pour gérer le HTTPS en local. Les certificats sont auto-signés, ce qui signifie que votre navigateur ne les reconnaît pas comme sécurisés.
Vous verrez probablement un avertissement “Site non sécurisé” ou “Connexion non privée”.

Pour accéder au frontend et au backend :

Cliquez sur Avancé / Continuer vers le site (selon votre navigateur).

Une fois fait, vous pourrez utiliser l’application normalement en HTTPS.

## Exécution des Tests
### Tests Frontend (Vue.js)

Lancer tous les tests
```bash
docker exec -it vue_frontend npm run test
````

Générer la couverture de tests

```bash
docker exec -it vue_frontend npm run test:coverage
````

### Tests Backend (Symfony)

Lancer la suite de tests PHPUnit

```bash
docker exec -it symfony_php php bin/phpunit
````

Générer la couverture de tests HTML

```bash
docker exec -it symfony_php php bin/phpunit --coverage-html coverage
````

Les résultats seront disponibles dans le répertoire coverage/ à la racine du projet Symfony.
