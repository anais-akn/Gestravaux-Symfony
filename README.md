<h1 align="center">ImmoSync — Gestion de Travaux Immobiliers</h1>

<p align="center">
  Application web de gestion de chantiers et de devis pour propriétaires, entrepreneurs et inspecteurs.
</p>

---

## 📋 Sommaire

- [Présentation](#-présentation)
- [Technologies utilisées](#-technologies-utilisées)
- [Prérequis](#-prérequis)
- [Installation et lancement](#-installation-et-lancement)
- [Créer un compte](#-créer-un-compte)
- [Rôles et fonctionnalités](#-rôles-et-fonctionnalités)
- [Structure du projet](#-structure-du-projet)
- [Variables d'environnement](#-variables-denvironnement)
- [Commandes utiles](#-commandes-utiles)

---

## 🏗️ Présentation

**ImmoSync** est une application Symfony 7.4 qui permet de gérer des chantiers de travaux immobiliers. Elle met en relation trois types d'acteurs :

- 🏠 **Propriétaire** : déclare ses biens, consulte ses chantiers et compare les devis reçus.
- 🔧 **Entrepreneur** : répond aux appels d'offres en soumettant des devis chiffrés.
- 🔍 **Inspecteur** : supervise les chantiers et les localise sur une carte Google Maps.

---

## 🛠 Technologies utilisées

| Technologie | Version |
|---|---|
| PHP | ≥ 8.2 |
| Symfony | 7.4.* |
| Doctrine ORM | ^3.5 |
| MariaDB | 10.11.2 |
| Twig | ^3.0 |
| Stimulus / Turbo (UX) | ^2.31 |
| Docker / Docker Compose | — |
| Google Maps API | — |

---

## ✅ Prérequis

Avant de lancer le projet, assurez-vous d'avoir installé :

- [PHP 8.2+](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [Docker & Docker Compose](https://docs.docker.com/get-docker/)
- [Symfony CLI](https://symfony.com/download) *(optionnel mais recommandé)*

---

## 🚀 Installation et lancement

### 1. Cloner le dépôt

```bash
git clone https://github.com/le-rebours/2026-GR1-WEB-GESTRAVAUX.git
cd 2026-GR1-WEB-GESTRAVAUX
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

Copiez le fichier `.env` et personnalisez-le :

```bash
cp .env .env.local
```

Éditez `.env.local` et renseignez au minimum :

```dotenv
APP_SECRET=votre_secret_ici
DATABASE_URL="mysql://root:root@127.0.0.1:3306/app_db?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
GOOGLE_MAPS_API_KEY=votre_cle_google_maps
```

> ⚠️ Ne commitez jamais votre fichier `.env.local` — il est déjà dans le `.gitignore`.

### 4. Démarrer la base de données avec Docker

```bash
docker compose up -d
```

Cela lance :
- **MariaDB** sur le port `3306`
- **phpMyAdmin** accessible à l'adresse [http://localhost:8081](http://localhost:8081)

> Identifiants phpMyAdmin : `root` / `root`

### 5. Créer la base de données et exécuter les migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
remplir la base de donées
importer le fichier data.sql depuis PHPmyadmin

### 6. Lancer le serveur de développement

Avec la CLI Symfony :

```bash
symfony serve
```

Ou avec PHP intégré :

```bash
php -S localhost:8000 -t public/
```

L'application est accessible à l'adresse : [http://localhost:8000](http://localhost:8000)

---

## 👤 Créer un compte

Rendez-vous sur [http://localhost:8000/register-choice](http://localhost:8000/register-choice) pour choisir votre type de compte.

### 🏠 Compte Propriétaire (`/register-proprietaire`)

Remplissez le formulaire avec :
- Prénom, Nom
- Email
- Mot de passe
- Téléphone
- Adresse, Ville, Code postal

### 🔧 Compte Entrepreneur (`/register-entrepreneur`)

Remplissez le formulaire avec :
- Nom de l'entreprise
- Numéro SIRET
- Email
- Mot de passe
- Téléphone
- Adresse, Ville, Code postal

### 🔍 Compte Inspecteur (`/register-inspecteur`)

Remplissez le formulaire avec :
- Prénom, Nom
- Email
- Mot de passe
- Téléphone
- Adresse, Ville, Code postal

---

## 🎭 Rôles et fonctionnalités

### 🏠 Propriétaire (`ROLE_USER`)

- **Dashboard** (`/proprietaire/dashboard`) : liste de tous ses chantiers en cours, classés par bien immobilier.
- **Comparatif de devis** (`/proprietaire/devis-comparatif`) : consulte et compare les devis reçus pour chaque chantier.
- **Validation d'un devis** (`/proprietaire/devis/{id}/valider`) : accepte un devis (les autres sont automatiquement annulés) et marque le chantier en cours.

### 🔧 Entrepreneur (`ROLE_ENTREPRENEUR`)

- **Dashboard** (`/entrepreneur/dashboard`) : liste des appels d'offres disponibles et des devis déjà envoyés.
- **Compléter un devis** (`/devis/completer/{id}`) : remplit le formulaire de devis avec date de début, durée estimée et prix par prestation.
- **Soumettre un devis** (`/devis/submit/{id}`) : envoie le devis au propriétaire.
- **Gérer ses prestations** (`/entrepreneur/mes-prestations`) : sélectionne les catégories de travaux qu'il propose.
- **Terminer un chantier** (`/devis/terminer/{id}`) : marque un chantier comme terminé.

### 🔍 Inspecteur (`ROLE_INSPECTEUR`)

- **Dashboard** (`/inspecteur/dashboard`) : liste de tous les chantiers qui lui sont assignés.
- **Détail d'un chantier** (`/inspecteur/chantier/{id}`) : visualise les informations du chantier ainsi que sa localisation sur **Google Maps**.

---

## 📁 Structure du projet

```
2026-GR1-WEB-GESTRAVAUX/
├── assets/                   # JS & CSS (Stimulus, Turbo)
│   ├── controllers/          # Contrôleurs Stimulus
│   ├── styles/               # Feuilles de style CSS
│   └── app.js
├── config/                   # Configuration Symfony
├── migrations/               # Migrations Doctrine
├── public/                   # Point d'entrée web (index.php, images)
│   └── image/
│       ├── croisee_choix.png # Logo de l'application
│       └── pageChoix/        # Images des rôles (propriétaire, entrepreneur, inspecteur)
├── src/
│   ├── Controller/
│   │   ├── LoginController.php          # Connexion / Déconnexion / Redirection
│   │   ├── RegistrationController.php   # Inscription (3 types)
│   │   ├── ProprietaireController.php   # Espace propriétaire
│   │   ├── EntrepreneurController.php   # Espace entrepreneur
│   │   └── InspecteurController.php     # Espace inspecteur
│   ├── Entity/               # Entités Doctrine
│   │   ├── Utilisateur.php
│   │   ├── Bien.php
│   │   ├── Chantier.php
│   │   ├── Entrepreneur.php
│   │   ├── Inspecteur.php
│   │   ├── Prestataire.php
│   │   ├── Categorie.php
│   │   ├── DevisType.php
│   │   ├── DevisEntrepreneur.php
│   │   ├── DevisEntrepreneurPrestataire.php
│   │   ├── DevisTypePrestation.php
│   │   ├── Photo.php
│   │   └── Document.php
│   ├── Repository/           # Repositories Doctrine
│   ├── Security/
│   │   └── AuthenticationSuccessHandler.php  # Redirection après login
│   └── Services/
│       └── GeocodeService.php  # Géocodage via Google Maps API
├── templates/                # Vues Twig
│   ├── base.html.twig
│   ├── login/
│   ├── registration/
│   ├── proprietaire/
│   ├── entrepreneur/
│   └── inspecteur/
├── compose.yaml              # Docker Compose (MariaDB + phpMyAdmin)
├── composer.json
└── .env
```

---

## 🔐 Variables d'environnement

| Variable | Description | Exemple |
|---|---|---|
| `APP_ENV` | Environnement (`dev`, `prod`) | `dev` |
| `APP_SECRET` | Clé secrète Symfony | `4dd58e1fb276e73d...` |
| `DATABASE_URL` | URL de connexion à la BDD | `mysql://root:root@127.0.0.1:3306/app_db?...` |
| `GOOGLE_MAPS_API_KEY` | Clé API Google Maps | `AIzaSyC0ASX...` |
| `MAILER_DSN` | Configuration mailer | `null://null` |

---

## 🧰 Commandes utiles

```bash
# Vider le cache
php bin/console cache:clear

# Créer une migration après modification d'entité
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Voir les routes disponibles
php bin/console debug:router

# Démarrer/stopper Docker
docker compose up -d
docker compose down
```

---

## 🗺️ Routes principales

| Route | Méthode | Description |
|---|---|---|
| `/login` | GET/POST | Page de connexion |
| `/logout` | GET | Déconnexion |
| `/register-choice` | GET | Choix du type de compte |
| `/register-proprietaire` | GET/POST | Inscription propriétaire |
| `/register-entrepreneur` | GET/POST | Inscription entrepreneur |
| `/register-inspecteur` | GET/POST | Inscription inspecteur |
| `/proprietaire/dashboard` | GET | Dashboard propriétaire |
| `/proprietaire/devis-comparatif` | GET | Comparatif des devis |
| `/entrepreneur/dashboard` | GET | Dashboard entrepreneur |
| `/entrepreneur/mes-prestations` | GET | Gestion des prestations |
| `/inspecteur/dashboard` | GET | Dashboard inspecteur |
| `/inspecteur/chantier/{id}` | GET | Détail chantier + carte |

---

## 🐳 Accès aux services Docker

| Service | URL | Identifiants |
|---|---|---|
| Application Symfony | http://localhost:8000 | — |
| phpMyAdmin | http://localhost:8081 | `root` / `root` |
| MariaDB | localhost:3306 | `root` / `root` |
