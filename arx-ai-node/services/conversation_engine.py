# ==========================================
# Production des réponses d'ARX AI
# ==========================================

def generate_response(prompt: str, context: list[dict[str, str]]) -> str:
    """

    Génère une réponse à partir  :
        - du message de l'utilisateur,
        - du contexte des conversations précédentes.
        
    Pour le moment, la réponse reste simulée.
    """
     
    # Génération temporaire de la réponse
    response = f"ARX AI a reçu le message : {prompt}"
    
    # Retour de la réponse au handler
    return response