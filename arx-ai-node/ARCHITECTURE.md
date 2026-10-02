# ARX AI Node — Architecture

## 1. Rôle d'ARX AI Node

**ARX AI Node** est le microservice Python chargé de la partie intelligence artificielle du projet ARX.

Il est séparé de **ARX Core**, qui est développé avec Laravel.

ARX Core joue le rôle d'orchestrateur principal de la plateforme, tandis qu'ARX AI Node sera progressivement chargé des traitements liés à l'intelligence artificielle.

À ce stade du développement, aucune véritable intelligence artificielle n'est encore connectée. L'objectif actuel est de construire et de valider la communication entre **Laravel et Python** avant d'ajouter un moteur IA.

---

## 2. Architecture actuelle

```text
ARX Core (Laravel)
        │
        │ HTTP POST
        ▼
      /ask
        │
        ▼
main.py (FastAPI)
        │
        ▼
AskRequest (Pydantic)
        │
        │ request.prompt
        ▼
process_prompt()
        │
        ▼
services/arx_ai_service.py
        │
        │ dictionnaire Python
        ▼
FastAPI
        │
        │ JSON
        ▼
ARX Core (Laravel)
        │
        ▼
Interface ARX AI
```

---

## 3. Communication entre Laravel et FastAPI

ARX Core communique avec ARX AI Node au moyen de requêtes HTTP.

Actuellement, Laravel envoie une requête :

```http
POST /ask
```

vers le serveur FastAPI :

```text
http://127.0.0.1:8001/ask
```

Le corps de la requête contient un objet JSON :

```json
{
    "prompt": "Analyse mon réseau"
}
```

Laravel joue donc ici le rôle de **client HTTP**, tandis que FastAPI expose une **API** capable de recevoir la demande.

---

## 4. `main.py`

Le fichier :

```text
main.py
```

est actuellement le **point d'entrée du microservice FastAPI**.

Il crée l'application :

```python
app = FastAPI()
```

Il expose actuellement deux routes.

### `GET /`

Cette route permet de vérifier qu'ARX AI Node fonctionne.

Elle retourne par exemple :

```json
{
    "status": "success",
    "module": "arx-ai",
    "message": "ARX AI Node est opérationnel"
}
```

Cette route peut être considérée pour le moment comme un test simple de disponibilité du service.

### `POST /ask`

Cette route reçoit les demandes envoyées par ARX Core.

```python
@app.post("/ask")
def ask(request: AskRequest):
    return process_prompt(request.prompt)
```

Son rôle est volontairement limité :

1. recevoir la requête ;
2. laisser Pydantic valider les données ;
3. récupérer le `prompt` ;
4. transmettre le prompt au service ;
5. retourner le résultat.

La logique de traitement n'est donc plus directement placée dans la route.

---

## 5. Validation avec Pydantic

Le modèle :

```python
class AskRequest(BaseModel):
    prompt: str
```

définit la structure attendue pour une requête envoyée à `/ask`.

Il indique qu'une demande doit contenir :

```text
prompt
```

et que sa valeur doit être une chaîne de caractères (`str`).

Exemple valide :

```json
{
    "prompt": "Analyse mon réseau"
}
```

Pydantic permet ainsi de contrôler les données avant qu'elles ne soient transmises au reste de l'application.

### Pourquoi utiliser un modèle ?

Cela permet progressivement de définir un véritable **contrat d'API** entre ARX Core et ARX AI Node.

ARX Core sait quelles informations envoyer et ARX AI Node sait quelle structure il doit accepter.

---

## 6. Le service `arx_ai_service.py`

La logique de traitement commence à être séparée de `main.py`.

Le fichier se trouve dans :

```text
services/arx_ai_service.py
```

Il contient actuellement :

```python
def process_prompt(prompt: str):
    return {
        "status": "success",
        "module": "arx-ai",
        "prompt": prompt,
        "response": f"ARX AI Node a reçu la demande : {prompt}"
    }
```

La fonction :

```python
process_prompt()
```

reçoit le prompt transmis par `main.py`.

Elle construit ensuite la réponse qui sera retournée à ARX Core.

À ce stade, cette réponse est encore simulée : aucun LLM ou autre moteur d'intelligence artificielle n'analyse réellement le prompt.

---

## 7. Pourquoi séparer `main.py` et le service ?

Au départ, la route `/ask` construisait directement la réponse.

L'organisation était donc :

```text
main.py
├── réception HTTP
├── validation
└── traitement
```

La nouvelle organisation sépare les responsabilités :

