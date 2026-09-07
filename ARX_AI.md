# Structure 

ARX AI
│
├── LLM
│     compréhension / explication
│
├── Machine Learning
│     détection d'anomalies
│
├── Rules engine
│     règles déterministes
│
└── Threat intelligence
      CVE / IOC / MITRE

# ARX AI — Feuille de route

## 1. Vision

ARX AI constitue le **cerveau défensif intelligent** de la plateforme ARX.

Son rôle sera d'assister ARX Core et les différents modules de la plateforme dans :

* l'analyse des événements ;
* la corrélation des informations provenant du réseau et du SOC ;
* l'analyse des logs ;
* la détection de comportements anormaux ;
* l'aide aux audits de sécurité ;
* l'identification de menaces potentielles ;
* l'explication des incidents ;
* la proposition de réponses adaptées.

> **Principe fondamental : ARX AI analyse et recommande. ARX Core contrôle et autorise.**

ARX AI ne devra donc pas pouvoir exécuter librement une action sensible sur l'infrastructure.

---

# 2. Architecture cible

```text
Utilisateur
    │
    ▼
Interface ARX AI
    │
    ▼
ARX Core / Laravel
    │
    ├── Authentification
    ├── Autorisations
    ├── Validation
    ├── Journalisation
    │
    ▼
ARX AI Service
Python
    │
    ├── LLM
    ├── RAG cybersécurité
    ├── Règles déterministes
    ├── Détection d'anomalies / ML
    └── Threat Intelligence
    │
    ▼
Réponse structurée
    │
    ▼
ARX Core
    │
    ▼
Interface utilisateur
```

À terme, les calculs IA lourds pourront être exécutés sur un **ARX AI Node dédié**, tandis que le Raspberry Pi 5 conservera principalement son rôle d'ARX Core et d'orchestrateur.

---

# 3. Phase 1 — Interface ARX AI

## Objectif

Rendre la page `arx/ai` réellement interactive avant d'intégrer un modèle d'intelligence artificielle.

### À réaliser

* [x] Créer la page ARX AI
* [x] Créer l'identité visuelle de l'interface
* [x] Créer les composants Blade ARX
* [x] Créer le champ de saisie
* [x] Créer le bouton ARX
* [ ] Transformer le formulaire en véritable formulaire POST
* [ ] Ajouter une route POST dédiée
* [ ] Créer la méthode du contrôleur
* [ ] Valider la requête utilisateur
* [ ] Retourner une réponse simulée
* [ ] Afficher la réponse dans la zone ARX AI
* [ ] Gérer les erreurs

### Premier objectif fonctionnel

```text
Question utilisateur
        ↓
Laravel
        ↓
Contrôleur ARX AI
        ↓
Réponse simulée
        ↓
Interface
```

À ce stade, aucune véritable IA n'est encore nécessaire.

---

# 4. Phase 2 — Format interne ARX AI

Avant de connecter un LLM, définir un format d'échange commun.

Exemple conceptuel :

```json
{
    "source": "arx_core",
    "type": "user_query",
    "severity": "info",
    "message": "Analyse cette activité réseau",
    "timestamp": "...",
    "context": {}
}
```

### Objectifs

* [ ] Définir le format interne des événements ARX
* [ ] Définir les types d'événements
* [ ] Définir les niveaux de criticité
* [ ] Définir le format des réponses ARX AI
* [ ] Prévoir les métadonnées nécessaires
* [ ] Prévoir l'identification de la source

Ce format deviendra progressivement le **langage commun entre ARX Core, SOC, Server, VPN et AI**.

---

# 5. Phase 3 — Création du service Python ARX AI

ARX AI sera séparé de Laravel.

Laravel restera l'orchestrateur tandis que Python prendra en charge les traitements liés à l'intelligence artificielle.

```text
Laravel
   │
   │ API interne
   ▼
Python ARX AI
```

### À réaliser

