#models = définition des données circulant

from pydantic import BaseModel


class RequestContext(BaseModel):
    source: str

#Une requête : AksRequest doit contenir un champ prompt et ce champ doit être une string
class AskRequest(BaseModel):
    type: str
    prompt: str
    context: RequestContext
    

#Caracteristiques de la réponse ARX AI 
class AskResponse(BaseModel):
    status: str
    module: str
    prompt: str
    response: str