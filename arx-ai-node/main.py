#Importation de la classe FastAPI de la bibliothèquue
from fastapi import FastAPI

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