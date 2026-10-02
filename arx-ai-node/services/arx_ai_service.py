#arx_ai_service va traîter le prompt qu'il reçoit du main.py
def process_prompt(prompt: str):
    return {
        "status": "success",
        "module": "arx-ai",
        "prompt": prompt,
        "response": f"ARX AI Node a reçu la demande : {prompt}"
        }