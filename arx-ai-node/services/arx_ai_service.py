
# arx_ai_service traite le prompt reçu depuis main.py

from handlers.conversation import handle_conversation

# Service de traitement des demandes ARX AI
def process_prompt(
    prompt: str,
    request_type: str,
    source: str
) -> dict[str, str]:

    if request_type == "conversation":
        status = "success"
        
        # Appel du handler conversationnel
        message = handle_conversation(prompt)
    else:
        status = "unsupported"
        message = "Ce type de demande n'est pas encore pris en charge."

    return {
        "status": status,
        "module": "arx-ai",
        "prompt": prompt,
        "response": message,
    }
