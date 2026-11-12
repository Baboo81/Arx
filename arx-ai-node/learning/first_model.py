# ==========================================
# ARX AI - Premier modèle d'apprentissage
# ==========================================

# Données d'entraînement :
# (nb d'échecs de connexion, classification)
training_data = [
    (2, 0),
    (5, 0),
    (8, 0),
    (20, 1),
    (35, 1),
    (50, 1),
]

# Neurone artificiel : z = w * x + b (x = nombre d'échecs de connexion, w = poids, b = biais)

# Paramètres initiaux de notre naurone
weight = 0.0
bias = 0.0

# Vitesse d'apprentissage
learning_rate = 0.01

def predict(x: float) -> int:
    """
    Prédit si une activité est normale (0) ou suspecte (1).
    """
    
    # Calcul du neurone artificiel
    result = weight * x + bias
    
    
    # Fonction d'activation
    return 1 if result > 0 else 0

# Entraînement sur 100 passages des données 
for epoch in range(100):
    
    errors = 0
    
    for x, expected in training_data:
        
        # Prédiction actuelle
        prediction = predict(x)
        
        # Calcul de l'erreur
        error = expected - prediction
        
        # Ajustemenbt des paramètres
        weight += learning_rate * error * x
        bias += learning_rate * error
        
        # Comptabilisation des erreurs
        if error != 0:
            errors += 1
            
    # Affichage de la progression
    if epoch % 10 == 0:
        print(f"Époque {epoch}: {errors} erreurs")
        
# Résultat final de l'entraînement
print("\nEntrainement terminé !")
print(f"Poids appris : {weight:.4f}, Biais appris : {bias:.4f}")

# Test sur de nouvelles valeurs
for attempts in [3, 12, 25, 60]:
    result = predict(attempts)
    print(f"{attempts} échecs -> Classe {result}")
    
        