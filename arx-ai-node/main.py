#Importation de la classe FastAPI de la bibliothèquue
from fastapi import FastAPI
from pydantic import BaseModel

#Une requête : AksRequest doit contenir un champ prompt et ce champ doit être une string
class AskRequest(BaseModel):
    prompt: str

#Création de l'app FastAPI 
app = FastAPI()

#Lorsque la requête HTTP GET arrive sur /, exècute la fonction située juste en dessous
@app.get("/")

#Synthaxe en Python d'une fonction : cette fonction permet de retourner un dictionnaire Python, FastAPI va transformer cela en JSON pour la réponse HTTP
def root():
    return {
        "status": "success",
        "module": "arx-ai",
        "message": "ARX AI Node est opérationnel"
    }

#Route permettant d'envoyer une demande à ARX AI
@app.post("/ask")
def ask(request: AskRequest):
    return {
        "status": "success",
        "module": "arx-ai",
        "prompt": request.prompt,
        "response": f"ARX AI Node a reçu la demande : {request.prompt}"
    }