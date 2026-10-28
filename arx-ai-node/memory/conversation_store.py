# Gestion de l'historique

import json
from pathlib import Path
from datetime import datetime, timezone

# Définition du dossier dans lequel se trouve notre fichier Python
MEMORY_DIR = Path(__file__).resolve().parent

# Définition du chemin vers notre fichier JSON
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
    conversations: list[dict[str, str]] = []

    # Si le fichier existe, nous récupèrons son contenu
    if MEMORY_FILE.exists():
        with MEMORY_FILE.open("r", encoding="utf-8") as file:
            conversations = json.load(file)
        
    # Nous ajoutons notre nouvel échange à la liste
    conversations.append(message)

    # Nous enregistrons la liste dans notre fichier JSON
    with MEMORY_FILE.open("w", encoding="utf-8") as file:
        json.dump(conversations, file, indent=4, ensure_ascii=False)
        
def load_conversations(limit: int = 5) -> list[dict[str, str]]:
    """
    Cette fonction permettra la lecture du fichier JSON et retournera son contenu sous forme de liste Python
     - list = liste Python
     - dict = dico contenant des associations : clé-valeur
    Si le fichier n'existe pas encore, retourne une liste vide
    """
    
    # Vérification de l'existence du fichier JSON
    if not MEMORY_FILE.exists():
        return []
    
    # Ouverture du fichier en mode lecture 
    with MEMORY_FILE.open("r", encoding="utf-8") as file:
        
        # Conversion du contenu JSON en objet Python 
        conversations = json.load(file)
        
    # Retour de la liste des conversations
    return conversations