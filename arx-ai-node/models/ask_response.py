#Import de la classe qui permet de definir et de valider nos modèles
from pydantic import BaseModel

#Création de notre propre modèle en héritant de BaseModel
class AskResponse(BaseModel):
    status: str #Le statut doit être une chaîne de caractères
    module: str #Le nom du module doit être une chaîne de caractères
    prompt: str #La question reçue doit e^tre du texte
    response: str #La réponse générée doit être du texte