# ARX AI Node — Architecture et feuille de route

> **État du code observé : 30 octobre 2026**  
> Documentation technique et pédagogique du microservice Python d’ARX. Les fonctionnalités observées dans les fichiers sont distinguées des étapes à construire. Aucun nouveau test d’exécution ni test Laravel de bout en bout n’a été effectué pour cette mise à jour.

## 1. Rôle du microservice

**ARX Core**, développé avec Laravel, gère l’interface et transmet les demandes. **ARX AI Node**, développé avec Python et FastAPI, valide les données et oriente les demandes vers des traitements spécialisés.

L’objectif est de construire progressivement un assistant de cybersécurité défensive : conversation, consultation de connaissances, puis analyse de données ARX SOC. Les capacités d’action nécessiteront des permissions et une validation humaine adaptées.

**Aujourd’hui :** les réponses restent simulées. Une première mémoire persistante et une transformation de l’historique en contexte sont présentes. Aucun LLM n’est connecté.

## 2. Architecture actuelle

```text
Interface Laravel / Blade
    ↓
ArxAiController → ArxAiService.php
    ↓ POST /ask
main.py → validation AskRequest
    ↓
services/arx_ai_service.py → process_prompt()
    ├── autre type → réponse « unsupported »
    └── conversation → handlers/conversation.py
                           ├── charge les 5 derniers échanges JSON
                           ├── construit les messages user / assistant
                           ├── produit une réponse simulée
                           └── enregistre le nouvel échange
    ↓
AskResponse → JSON → Laravel → Utilisateur
```

Le parcours Laravel est repris de la documentation précédente, qui signalait une validation de bout en bout le **23 octobre 2026**. Cette mise à jour repose sur la lecture du code Python actuel.

## 3. Responsabilité des fichiers

| Fichier | Responsabilité actuelle |
|---|---|
| `main.py` | Créer FastAPI et exposer `GET /` et `POST /ask` |
| `models/ask.py` | Définir `AskRequest` et `RequestContext` |
| `models/ask_response.py` | Définir le contrat de réponse |
| `services/arx_ai_service.py` | Orienter les demandes selon leur type |
| `handlers/conversation.py` | Charger le contexte, simuler une réponse et enregistrer l’échange |
| `services/conversation_engine.py` | Fichier préparatoire ; contient seulement un commentaire, sans fonction ni appel depuis le handler |
| `memory/conversation_store.py` | Lire et réécrire l’historique JSON |
| `memory/conversation_context.py` | Transformer les échanges en messages structurés |

La route HTTP reçoit les demandes ; le service choisit le traitement ; le handler organise le parcours conversationnel ; le futur moteur produira la réponse. La couche mémoire conserve les échanges et prépare leur contexte.

## 4. Contrat HTTP actuel

### Requête

`POST http://127.0.0.1:8001/ask`

```json
{
  "type": "conversation",
  "prompt": "Bonjour ARX AI !",
  "context": {
    "source": "arx-core"
  }
}
```

Tous ces champs sont requis. `type`, `prompt` et `source` sont actuellement de simples chaînes, sans contraintes explicites de longueur ni rejet spécifique d’un prompt vide.

`context.source` est reçu par le service, mais n’est pas utilisé dans son traitement. Il ne constitue pas une preuve d’identité.

### Réponse conversationnelle

```json
{
  "status": "success",
  "module": "arx-ai",
  "prompt": "Bonjour ARX AI !",
  "response": "ARX AI a reçu le message : Bonjour ARX AI !"
}
```

Un autre type reçoit `status: "unsupported"` avec un message explicatif. Le code actuel n’associe pas ce statut applicatif à un code d’erreur HTTP spécifique : cette réponse suit le chemin HTTP normal de la route.

Les entrées sont validées par Pydantic et les sorties par `response_model=AskResponse`.

## 5. Mémoire déjà présente

### Enregistrement

`save_message(prompt, response)` enregistre chaque échange dans `memory/conversations.json` avec :

- `timestamp` : date et heure UTC au format ISO ;
- `prompt` : message de l’utilisateur ;
- `response` : réponse de l’assistant.

