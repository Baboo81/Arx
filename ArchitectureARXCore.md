        
# ARX Core — Programme de réalisation

## 1. Objectif

ARX Core constitue le **noyau central et la colonne vertébrale de la plateforme ARX**.

Son rôle est de fournir les services communs utilisés par les différents modules :

* ARX Server
* ARX VPN
* ARX SOC
* ARX AI
* ARX Cloud
* Tableau de bord ARX

Chaque module conserve sa propre responsabilité et son autonomie. ARX Core fournit les mécanismes communs nécessaires à leur fonctionnement.

L'objectif principal est de construire une architecture **modulaire, cohérente, faiblement couplée et évolutive**.

---

# 2. Architecture générale

```text
                         ┌─────────────────────┐
                         │      ARX CORE       │
                         │                     │
                         │  Identity           │
                         │  Authentication     │
                         │  Authorization      │
                         │  Configuration      │
                         │  API                │
                         │  Events             │
                         │  Audit              │
                         │  Notifications      │
                         └──────────┬──────────┘
                                    │
              ┌─────────────────────┼─────────────────────┐
              │                     │                     │
              ▼                     ▼                     ▼
        ┌───────────┐         ┌───────────┐         ┌───────────┐
        │ ARX VPN   │         │ ARX SERVER│         │  ARX SOC  │
        └───────────┘         └───────────┘         └───────────┘
                                    │
                              ┌─────▼─────┐
                              │  ARX AI   │
                              └───────────┘
```

Principe fondamental :

> Les modules ARX ne doivent pas dépendre directement les uns des autres lorsque la fonctionnalité concerne un service commun.

Ils utilisent les interfaces et services fournis par ARX Core.

---

# 3. Phase 0 — Comprendre et concevoir l'architecture

Avant de développer, définir précisément :

* les responsabilités d'ARX Core ;
* les responsabilités de chaque module ;
* les frontières entre les modules ;
* les données communes ;
* les interfaces de communication ;
* les conventions de développement ;
* les principes de sécurité ;
* les règles de dépendance entre composants.

### Concepts à maîtriser

* Couplage faible
* Cohésion forte
* Séparation des responsabilités
* Interfaces
* API
* Services
* Événements
* Architecture modulaire

---

# 4. Phase 1 — Fondations techniques

Mettre en place le socle du projet.

### Technologies envisagées

* Laravel
* PostgreSQL
* Redis
* API REST

### Objectifs

* créer le projet ARX Core ;
* configurer l'environnement ;
* définir l'architecture du code ;
* configurer la base de données ;
* définir le modèle de données commun ;
* établir les conventions du projet.

Cette phase doit produire un **Core minimal fonctionnel**, mais sans encore chercher à implémenter toutes les fonctionnalités.

---

# 5. Phase 2 — Authentification

Construire le système permettant de vérifier l'identité des utilisateurs.

### Fonctionnalités

* connexion ;
* déconnexion ;
* gestion des sessions ;
* réinitialisation des accès ;
* sécurisation des comptes ;
* authentification multifacteur (MFA) ;
* gestion des appareils de confiance.

### Technologies à étudier

* Laravel Breeze ou Laravel Fortify ;
* mécanismes d'authentification Laravel ;
* sessions ;
* tokens ;
* principes des JWT.

Objectif :

> ARX Core doit devenir la référence concernant l'identité des utilisateurs.

---

# 6. Phase 3 — Gestion des utilisateurs et autorisation

Une fois l'identité établie, construire le système permettant de déterminer **ce que chaque utilisateur a le droit de faire**.

### Gestion des utilisateurs

* création de comptes ;
* profils ;
* désactivation ;
* équipes ;
* historique des connexions.

### Autorisation

* rôles ;
* permissions ;
* RBAC ;
* politiques d'accès ;
* groupes d'utilisateurs.

Exemple conceptuel :

```text
Utilisateur
    │
    ▼
   Rôle
    │
    ▼
Permissions
    │
    ▼
Action autorisée
```

Objectif :

> Séparer clairement l'identité de l'utilisateur de ses autorisations.

---

# 7. Phase 4 — Configuration centralisée

ARX Core devient le point central de gestion des paramètres communs.

### Types de configuration

* paramètres généraux ;
* configuration des modules ;
* paramètres réseau ;
* variables d'environnement applicatives ;
* préférences utilisateur.

### Fonctionnalités

* consultation ;
* modification ;
* activation/désactivation de modules ;
* gestion des préférences.

Principe :

> Les paramètres communs doivent être centralisés plutôt que dupliqués dans chaque module.

---

# 8. Phase 5 — Communication entre les modules

Construire les mécanismes permettant aux composants ARX de communiquer.

### À étudier

* API REST ;
* échanges de données ;
* services internes ;
* événements ;
* files de messages ;
* principes des architectures asynchrones.

### Technologies possibles

* API REST ;
* Redis / Redis Streams ;
* RabbitMQ ou équivalent.

Objectif :

```text
ARX VPN
    │
    │ API / Events
    ▼
ARX CORE
    ▲
    │ API / Events
    │
ARX SERVER
```

