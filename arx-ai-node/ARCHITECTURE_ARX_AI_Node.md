# ARX AI Node — Architecture

> **État du projet : 23 octobre 2026**  
> Documentation technique et pédagogique du microservice Python d'ARX.

## 1. Rôle d'ARX AI Node

**ARX AI Node** est le microservice Python destiné aux traitements d'intelligence artificielle de la plateforme ARX. Il est séparé d'**ARX Core**, développé avec Laravel.

- **ARX Core** orchestre la plateforme, gère l'interface et transmet les demandes.
- **ARX AI Node** expose une API FastAPI, valide les données et distribue les demandes vers des traitements spécialisés.

**Situation actuelle :** le parcours technique fonctionne de bout en bout, mais aucune véritable intelligence artificielle ni aucun LLM n'est encore connecté. Les réponses conversationnelles sont simulées.

---

## 2. Architecture actuelle

```text
Utilisateur
    │
    ▼
Interface ARX AI (Blade / Laravel)
    │
    ▼
ArxAiController (Laravel)
    │
    ▼
app/Services/ArxAiService.php
    │  POST http://127.0.0.1:8001/ask
    │  JSON : type, prompt, context.source
    ▼
main.py (FastAPI) — POST /ask
    │  validation AskRequest (Pydantic)
    ▼
services/arx_ai_service.py
    │  process_prompt(prompt, request_type, source)
    │
    ├── type == "conversation"
    │       │
    │       ▼
    │   handlers/conversation.py
    │       handle_conversation(prompt)
    │       │
    │       ▼
    │   status = "success"
    │
    └── autre type
            │
            ▼
        status = "unsupported"
    │
    ▼
AskResponse (Pydantic) — contrat de sortie
    │
    ▼
Réponse JSON (FastAPI)
    │
    ▼
ArxAiService.php → ArxAiController → Blade
    │
    ▼
Utilisateur
```

**Principe :** `main.py` s'occupe du transport HTTP, les modèles Pydantic du contrat de données, le service de l'orientation et les handlers des traitements spécialisés.

---

## 3. Communication entre Laravel et FastAPI

ARX Core est **client HTTP** ; ARX AI Node est **serveur d'API**.

**Adresse locale de développement :** `http://127.0.0.1:8001/ask`  
**Méthode :** `POST`  
**Format :** `application/json`

Exemple de requête valide :

```json
{
  "type": "conversation",
  "prompt": "Bonjour ARX AI !",
  "context": {
    "source": "arx-core"
  }
}
```

Dans Laravel, le service `app/Services/ArxAiService.php` prépare notamment :

```php
$requestData = [
    'type' => 'conversation',
    'prompt' => $prompt,
    'context' => [
        'source' => 'arx-core',
    ],
];

$response = Http::post('http://127.0.0.1:8001/ask', $requestData);
return $response->json();
```

**Attention :** cette URL en `127.0.0.1` convient au développement lorsque les deux services sont accessibles sur la même machine. Une configuration adaptée sera nécessaire pour un déploiement distribué. La gestion robuste des erreurs HTTP, délais d'attente et indisponibilités reste à renforcer.

---

## 4. Point d'entrée : `main.py`

`main.py` initialise l'application FastAPI et expose les routes HTTP.

### `GET /` — Vérification simple de disponibilité

```json
{
  "status": "success",
  "module": "arx-ai",
  "message": "ARX AI Node est opérationnel"
}
```

Cette route confirme que le serveur répond ; elle ne prouve pas à elle seule que tous les composants sont disponibles.

### `POST /ask` — Réception d'une demande

Extrait représentatif du code actuel :

```python
from fastapi import FastAPI
from models.ask import AskRequest
from models.ask_response import AskResponse
from services.arx_ai_service import process_prompt

app = FastAPI()

@app.post("/ask", response_model=AskResponse)
def ask(request: AskRequest):
    return process_prompt(
        prompt=request.prompt,
        request_type=request.type,
        source=request.context.source
    )
```

La route reçoit la demande, fait valider le corps JSON par Pydantic, transmet les champs au service puis renvoie une réponse conforme à `AskResponse`.

---

## 5. Contrats de données Pydantic

### Entrée : `models/ask.py`

```python
from pydantic import BaseModel

class RequestContext(BaseModel):
    source: str

class AskRequest(BaseModel):
    type: str
    prompt: str
    context: RequestContext
```

Les champs actuellement requis sont :

| Champ | Type | Rôle |
|---|---|---|
| `type` | `str` | Nature de la demande, par exemple `conversation` |
| `prompt` | `str` | Texte envoyé par l'utilisateur |
| `context` | `RequestContext` | Informations de contexte |
| `context.source` | `str` | Origine déclarée, par exemple `arx-core` |

**À retenir :** `source` est pour l'instant une information fournie par le client, pas une preuve d'identité. Il faudra authentifier les appels interservices avant une exposition réseau.

