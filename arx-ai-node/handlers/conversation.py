# Gestion des demandes conversationnelles d'ARX AI

# Importation de notre fonction de sauvegarde
from memory.conversation_store import save_message


def handle_conversation(prompt: str) -> str:
    """
    Traite un message utilisateur et conserve l'échange
    dans la mémoire d'ARX AI.
    """

    # 1. Génération de la réponse
    response = f"ARX AI a reçu le message : {prompt}"

    # 2. Enregistrement de la conversation
    save_message(prompt, response)

    # 3. Retour de la réponse à notre service
    return response