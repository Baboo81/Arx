# ==========================================
# ARX AI - Perceptron à deux entrées
# ==========================================

# Paramètres initiaux du neurone
weight1 = 0.0
weight2 = 0.0
bias = 0.0

# Vitesse d'apprentissage
learning_rate = 0.01


def normalize(attempts: float, duration: float) -> tuple[float, float]:
    """
    Normalise les caractéristiques pour notre exercice.

    attempts : nombre d'échecs
    duration : durée en secondes
    """

    # Échelles de référence fictives
    normalized_attempts = attempts / 50
    normalized_duration = duration / 3600

    return normalized_attempts, normalized_duration

def predict_activity(attempts: float, duration: float) -> int:
    """
    Prédit une activité à partir de données brutes.
    Prépare les observations brutes, notamment en les normalisant.

    1. Normalise les deux caractéristiques.
    2. Transmet les valeurs au perceptron.
    3. Retourne la classe prédite.
    """

    x1, x2 = normalize(attempts, duration)

    return predict(x1, x2)

def predict(x1: float, x2: float) -> int:
    """
    Effectue uniquement le calcul mathématique du perceptron.
    
    x1 : nombre d'échecs de connexion
    x2 : durée des échecs en secondes
    
    Retourne :
    0 = activité normale
    1 = activité suspecte
    """
    
    # Somme pndérée des deux entrées
    z = weight1 * x1 + weight2 * x2 + bias
    
    # Fonction d'activation binaire
    return 1 if z > 0 else 0

def train(x1: float, x2: float, expected: int) -> int:
    """
    Entraîne le perceptron sur une observation.
    
    Retourne l'erreur de prédiction.
    """
    
    # Nous allons modifier les paramètres globaux
    global weight1, weight2, bias
    
    # 1. Prédiction actuelle
    prediction = predict(x1, x2)
    
    # 2. Calcul de l'erreur
    error = expected - prediction
    
    # 3. Ajustement des 2 poids
    weight1 += learning_rate * error * x1
    weight2 += learning_rate * error * x2
    
    # 4. Ajustement du biais
    bias += learning_rate * error
    
    return error

# ==========================================
# PREMIER TEST D'APPRENTISSAGE
# ==========================================

# Observation fictives :
# 20 échecs en 5 secondes, classe suspecte (1)
x1 = 20
x2 = 5
expected = 1

# Prédiction avant aprrentissage
print("Avant entraînement :", predict(x1, x2))

# Entraînement sur une eobservation
error = train(x1, x2, expected)

# Affichage des paramètres appris
print("Erreur :", error)
print(f"Poids 1 : {weight1:.4f}")
print(f"Poids 2 : {weight2:.4f}")
print(f"Biais : {bias:.4f}")

# Nouvelle prédiction
print("Après entraînement :", predict(x1, x2))
print("Données normalisées :", normalize(20, 1800))