```text
main.py
│
├── API FastAPI
├── routes HTTP
├── validation des entrées
│
└── appelle
       │
       ▼
services/arx_ai_service.py
       │
       └── logique de traitement ARX AI
```

Cette séparation permettra de faire évoluer le moteur ARX AI sans transformer `main.py` en un fichier contenant toute la logique du projet.

---

## 8. Parallèle avec Laravel

L'organisation peut être rapprochée de l'architecture déjà utilisée dans ARX Core :

```text
ARX Core / Laravel              ARX AI Node / Python

Route                           Endpoint FastAPI
  ↓                                  ↓
Controller                     main.py
  ↓                                  ↓
Service                        Service
  ↓                                  ↓
Traitement                     Traitement IA
```

FastAPI n'impose pas exactement la même architecture que Laravel, mais cette séparation permet de conserver des responsabilités claires.

---

## 9. Flux complet d'une demande

Lorsqu'un utilisateur saisit par exemple :

```text
Analyse mon réseau
```

le parcours actuel est :

```text
Utilisateur
    ↓
Interface ARX AI
    ↓
ArxAiController
    ↓
ArxAiService (Laravel)
    ↓
POST http://127.0.0.1:8001/ask
    ↓
FastAPI
    ↓
AskRequest
    ↓
request.prompt
    ↓
process_prompt("Analyse mon réseau")
    ↓
arx_ai_service.py
    ↓
réponse
    ↓
FastAPI transforme la réponse en JSON
    ↓
ArxAiService Laravel récupère le JSON
    ↓
ArxAiController
    ↓
Blade
    ↓
Utilisateur
```

Nous avons donc maintenant une communication fonctionnelle **de bout en bout entre Laravel et Python**.

---

## 10. Structure actuelle

```text
arx-ai-node/
│
├── .venv/
│
├── main.py
│
├── services/
│   └── arx_ai_service.py
│
└── ARCHITECTURE.md
```

### `.venv/`

Environnement virtuel Python du projet.

Il permet d'isoler les dépendances Python utilisées par ARX AI Node de celles installées globalement sur la machine.

### `main.py`

Point d'entrée actuel de l'application FastAPI.

### `services/`

Contient progressivement la logique métier d'ARX AI.

### `ARCHITECTURE.md`

Documentation technique et pédagogique de l'architecture du microservice.

---

## 11. Technologies actuellement utilisées

### Python

Langage utilisé pour développer ARX AI Node.

### FastAPI

Framework Python utilisé pour créer l'API HTTP permettant à ARX Core de communiquer avec ARX AI Node.

### Uvicorn

Serveur ASGI utilisé pour exécuter l'application FastAPI.

ARX AI Node est actuellement lancé sur le port :

```text
8001
```

### Pydantic

Utilisé pour définir et valider les structures de données échangées avec l'API.

### Laravel

Utilisé par ARX Core, qui joue le rôle d'orchestrateur principal et de client HTTP d'ARX AI Node.

---

## 12. État actuel

### Fonctionnel

- lancement d'ARX AI Node avec Uvicorn ;
- serveur FastAPI sur le port `8001` ;
- route `GET /` ;
- route `POST /ask` ;
- validation du `prompt` avec Pydantic ;
- communication Laravel → FastAPI ;
- communication FastAPI → Laravel ;
- séparation de la logique dans `services/arx_ai_service.py` ;
- affichage de la réponse dans l'interface ARX AI.

### Pas encore implémenté

ARX AI Node ne possède pas encore :

- de véritable moteur LLM ;
- de RAG cybersécurité ;
- de mémoire conversationnelle ;
- d'analyse des événements ARX SOC ;
- de moteur de règles ;
- de détection d'anomalies ;
- de connexion à une base de connaissances ;
- de système d'actions défensives.

Ces éléments seront ajoutés progressivement.

---

## 13. Prochaine étape

La prochaine étape consiste à définir également la **structure de sortie** d'ARX AI Node avec Pydantic.

Nous avons actuellement :

```text
AskRequest
    ↓
ENTRÉE
```

Nous allons ajouter :

```text
AskResponse
    ↑
SORTIE
```

L'objectif est que les données entrantes **et sortantes** respectent un contrat clairement défini.

À terme :

```text
ARX Core
    ↓
AskRequest
    ↓
ARX AI Node
    ↓
Moteur ARX AI
    ↓
AskResponse
    ↓
ARX Core
```

Cette base préparera ensuite l'intégration progressive d'un véritable moteur d'intelligence artificielle.