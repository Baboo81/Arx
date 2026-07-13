# Philosophie de l'architecture

ARX repose sur une architecture modulaire.

Le projet est divisé en deux parties :

- **ARX Core** : le noyau de la plateforme. Il fournit tous les services communs (authentification, utilisateurs, permissions, configuration, journalisation, notifications, API interne...).

- **Modules** : chaque fonctionnalité métier (VPN, Server, SOC, AI, Cloud...) est développée indépendamment et consomme les services du Core.

## Principe fondamental

Les dépendances vont toujours dans une seule direction :

Modules
      │
      ▼
  ARX Core

Le Core ne dépend jamais d'un module.

Chaque module reste autonome afin de garantir :

- une faible dépendance (Low Coupling)
- une forte cohésion (High Cohesion)
- une maintenance simplifiée
- une meilleure évolutivité
- une réutilisation maximale du code

Cette architecture permet d'ajouter de nouveaux modules sans modifier le cœur de la plateforme.

# ARX - Architecture du projet

```text
ARX/
│
├── app/
│   │
│   ├── Core/
│   │   │
│   │   ├── Authentication/
│   │   ├── Authorization/
│   │   ├── Audit/
│   │   ├── Configuration/
│   │   ├── Contracts/
│   │   ├── DTO/
│   │   ├── Events/
│   │   ├── Exceptions/
│   │   ├── Helpers/
│   │   ├── Notifications/
│   │   ├── Policies/
│   │   ├── Providers/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Traits/
│   │   └── Utils/
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Models/
│   │
│   └── Providers/
│
├── Modules/
│   │
│   ├── AI/
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Events/
│   │   ├── Models/
│   │   ├── Policies/
│   │   ├── Repositories/
│   │   ├── Routes/
│   │   ├── Services/
│   │   └── Views/
│   │
│   ├── Cloud/
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Events/
│   │   ├── Models/
│   │   ├── Policies/
│   │   ├── Repositories/
│   │   ├── Routes/
│   │   ├── Services/
│   │   └── Views/
│   │
│   ├── Server/
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Events/
│   │   ├── Models/
│   │   ├── Policies/
│   │   ├── Repositories/
│   │   ├── Routes/
│   │   ├── Services/
│   │   └── Views/
│   │
│   ├── SOC/
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Events/
│   │   ├── Models/
│   │   ├── Policies/
│   │   ├── Repositories/
│   │   ├── Routes/
│   │   ├── Services/
│   │   └── Views/
│   │
│   └── VPN/
│       ├── Config/
│       ├── Controllers/
│       ├── Events/
│       ├── Models/
│       ├── Policies/
│       ├── Repositories/
│       ├── Routes/
│       ├── Services/
│       └── Views/
│
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│
├── storage/
│
├── tests/
│
├── artisan
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

# réalisation de la maquette 
- XD

# Architecture ARX
- ARX Core
    - ARX Server
    - ARX AI
    - ARX SOC
    - ARX Cloud
    - ARX VPN
# Étape n°1: création de la partie ARX Core
- [V] Création de l'architecture 
# Étape n°2: construction de Arx Core (Kernel)
ARX Core

│

├── 1. Configuration

├── 2. Services

├── 3. Contrats

├── 4. Providers

├── 5. Authentification

├── 6. Utilisateurs

├── 7. Permissions

├── 8. API

---

# Journal de développement

## Séance du 13/07/2026

### ✅ Objectifs réalisés

#### Architecture générale

- Création de l'architecture d'ARX Core.
- Création du dossier `app/Core`.
- Organisation des différents sous-dossiers du Core :
  - Authentication
  - Authorization
  - Audit
  - Configuration
  - Contracts
  - DTO
  - Events
  - Exceptions
  - Helpers
  - Notifications
  - Policies
  - Providers
  - Repositories
  - Services
  - Traits
  - Utils

#### Architecture modulaire

Création du dossier `Modules` à la racine du projet.

Modules créés :

- AI
- Cloud
- Server
- SOC
- VPN

Chaque module possède désormais sa propre structure afin de garantir une architecture modulaire et indépendante.

#### ARX Core

Début du développement du composant **Configuration**.

Création des fichiers :

- ConfigurationContract.php
- ConfigurationProvider.php
- ConfigurationRepository.php
- ConfigurationService.php

Ces fichiers serviront de base au gestionnaire centralisé de configuration de toute la plateforme.

#### Réflexion architecturale

Les principes suivants ont été définis :

- ARX Core constitue le cœur de la plateforme.
- Tous les modules utilisent les services du Core.
- Le Core ne dépend d'aucun module.
- Les modules restent totalement indépendants.
- Architecture orientée faible couplage et forte cohésion.
- ARX est pensé comme une plateforme modulaire et évolutive.

---

# À faire lors de la prochaine séance

## 1. Création du modèle Setting

Créer le modèle Laravel :

```bash
php artisan make:model Setting -m
```

Cette commande générera :

- le modèle `Setting`
- la migration `create_settings_table`

---

## 2. Compléter le modèle

Ajouter les propriétés `$fillable` :

- module
- key
- value
- type

---

## 3. Construire la migration

Créer la table `settings` avec les colonnes :

- id
- module
- key
- value
- type
- timestamps

Ajouter une contrainte d'unicité :

```php
$table->unique(['module', 'key']);
```

---

## 4. Exécuter la migration (Je suis arrivée à cette étape !!!!!!)

```bash
php artisan migrate
```

---

## 5. Développer le ConfigurationRepository

Objectif :

Créer le premier composant métier d'ARX Core.

Il devra permettre de :

- récupérer un paramètre
- enregistrer un paramètre
- modifier un paramètre
- supprimer un paramètre

Ce repository deviendra le point d'accès unique aux paramètres de la plateforme.

---

## 6. Développer le ConfigurationService

Le service utilisera le repository afin de fournir une API simple aux autres modules.

Exemple d'utilisation futur :

```php
$config = app(ConfigurationService::class);

$config->get('vpn.default_port');
$config->set('vpn.default_port', 51820);
```

---

## Objectif global

Construire progressivement **ARX Core** avant de développer les modules (VPN, SOC, AI, Server, Cloud).

L'objectif est d'obtenir une plateforme robuste, modulaire et facilement extensible.

---

### État d'avancement

Architecture : ██████████░░░░░░░░░░ 40 %

ARX Core : █░░░░░░░░░░░░░░░░░░░░░ 5 %

Modules : ░░░░░░░░░░░░░░░░░░░░░░ 0 %

Authentification : ░░░░░░░░░░░░░░░░░░░░░ 0 %

Configuration : ██░░░░░░░░░░░░░░░░░░░ 10 %

Journalisation : ░░░░░░░░░░░░░░░░░░░░░ 0 %

Notifications : ░░░░░░░░░░░░░░░░░░░░░ 0 %