Le chemin du fichier est construit à partir de l’emplacement du module Python. Si le fichier est absent, la sauvegarde initialise une nouvelle liste.

### Lecture et contexte

Le handler appelle `load_conversations(limit=5)` **avant** de sauvegarder le nouvel échange. Cette fonction lit le fichier entier, puis conserve les cinq derniers échanges dans leur ordre d’enregistrement.

`build_conversation_context(history)` transforme chaque échange en deux messages :

```json
[
  {"role": "user", "content": "Bonjour"},
  {"role": "assistant", "content": "Réponse précédente"}
]
```

Cinq échanges produisent donc jusqu’à dix messages. Le prompt courant n’est pas ajouté à cette liste par la fonction actuelle.

**Limite essentielle :** le contexte est construit et sa taille est affichée dans le terminal, mais il n’influence pas la réponse simulée.

### Limites du stockage actuel

- Un fichier commun à toutes les demandes, sans utilisateur ni identifiant de conversation.
- Lecture complète et réécriture complète à chaque sauvegarde.
- Risque de perte d’échanges lors d’écritures concurrentes.
- Absence de traitement explicite d’un JSON invalide ou d’une erreur de lecture/écriture.
- Absence de politique de conservation, de suppression et de limite globale de taille.

Cette mémoire convient à un prototype local individuel. L’isolation des conversations doit précéder une utilisation avec plusieurs utilisateurs.

## 6. Arborescence applicative observée

```text
arx-ai-node/
├── main.py
├── models/
│   ├── __init__.py
│   ├── ask.py
│   └── ask_response.py
├── services/
│   ├── arx_ai_service.py
│   └── conversation_engine.py
├── handlers/
│   ├── __init__.py
│   └── conversation.py
└── memory/
    ├── __init__.py
    ├── conversation_context.py
    ├── conversation_store.py
    └── conversations.json       # Créé/utilisé par la sauvegarde
```

L’arborescence présente les fichiers applicatifs utiles ; elle n’inventorie pas tous les fichiers du dépôt. Le contenu de l’historique utilisateur n’a pas été consulté pour rédiger ce document.

## 7. Démarrage local

Depuis `arx-ai-node` :

```bash
source .venv/bin/activate
uvicorn main:app --reload --port 8001
```

`main:app` désigne le fichier `main.py` et l’objet `app = FastAPI()`.

Dans un autre terminal, depuis la racine Laravel :

```bash
php artisan serve
```

- Disponibilité simple : `http://127.0.0.1:8001/`
- Documentation interactive : `http://127.0.0.1:8001/docs`

`GET /` indique que le serveur répond ; il ne vérifie pas l’état d’un futur LLM ni du stockage. `--reload` est réservé au développement. L’adresse locale devra être configurable pour d’autres environnements.

## 8. Feuille de route de codage

Les étapes suivantes sont des travaux prévus, pas des fonctionnalités déjà présentes. Chaque étape possède un résultat observable avant de passer à la suivante.

### Étape 1 — Relier le handler au moteur conversationnel

**Objectif :** donner une responsabilité réelle à `services/conversation_engine.py` sans changer immédiatement le contrat HTTP.

Travail :

1. Créer une fonction `generate_response(prompt, context)` dans ce module.
2. Y déplacer la construction de la réponse simulée.
3. Importer et appeler cette fonction depuis `handle_conversation()`.
4. Conserver le chargement de l’historique, la préparation du contexte et la sauvegarde dans le parcours du handler.
5. Définir clairement que `context` contient les échanges précédents et que `prompt` représente le message courant.

**Validation :** `/ask` fournit toujours la réponse attendue ; un échange est enregistré une seule fois ; le moteur reçoit le prompt et l’historique. Le fait de recevoir le contexte ne rend pas encore le moteur intelligent.

**Fichiers :** `services/conversation_engine.py`, `handlers/conversation.py`.

### Étape 2 — Isoler les conversations

**Objectif :** empêcher le mélange des historiques.

