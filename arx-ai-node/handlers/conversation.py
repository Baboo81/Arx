# ==========================================
# ARX AI - Gestion des conversations
# ==========================================

# Importation de notre fonction de sauvegarde
from memory.conversation_store import save_message, load_conversations
from memory.conversation_context import build_conversation_context


def handle_conversation(prompt: str) -> str:
    """
    Traite un message envoyé à ARX AI.
    
    - Reçoit le message de l'utilisateur,
    - Génère une réponse,
    - Enregistre l'échange dans la mémoire,
    - Retourne la réponse
    """
    # Récupération des 5 dernières conversations
    history = load_conversations(limit=5)
    
    # Vérification temporaire de notre mémoire
    print(f"ARX AI : {len(history)} conversations récupérées.")
    
    # Transformation de l'historique en contexte conversationnel
    context = build_conversation_context(history)
    
    # Vérification temporaire du contexte
    print(f"ARX AI : {len(context)} messages dans le contexte.")

    #  Génération de la réponse
    response = f"ARX AI a reçu le message : {prompt}"

    #  Enregistrement de la conversation
    save_message(prompt, response)

    #  Retour de la réponse à notre service
    return response