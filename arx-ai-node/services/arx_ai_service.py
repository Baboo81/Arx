
# arx_ai_service traite le prompt reçu depuis main.py

def process_prompt(
    prompt: str,
    request_type: str,
    source: str
) -> dict[str, str]:

    if request_type == "conversation":
        status = "success"
        message = f"ARX AI a reçu le message : {prompt}"
    else:
        status = "unsupported"
        message = "Ce type de demande n'est pas encore pris en charge."

    return {
        "status": status,
        "module": "arx-ai",
        "prompt": prompt,
        "response": message,
    }