Travail :

1. Définir un `conversation_id` et son cycle de vie : création, transmission et reprise.
2. Ajouter cet identifiant au contrat d’API et adapter Laravel dans le même changement.
3. Le transmettre à travers route, service, handler et stockage.
4. Charger et sauvegarder uniquement les échanges de la conversation concernée.
5. Définir le traitement de l’ancien historique sans identifiant ; ne pas l’attribuer automatiquement à tous les utilisateurs.
6. Préparer l’association entre conversation et utilisateur authentifié.

Un identifiant de conversation permet de sélectionner un historique ; il ne donne pas, à lui seul, le droit de le consulter. L’identité et les permissions doivent venir d’un mécanisme de confiance.

**Validation :** deux conversations alternées ne partagent aucun message. Avant un usage multiutilisateur, vérifier aussi qu’un utilisateur ne peut pas accéder à la conversation d’un autre.

**Fichiers :** modèles, route, service, handler, mémoire et intégration Laravel.

### Étape 3 — Fiabiliser les entrées, le stockage et les erreurs

**Objectif :** obtenir un socle prévisible avant de dépendre d’un modèle externe ou local.

Travail :

- Rejeter un prompt vide après suppression des espaces et fixer une longueur maximale.
- Définir les valeurs de statut et la politique des types non pris en charge ; adapter Laravel si les codes HTTP changent.
- Définir les réponses en cas d’échec du moteur ou du stockage et éviter d’enregistrer une fausse réponse réussie.
- Choisir explicitement le comportement si une réponse est générée mais que sa sauvegarde échoue.
- Remplacer les `print()` de diagnostic par des journaux utiles, sans contenu sensible par défaut.
- Utiliser un stockage transactionnel, par exemple SQLite pour le développement local, avant de permettre plusieurs écritures concurrentes.
- Prévoir une reprise contrôlée des données JSON existantes, en conservant une copie avant migration.
- Ajouter des tests ciblés : contexte ordonné, isolation, données invalides, erreurs et sauvegarde.

**Validation :** une erreur ne produit pas un succès trompeur ; les échanges ne s’écrasent pas ; les cas invalides sont compréhensibles côté Laravel.

### Étape 4 — Connecter un premier LLM

**Objectif :** remplacer la simulation par une réponse réellement générée.

Travail :

1. Choisir un moteur local ou distant selon le matériel, la confidentialité, le coût et la latence acceptables. Aucun fournisseur n’est retenu dans ce document.
2. Garder les appels spécifiques au fournisseur derrière le moteur conversationnel.
3. Assembler les instructions d’ARX, l’historique autorisé et le prompt courant, sans dupliquer ce dernier.
4. Configurer modèle, adresse et éventuels secrets hors du code versionné.
5. Fixer des délais d’attente, des limites de génération et un traitement des indisponibilités.
6. Adapter le mode d’appel au client choisi pour éviter de bloquer inutilement le serveur.
7. Limiter le contexte selon le budget du modèle : cinq échanges ne garantissent pas une taille acceptable.

**Validation :** une question produit une réponse générée ; une relance utilise le contexte de la bonne conversation ; un arrêt du fournisseur donne une erreur maîtrisée ; aucun secret n’apparaît dans les journaux.

### Étape 5 — Sécuriser l’intégration et compléter l’interface

**Objectif :** préparer un usage partagé entre ARX Core et ARX AI Node.

Travail :

- Authentifier les appels entre services et vérifier les droits sur chaque conversation.
- Configurer les adresses, délais d’attente et erreurs côté Laravel.
- Prévoir les états d’attente, d’échec, de nouvelle conversation et de reprise dans l’interface.
- Limiter la fréquence des demandes et les ressources consommées.
- Définir la conservation et la suppression de l’historique.
- Afficher les réponses comme du contenu non fiable : échapper le texte et assainir tout rendu Markdown/HTML éventuel.
- Vérifier le parcours complet avec deux utilisateurs et plusieurs conversations.

**Validation :** un appel non autorisé est refusé, les historiques restent isolés et l’interface gère une indisponibilité du service.

