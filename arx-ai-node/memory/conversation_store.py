# Gestion de l'historique

import json
from pathlib import Path
from datetime import datetime, timezone

# Définition du dossier dans lequel se trouve notre fichier Python
MEMORY_DIR = Path(__file__).resolve().parent

# Définition du chemin vers notre fichier JSOB
MEMORY_FILE = MEMORY_DIR / "conversations.json"



def save_message(prompt: str, response: str) -> None:
    """
    
    Enregistre un échange entre l'utilisateur et ARX AI.
    """

    # Création d'un dictionnaire représentant les échanges
    message = {
        "timestamp": datetime.now(timezone.utc).isoformat(),
        "prompt": prompt,
        "response": response
    }
    
    # Sinon, nous créons une liste vide
    conversations = []

    # Si le fichier existe, nous récupèrons son contenu
    if MEMORY_FILE.exists():
        with MEMORY_FILE.open("r", encoding="utf-8") as file:
            conversations = json.load(file)
        
    # Nous ajoutons notre nouvel échange à la liste
    conversations.append(message)

    # Nous enregistrons la liste dans notre fichier JSON
    with MEMORY_FILE.open("w", encoding="utf-8") as file:
        json.dump(conversations, file, indent=4, ensure_ascii=False)