Les modules doivent communiquer à travers des **interfaces clairement définies**.

---

# 9. Phase 6 — Journalisation et audit

Mettre en place la traçabilité de la plateforme.

### Fonctionnalités

* journaux d'activité ;
* historique des actions ;
* audit ;
* traçabilité ;
* horodatage ;
* rapports.

Exemples d'événements :

```text
Utilisateur connecté
Utilisateur créé
Permission modifiée
Configuration modifiée
Module activé
Action administrative effectuée
```

Objectif :

> Pouvoir déterminer qui a effectué quelle action, quand et dans quel contexte.

---

# 10. Phase 7 — Notifications

Créer un système commun permettant à ARX d'informer les utilisateurs.

### Canaux

* notifications dans l'application ;
* courrier électronique ;
* notifications push à terme ;
* webhooks.

Les notifications doivent être conçues comme un **service commun**, utilisable par les différents modules.

---

# 11. Phase 8 — API ARX Core

Formaliser les interfaces permettant aux autres modules d'utiliser Core.

Exemples conceptuels :

```text
Authentication API
User API
Authorization API
Configuration API
Audit API
Notification API
```

L'API doit permettre aux modules de consommer les services de Core sans connaître leur implémentation interne.

---

# 12. Phase 9 — Premier module consommateur

Ne pas construire Core entièrement isolé.

Créer un premier module ARX simplifié afin de vérifier l'architecture.

Exemple :

```text
ARX Core
    │
    ├── Authentication
    ├── Authorization
    ├── API
    ├── Configuration
    └── Audit
             ▲
             │
        ARX VPN
```

Le module devra pouvoir :

* s'authentifier auprès de Core ;
* identifier un utilisateur ;
* vérifier une permission ;
* récupérer une configuration ;
* envoyer un événement ;
* générer une entrée d'audit.

Cette étape permettra de vérifier que l'architecture fonctionne **dans la pratique**.

---

# 13. Phase 10 — Intégration des modules ARX

Une fois Core stabilisé, intégrer progressivement :

```text
ARX Core
   │
   ├── ARX VPN
   ├── ARX Server
   ├── ARX SOC
   ├── ARX AI
   └── ARX Cloud
```

Chaque module devra :

* utiliser l'authentification commune ;
* utiliser les mécanismes d'autorisation ;
* utiliser les services communs ;
* communiquer via les interfaces définies ;
* respecter les frontières architecturales.

---

# 14. Principes fondamentaux du développement

Pendant toute la réalisation, respecter les règles suivantes :

### 1. Core ne fait pas tout

ARX Core fournit les services communs.

Il ne doit pas absorber les responsabilités propres aux modules.

### 2. Un module = une responsabilité

Chaque module doit conserver une responsabilité claire.

### 3. Faible couplage

Éviter les dépendances directes inutiles entre modules.

### 4. Interfaces clairement définies

Les modules communiquent à travers des contrats définis.

### 5. Sécurité par conception

L'authentification, l'autorisation, l'audit et la traçabilité doivent être considérés dès la conception.

### 6. Évolutivité

L'architecture doit permettre d'ajouter un nouveau module sans devoir reconstruire ARX Core.

---

# 15. Évolutions futures

Une fois le Core stable, plusieurs évolutions pourront être envisagées :

* catalogue de modules / plugins ;
* API publique documentée ;
* marketplace d'extensions ;
* multi-tenant ;
* gestion centralisée des licences ;
* automatisation ;
* moteur de workflows ;
* bus d'événements interne ;
* fédération de plusieurs instances ARX.

Ces fonctionnalités ne font **pas partie du premier objectif de développement**.

---

# 16. Ordre pédagogique recommandé

Pour apprendre en construisant, l'ordre de travail sera :

```text
1. Architecture
       ↓
2. Responsabilités des modules
       ↓
3. Modèle de données
       ↓
4. Laravel / PostgreSQL
       ↓
5. Authentification
       ↓
6. Utilisateurs
       ↓
7. Rôles & Permissions
       ↓
8. Configuration
       ↓
9. API REST
       ↓
10. Communication inter-modules
       ↓
11. Events / Messages
       ↓
12. Audit & Logs
       ↓
13. Notifications
       ↓
14. Premier module consommateur
       ↓
15. Intégration ARX
```

## Objectif final

Construire un **ARX Core robuste et indépendant**, capable de fournir les services fondamentaux de la plateforme tout en laissant à chaque module son autonomie.

Le résultat recherché n'est pas simplement une application Laravel.

C'est une **architecture de plateforme** capable d'évoluer dans le temps.

> **ARX Core = le socle commun.**
>
> **Les modules ARX = les briques spécialisées.**
>
> **Les interfaces = les contrats qui permettent aux briques de coopérer.**

## Sécurisation 

Email + mot de passe
        ↓
Fortify authentifie l'utilisatrice
        ↓
Compte protégé par 2FA détecté
        ↓
/two-factor-challenge
        ↓
Code TOTP généré par l'iPhone
        ↓
Fortify valide le code
        ↓
Session authentifiée
        ↓
ARX HOME 🛡️