Cette étape doit être terminée avant toute exposition partagée. Les contrôles nécessaires peuvent être avancés dès l’étape 2 selon l’environnement de test.

### Étape 6 — Ajouter une base de connaissances défensive

**Objectif :** répondre à partir de documents identifiés avec des références vérifiables.

Travail : importer un petit corpus maîtrisé, conserver les références et versions, rechercher les passages pertinents puis les fournir au moteur. Appliquer les droits d’accès avant de transmettre ces passages au modèle.

Les documents récupérés sont des données : leurs instructions éventuelles ne doivent pas modifier les permissions ni les règles d’ARX.

**Validation :** la réponse cite des passages réellement récupérés et signale quand les sources ne suffisent pas. Un document inaccessible à l’utilisateur n’entre pas dans le contexte.

### Étape 7 — Analyser les données ARX SOC en lecture seule

**Objectif :** produire une aide à l’analyse fondée sur les événements disponibles.

Travail : définir un contrat d’événements, brancher un accès autorisé en lecture seule, puis commencer par un cas limité comme le résumé d’une alerte. Distinguer faits observés, hypothèses et recommandations.

**Validation :** chaque conclusion est reliée aux événements analysés ; les données restent dans le périmètre autorisé ; l’analyse ne déclenche aucune action réseau.

### Étape 8 — Envisager des actions défensives contrôlées

**Objectif :** exécuter uniquement des opérations précisément définies et autorisées.

Travail : séparer proposition et exécution, créer une liste d’opérations permises, vérifier les paramètres et permissions côté code, demander une validation humaine pour les actions sensibles, journaliser le résultat et prévoir un retour arrière lorsque possible.

**Validation :** un texte produit par le modèle ne peut pas déclencher seul une opération ; une action non autorisée est refusée même si le modèle la demande.

## 9. Architecture cible progressive

```text
ARX Core : identité, interface et droits utilisateur
    ↓ appel interservice authentifié
FastAPI : validation et contrat HTTP
    ↓
Service : orientation de la demande
    ↓
Handler conversationnel : organisation du traitement
    ├── stockage isolé par conversation et utilisateur
    ├── construction d’un contexte borné
    ├── connaissances autorisées, dans une phase ultérieure
    └── moteur conversationnel
            ↓
        adaptateur du fournisseur → LLM
    ↓
Réponse et références éventuelles → sauvegarde → ARX Core
```

Les outils d’action formeront un parcours distinct, soumis à des contrôles de permissions et à une validation adaptée. Le modèle ne remplacera pas ces contrôles.

## 10. État d’avancement

| Élément | État au 30 octobre 2026 |
|---|---|
| API FastAPI, contrats Pydantic et routage | Présents dans le code |
| Intégration Laravel de bout en bout | Signalée comme validée le 23 octobre ; non retestée ici |
| Réponse conversationnelle simulée | Présente |
| Sauvegarde JSON horodatée et lecture des cinq derniers échanges | Présentes |
| Conversion de l’historique en messages `user` / `assistant` | Présente |
| Utilisation du contexte pour produire la réponse | À implémenter |
| Moteur conversationnel | Fichier préparatoire seulement |
| Isolation par conversation et utilisateur | À implémenter |
| LLM, RAG et analyse SOC | À implémenter |
| Authentification interservices et contrôle d’accès aux conversations | Non présents dans les fichiers Python examinés |
| Tests automatisés de ces parcours | À construire ; aucun n’a été exécuté pour cette mise à jour |

## 11. Point de reprise pour la prochaine séance

**Une seule priorité : terminer l’étape 1.**

Ouvrir `services/conversation_engine.py` et `handlers/conversation.py`, créer `generate_response(prompt, context)`, puis y déplacer la réponse simulée. Vérifier ensuite via Swagger que le parcours fonctionne et que l’échange est sauvegardé une seule fois.

Cette séance sert à terminer le branchement du moteur déjà préparé. Le choix et l’intégration du LLM viennent après l’isolation des conversations et la fiabilisation du socle.
