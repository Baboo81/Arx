# ==========================================
# ARX AI - Perceptron à deux entrées
# ==========================================

# Paramètres initiaux du neurone
weight1 = 0.0
weight2 = 0.0
bias = 0.0

# Vitesse d'apprentissage
learning_rate = 0.01

def predict(x1: float, x2: float) -> int:
    """
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