### Sortie : `models/ask_response.py`

```python
from pydantic import BaseModel

class AskResponse(BaseModel):
    status: str
    module: str
    prompt: str
    response: str
```

`response_model=AskResponse` indique à FastAPI le schéma attendu pour la réponse. Cela documente l'API dans Swagger et permet de valider les champs de sortie.

**Évolution possible :** restreindre les valeurs de `type` et `status` avec `Literal` ou des énumérations, et renforcer les règles de validation du prompt.

---

## 6. Service : `services/arx_ai_service.py`

Le service reçoit les données validées et choisit le traitement en fonction du type de demande.

```python
from handlers.conversation import handle_conversation


def process_prompt(
    prompt: str,
    request_type: str,
    source: str
) -> dict[str, str]:

    if request_type == "conversation":
        status = "success"
        message = handle_conversation(prompt)
    else:
        status = "unsupported"
        message = "Ce type de demande n'est pas encore pris en charge."

    return {
        "status": status,
        "module": "arx-ai",
        "prompt": prompt,
        "response": message,
    }
```

- `conversation` appelle le handler et retourne le statut applicatif `success`.
- Tout autre type retourne le statut applicatif `unsupported`.
- Le paramètre `source` est reçu, mais n'est pas encore exploité dans le traitement.

**Important :** `unsupported` est ici une valeur dans le **JSON applicatif**. Dans les tests actuels, FastAPI renvoie malgré cela un code HTTP `200`. Un code HTTP spécifique pourrait être choisi ultérieurement selon le contrat d'API souhaité.

---

## 7. Handler conversationnel : `handlers/conversation.py`

Le premier handler isole le traitement des demandes de type `conversation`.

```python
# Gestion des demandes conversationnelles d'ARX AI

def handle_conversation(prompt: str) -> str:
    message = f"ARX AI a reçu le message : {prompt}"
    return message
```

Cette fonction **ne comprend pas encore le texte** et **ne génère pas de réponse intelligente** : elle construit une réponse simulée pour vérifier le bon fonctionnement du circuit.

Le fichier `handlers/__init__.py` marque explicitement le dossier comme package Python. Les futurs handlers pourront gérer d'autres types de demandes, comme les audits défensifs, lorsque leurs fonctionnalités seront réellement implémentées.

---

## 8. Pourquoi séparer API, service et handlers ?

```text
main.py
  └── reçoit et valide la requête HTTP
        └── services/arx_ai_service.py
              └── choisit le traitement
                    └── handlers/conversation.py
                          └── construit la réponse
```

Cette séparation offre plusieurs avantages :

1. **Lisibilité :** chaque fichier possède une responsabilité identifiable.
2. **Maintenance :** les modifications du traitement conversationnel n'obligent pas à réécrire les routes HTTP.
3. **Tests :** chaque couche peut être vérifiée séparément.
4. **Évolutivité :** d'autres handlers ou un moteur LLM pourront être ajoutés progressivement.
5. **Sécurité :** il sera plus facile d'encadrer les capacités de chaque type de demande.

---

## 9. Parallèle avec Laravel

| ARX Core / Laravel | ARX AI Node / Python |
|---|---|
| Route web | Endpoint FastAPI |
| `ArxAiController` | Fonction de route dans `main.py` |
| `ArxAiService.php` | `services/arx_ai_service.py` |
| Validation Laravel | Modèles Pydantic |
| Logique métier spécialisée | `handlers/` |
| Vue Blade | Réponse JSON consommée par Laravel |

FastAPI n'impose pas exactement l'organisation de Laravel, mais cette structure conserve une séparation comparable des responsabilités.

---

## 10. Exemple de flux complet

Un utilisateur saisit : `Hello !`

```text
Formulaire Blade ARX AI
    ↓
ArxAiController
    ↓
ArxAiService.php
    ↓
POST /ask
{
  "type": "conversation",
  "prompt": "Hello !",
  "context": {"source": "arx-core"}
}
    ↓
FastAPI / AskRequest
    ↓
process_prompt("Hello !", "conversation", "arx-core")
    ↓
handle_conversation("Hello !")
    ↓
"ARX AI a reçu le message : Hello !"
    ↓
AskResponse → JSON
    ↓
Laravel → Blade → Utilisateur
```

Exemple de réponse :

```json
{
  "status": "success",
  "module": "arx-ai",
  "prompt": "Hello !",
  "response": "ARX AI a reçu le message : Hello !"
}
```

Ce parcours a été validé dans l'interface Laravel le **23 octobre 2026**.

---

## 11. Structure actuelle du microservice

```text
arx-ai-node/
├── .venv/                      # Environnement virtuel Python
├── main.py                     # Application et routes FastAPI
├── models/
│   ├── __init__.py             # Selon structure locale
│   ├── ask.py                  # AskRequest et RequestContext
│   └── ask_response.py         # AskResponse
├── services/
│   └── arx_ai_service.py       # Orientation des demandes
├── handlers/
│   ├── __init__.py             # Package Python
│   └── conversation.py         # Handler conversationnel simulé
└── ARCHITECTURE.md             # Documentation technique
```