* [ ] Créer un environnement Python dédié
* [ ] Créer le projet `arx-ai`
* [ ] Mettre en place une API interne
* [ ] Créer un endpoint de test
* [ ] Tester Laravel → Python
* [ ] Tester Python → Laravel
* [ ] Définir les réponses JSON
* [ ] Ajouter la gestion des erreurs
* [ ] Ajouter timeouts et contrôles
* [ ] Sécuriser les communications internes

---

# 6. Phase 4 — Premier moteur LLM

Une fois l'architecture Laravel ↔ Python fonctionnelle, connecter un premier LLM.

### Objectifs

* [ ] Créer une abstraction permettant de changer de modèle
* [ ] Envoyer une requête au modèle
* [ ] Recevoir sa réponse
* [ ] Structurer les réponses
* [ ] Définir le rôle système d'ARX AI
* [ ] Limiter les capacités du modèle
* [ ] Journaliser les requêtes importantes
* [ ] Gérer les erreurs et indisponibilités du modèle

Le LLM ne doit pas devenir ARX AI à lui seul.

Il constitue **une des briques du moteur ARX AI**.

---

# 7. Phase 5 — RAG cybersécurité

Ajouter une base documentaire spécialisée permettant à ARX AI de rechercher des informations fiables avant de répondre.

### Sources possibles

* documentation ARX ;
* procédures internes ;
* documentation réseau ;
* documentation Linux ;
* documentation des services ARX ;
* bases de connaissances cybersécurité ;
* procédures d'incident ;
* informations sur les vulnérabilités.

### Pipeline

```text
Question
   ↓
Recherche documentaire
   ↓
Documents pertinents
   ↓
Contexte
   ↓
LLM
   ↓
Réponse ARX AI
```

### À réaliser

* [ ] Étudier les embeddings
* [ ] Choisir le stockage vectoriel
* [ ] Créer l'index documentaire
* [ ] Importer les premières documentations
* [ ] Effectuer la recherche sémantique
* [ ] Injecter le contexte au LLM
* [ ] Conserver les références utilisées

---

# 8. Phase 6 — Règles déterministes

Toutes les décisions ne doivent pas dépendre d'une IA générative.

Certaines situations doivent être traitées par des règles strictes.

Exemple conceptuel :

```text
SI
    échecs SSH > seuil
ET
    plusieurs utilisateurs ciblés
ET
    même adresse IP source

ALORS
    suspicion = attaque brute force
```

### Objectifs

* [ ] Créer le moteur de règles
* [ ] Définir les premières règles de sécurité
* [ ] Ajouter des niveaux de criticité
* [ ] Corréler règles et analyse LLM
* [ ] Permettre à ARX AI d'expliquer le déclenchement d'une règle

---

# 9. Phase 7 — Connexion avec ARX SOC

ARX AI commencera alors à recevoir de véritables événements de sécurité.

Sources possibles :

```text
Suricata
Linux
SSH
Firewall
VPN
ARX Server
DNS
Monitoring
```

### Pipeline

```text
Capteurs
   ↓
ARX SOC
   ↓
Normalisation
   ↓
ARX Core
   ↓
ARX AI
   ↓
Analyse / corrélation
```

### À réaliser

* [ ] Recevoir les événements SOC
* [ ] Normaliser les événements
* [ ] Classifier les événements
* [ ] Corréler plusieurs événements
* [ ] Produire un niveau de risque
* [ ] Générer une explication
* [ ] Proposer une réponse

---

# 10. Phase 8 — Détection d'anomalies

Ajouter progressivement des mécanismes statistiques et Machine Learning.

Exemples :

* volume réseau inhabituel ;
* connexion à une heure inhabituelle ;
* augmentation soudaine des erreurs ;
* comportement réseau atypique ;
* fréquence anormale de connexions ;
* nouveaux appareils ou services.

### Objectifs

* [ ] Établir des comportements de référence
* [ ] Collecter les données nécessaires
* [ ] Détecter les écarts
* [ ] Attribuer un score d'anomalie
* [ ] Envoyer les anomalies à ARX AI
* [ ] Corréler anomalies et événements SOC

