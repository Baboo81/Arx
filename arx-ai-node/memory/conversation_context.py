# ==================================================================
# Préparer les données pour le moteur d'intelligence artificielle
# ==================================================================

def build_conversation_context(history: list[dict[str, str]]) -> list[dict[str, str]]:
    """
    Transforme l'historique des conversations en messages structurés pour un futur moteur d'intelligence artificielle.
    Chaque échange devient 2 messages : 
     - Un message de l'utilisateur (role: user)
     - Une réponse de l'assistant.
    """
    # Initialisation de la liste des messages
    context: list[dict[str, str]] = []
    
    # Parcours de chaque conversation enregistrée
    for conversation in history:
        
        #Ajout du message de l'utilisateur
        context.append({
            "role": "user",
            "content": conversation["prompt"]
        })
        
        # Ajout de la réponse d'ARX AI
        context.append({
            "role": "assistant",
            "content": conversation["response"]
        })
        
    # Retour du contexte conversationnel
    return context