L'arborescence montre les principaux fichiers applicatifs ; certains fichiers annexes ou générés ne sont pas représentés. `models/__init__.py` est indiqué à vérifier selon le contenu réel du projet.

---

## 12. Technologies utilisées

| Technologie | Fonction |
|---|---|
| Python | Langage du microservice ARX AI Node |
| FastAPI | Exposition des endpoints HTTP |
| Uvicorn | Serveur ASGI, port local `8001` |
| Pydantic | Validation et schémas d'entrée/sortie |
| Laravel / PHP | ARX Core, orchestration et interface |
| Blade | Affichage de l'interface ARX AI |
| Swagger UI | Tests et documentation interactive via `/docs` |

### Démarrage en développement

Depuis `arx-ai-node`, environnement virtuel activé :

```bash
uvicorn main:app --reload --port 8001
```

Depuis la racine Laravel, dans un autre terminal :

```bash
php artisan serve
```

Swagger UI : `http://127.0.0.1:8001/docs`

---

## 13. Tests et état d'avancement

### Fonctionnel et vérifié

- Démarrage du microservice FastAPI avec Uvicorn.
- Routes `GET /` et `POST /ask`.
- Modèles Pydantic `AskRequest`, `RequestContext` et `AskResponse`.
- JSON entrant avec `type`, `prompt` et `context.source`.
- Routage applicatif selon `request_type`.
- Handler conversationnel `handle_conversation()`.
- Réponse `success` pour `conversation`.
- Réponse `unsupported` pour un type non pris en charge (test avec `audit`).
- Tests Swagger réussis avec HTTP `200`.
- Communication complète Laravel → FastAPI → service → handler → Laravel.
- Affichage de la réponse depuis l'interface ARX AI.

### Non encore implémenté

- Véritable modèle de langage (LLM) et génération de réponses intelligentes.
- Historique et mémoire conversationnelle.
- Base de connaissances et RAG cybersécurité.
- Analyse réelle des événements ARX SOC.
- Traitements d'audit réseau.
- Détection d'anomalies et moteur de règles.
- Actions défensives contrôlées.
- Authentification et autorisation robustes des appels interservices.
- Journalisation, gestion des erreurs et tests automatisés complets.

---

## 14. Sécurité et principes de conception

ARX AI est destiné à devenir un **assistant de cybersécurité défensive**. Son développement devra préserver plusieurs principes :

- **Moindre privilège :** ne donner au microservice que les accès nécessaires.
- **Contrôle humain :** aucune action sensible sur le réseau sans autorisation explicite.
- **Validation des entrées :** encadrer types, longueurs et contenus avant traitement.
- **Séparation des rôles :** distinguer conversation, analyse et éventuelles actions.
- **Authentification des services :** ne pas faire confiance au seul champ `context.source`.
- **Résistance aux instructions non fiables :** traiter les contenus externes comme des données, pas comme des ordres.
- **Traçabilité :** prévoir des journaux et des audits adaptés, sans exposer de secrets.

Ces principes constituent une **feuille de route**, pas des protections déjà toutes mises en place.

---

## 15. Prochaine étape — Véritable moteur conversationnel

**Point de reprise après la session du 23 octobre 2026 :** les communications et le premier handler sont validés. La prochaine phase consiste à remplacer progressivement la réponse simulée par une véritable génération de texte.

### Progression proposée

1. **Préparer l'interface du moteur :** isoler la génération de réponse de `handlers/conversation.py` pour pouvoir changer de fournisseur ou de modèle sans bouleverser l'API.
2. **Choisir un moteur LLM :** comparer les possibilités locales et distantes selon les ressources, la confidentialité, les coûts et les besoins d'ARX.
3. **Intégrer un premier modèle :** obtenir une réponse réellement générée à une question simple.
4. **Ajouter des garde-fous :** contrôler les entrées, les sorties, les permissions et les capacités autorisées.
5. **Construire la mémoire conversationnelle :** introduire des identifiants de conversation et un historique maîtrisé.
6. **Préparer les fonctions cybersécurité :** base de connaissances, données ARX SOC et traitements défensifs, uniquement après sécurisation des accès.

### Architecture cible indicative

```text
ARX Core (Laravel)
    ↓
FastAPI + Pydantic
    ↓
Service d'orientation
    ↓
Handler conversationnel
    ↓
Interface de moteur IA
    ↓
Modèle de langage (à choisir)
    ↓
Réponse contrôlée
    ↓
AskResponse → ARX Core
```

**Objectif :** conserver une architecture claire, modulaire et maîtrisée avant d'étendre les capacités d'ARX AI.