---

# 11. Phase 9 — Threat Intelligence

ARX AI pourra enrichir une observation avec des informations externes.

Exemples :

```text
IP suspecte
CVE
Domaine
Hash
Signature
Technique d'attaque
```

### Objectifs

* [ ] Créer le module Threat Intelligence
* [ ] Interroger différentes sources
* [ ] Mettre en cache les résultats
* [ ] Corréler les renseignements avec les événements locaux
* [ ] Évaluer leur fiabilité
* [ ] Fournir ces informations au moteur d'analyse

---

# 12. Phase 10 — Moteur de décision ARX AI

Les différentes briques pourront alors travailler ensemble.

```text
                Événement
                    │
                    ▼
              ARX AI Engine
                    │
       ┌────────────┼────────────┐
       ▼            ▼            ▼
     Rules         RAG        Anomaly
       │            │            │
       └────────────┼────────────┘
                    │
                    ▼
             Threat Intelligence
                    │
                    ▼
                   LLM
                    │
                    ▼
            Analyse structurée
```

Une réponse pourrait contenir :

```text
Classification
Niveau de risque
Éléments observés
Hypothèse
Preuves disponibles
Niveau de confiance
Explication
Actions recommandées
```

---

# 13. Phase 11 — Actions encadrées

ARX AI pourra proposer des actions mais ne devra pas les exécuter librement.

Exemple :

```text
ARX AI
   │
   │ recommande
   ▼
Bloquer IP 192.168.x.x
   │
   ▼
ARX Core
   │
   ├── Vérification des permissions
   ├── Validation des règles
   ├── Confirmation utilisateur
   └── Journalisation
   │
   ▼
Module concerné
```

### Règle fondamentale

```text
ARX AI ≠ autorité système

ARX Core = autorité système
```

---

# 14. Phase 12 — ARX AI Node

Lorsque les besoins dépasseront les capacités du Raspberry Pi 5, déplacer les traitements IA lourds vers une machine dédiée.

```text
Raspberry Pi 5
ARX Core
      │
      │ réseau interne sécurisé
      ▼
ARX AI Node
      │
      ├── LLM
      ├── RAG
      ├── Vector DB
      ├── Machine Learning
      └── Analyse
```

Cela permettra de faire évoluer la puissance d'ARX AI indépendamment du Core.

---

# Principes de sécurité ARX AI

ARX AI devra respecter plusieurs principes dès sa conception :

* moindre privilège ;
* séparation Core / AI ;
* aucune exécution arbitraire de commandes ;
* validation des actions sensibles ;
* journalisation ;
* traçabilité des décisions ;
* contrôle des données envoyées au LLM ;
* réponses structurées ;
* gestion des erreurs ;
* authentification entre services ;
* limitation des ressources ;
* possibilité de désactiver immédiatement ARX AI.

---

# Ordre de développement

```text
01  Interface ARX AI
        ↓
02  POST Laravel + validation
        ↓
03  Première réponse simulée
        ↓
04  Format événement ARX
        ↓
05  Microservice Python
        ↓
06  Communication Laravel ↔ Python
        ↓
07  Premier LLM
        ↓
08  RAG cybersécurité
        ↓
09  Règles déterministes
        ↓
10  Connexion ARX SOC
        ↓
11  Détection d'anomalies
        ↓
12  Threat Intelligence
        ↓
13  Corrélation / moteur de décision
        ↓
14  Actions contrôlées par ARX Core
        ↓
15  ARX AI Node dédié
```

# Prochaine étape

Lors de la prochaine session :

**ARX AI — Phase 1 : faire parler l'interface.**

Premier objectif :

```text
Je saisis une requête
        ↓
Je clique sur Envoyer
        ↓
Laravel reçoit la requête
        ↓
Laravel la valide
        ↓
ARX AI génère une réponse simulée
        ↓
La réponse apparaît dans l'interface
```

Une fois cette chaîne maîtrisée, nous pourrons remplacer progressivement la simulation par le véritable moteur ARX AI.
