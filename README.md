# EventReserve — Application Web de Gestion de Réservations d'Événements

> Mini Projet | ISSAT Sousse | Année universitaire 2025-2026

## Description

Application web complète permettant aux utilisateurs de consulter des événements et d'effectuer des réservations en ligne, et à un administrateur de gérer les événements et les réservations via une interface sécurisée.

## Technologies utilisées

- **Backend** : Symfony 6/7 (PHP)
- **Base de données** : PostgreSQL 16
- **Frontend** : Twig, DM Sans + Lora (Google Fonts), CSS custom
- **Sécurité** : Symfony Security, CSRF protection, JWT (LexikJWTBundle), Passkeys/WebAuthn
- **ORM** : Doctrine
- **Conteneurisation** : Docker + Docker Compose

## Fonctionnalités

### Côté utilisateur

- Inscription et connexion (email/mot de passe)
- Consultation de la liste des événements disponibles
- Visualisation des détails d'un événement (titre, description, date, lieu, places restantes, image)
- Formulaire de réservation (nom, email, téléphone)
- Dashboard personnel : voir et annuler ses réservations
- Modification du compte (email, mot de passe)

### Côté administrateur

- Connexion sécurisée avec redirection automatique vers le dashboard admin
- Tableau de bord avec statistiques (nombre d'événements, réservations récentes)
- CRUD complet sur les événements (créer, lire, modifier, supprimer)
- Upload d'image pour chaque événement
- Consultation et gestion de toutes les réservations
- Déconnexion sécurisée

## Prérequis

- PHP 8.1+
- Composer 2.x
- PostgreSQL 14+
- Node.js (optionnel, pour assets)
- Docker & Docker Compose (pour la version conteneurisée)

## Installation

### Sans Docker

```bash
# 1. Cloner le dépôt
git clone https://github.com/Bouthayna123-hue/MiniProjet2A-EventReservation-BouthainaJenzeri.git
cd MiniProjet2A-EventReservation-BouthainaJenzeri

# 2. Installer les dépendances PHP
composer install

# 3. Configurer l'environnement
cp .env .env.local
# Éditer .env.local avec vos paramètres de base de données

# 4. Créer la base de données et les tables
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 5. Créer le dossier uploads
mkdir -p public/uploads/events

# 6. Lancer le serveur de développement
symfony server:start
# ou
php -S localhost:8000 -t public/
```

### Avec Docker

```bash
# 1. Cloner le dépôt
git clone https://github.com/votre-username/MiniProjet2A-EventReservation-BouthainaJenzeri.git
cd MiniProjet2A-EventReservation-BouthainaJenzeri

# 2. Copier et configurer les variables d'environnement
cp .env .env.local

# 3. Lancer les conteneurs
docker-compose up -d

# 4. Installer les dépendances
docker-compose exec php composer install

# 5. Créer la base de données
docker-compose exec php php bin/console doctrine:migrations:migrate

# 6. Accéder à l'application
# http://localhost:8080
```

## Configuration

### Variables d'environnement (.env.local)

```env
# Base de données
DATABASE_URL="postgresql://postgres:password@127.0.0.1:5432/app?serverVersion=16&charset=utf8"

# JWT
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase_here
JWT_TOKEN_TTL=3600

# WebAuthn / Passkeys
APP_DOMAIN=localhost
WEBAUTHN_RP_NAME="EventReserve"
```

### Créer un compte administrateur

```sql
UPDATE "user" SET roles = '["ROLE_ADMIN"]' WHERE email = 'admin@example.com';
```

## Structure du projet

```
├── src/
│   ├── Controller/
│   │   ├── AdminController.php        # Dashboard admin
│   │   ├── EventCrudController.php    # CRUD événements (admin)
│   │   ├── EventController.php        # Liste publique événements
│   │   ├── ReservationController.php  # Gestion réservations
│   │   ├── UserController.php         # Dashboard utilisateur
│   │   └── SecurityController.php    # Login/logout
│   ├── Entity/
│   │   ├── Event.php
│   │   ├── Reservation.php
│   │   └── User.php
│   ├── Form/
│   │   ├── EventType.php
│   │   ├── ReservationType.php
│   │   ├── AccountType.php
│   │   └── RegistrationFormType.php
│   ├── Repository/
│   └── Security/
│       └── AppAuthenticator.php
├── templates/
│   ├── admin/
│   ├── event/
│   ├── event_crud/
│   ├── reservation/
│   ├── user/
│   └── security/
├── public/
│   └── uploads/
│       └── events/
├── config/
│   └── packages/
│       └── security.yaml
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Routes principales

| URL                     | Rôle       | Description              |
| ----------------------- | ---------- | ------------------------ |
| `/`                     | Public     | Page d'accueil           |
| `/login`                | Public     | Connexion                |
| `/register`             | Public     | Inscription              |
| `/event`                | Public     | Liste des événements     |
| `/reservation/new/{id}` | ROLE_USER  | Réserver un événement    |
| `/dashboard`            | ROLE_USER  | Dashboard utilisateur    |
| `/admin`                | ROLE_ADMIN | Dashboard administrateur |
| `/event/crud`           | ROLE_ADMIN | Gestion des événements   |
| `/reservation`          | ROLE_ADMIN | Gestion des réservations |

## Branches Git

```
main          → code stable et fonctionnel
dev           → intégration et tests
feature/auth  → authentification JWT + Passkeys
feature/ui    → interface utilisateur
feature/crud  → CRUD événements et réservations
```

## Auteur

- **Nom** : Bouthaina Jenzeri
- **Email** : [jenzribouthayna18@gmail.com]
- **Établissement** : ISSAT Sousse
- **Année** : 2025-2026
