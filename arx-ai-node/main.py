#Importation de la classe FastAPI de la bibliothèquue
from fastapi import FastAPI
# Importation des modèles de données de la route /ask
from models.ask import AskRequest ,AskResponse
#Importation du service : ARX AI
from services.arx_ai_service import process_prompt


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

#Route permettant d'envoyer une demande à ARX AI, les données sortant de cette route doivent respecter : AskResponse
@app.post("/ask", response_model=AskResponse)
def ask(request: AskRequest):
    return process_prompt(request.prompt)