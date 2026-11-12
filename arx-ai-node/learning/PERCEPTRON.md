ARX AI --- Le perceptron
Fiche de cours --- 12 novembre 2026
Projet : arx-ai-node/learning/first_model.py
1. Qu'est-ce qu'un perceptron ?
Un perceptron est un neurone artificiel simple utilisé pour classer
des données en deux catégories. Dans notre exercice de cybersécurité
fictif, 0 signifie « normale » et 1 « suspecte ».

Notre entrée x est le nombre de tentatives de connexion échouées par
minute. Ce seul indicateur ne permet pas de diagnostiquer une
véritable attaque.
2. Les exemples d'entraînement
Tentatives (`x`)   Classe attendue

               2                 0
               5                 0
               8                 0
              20                 1
              35                 1
              50                 1
C'est de l'apprentissage supervisé : nous fournissons à l'algorithme
des exemples et leurs bonnes réponses.
3. Le calcul du neurone
Formule : z = weight × x + bias
- x : donnée d'entrée.
- weight (poids) : influence de l'entrée.
- bias (biais) : décalage de la frontière de décision.
- z : score obtenu, pas une probabilité.
Règle de classification : 1 si z > 0, sinon 0.
4. Comment apprend-il ?
Nous commençons avec weight = 0, bias = 0 et learning_rate = 0.01.

Pour chaque exemple : 1. Le neurone fait une prédiction. 2. Il calcule
error = expected - prediction. 3. Il corrige ses paramètres :
weight += learning_rate * error * x
bias += learning_rate * error
Si la réponse attendue est 1 et la prédiction 0, l'erreur vaut
+1. Si l'inverse se produit, elle vaut −1. Une bonne prédiction
produit une erreur nulle.

Une époque (epoch) correspond à un passage sur tous les exemples.
Le nombre d'erreurs affiché pendant une époque compte les erreurs
rencontrées au fil des corrections.
5. Notre programme Python commenté
Fichier : learning/first_model.py
# ARX AI - Premier perceptron

# (nombre de tentatives échouées, classe attendue)
training_data = [
    (2, 0), (5, 0), (8, 0),
    (20, 1), (35, 1), (50, 1),
]

# Paramètres à apprendre
weight = 0.0
bias = 0.0
learning_rate = 0.01


def predict(x: float) -> int:
    # Somme pondérée
    result = weight * x + bias

    # Fonction d'activation à seuil
    return 1 if result > 0 else 0


# L'entraînement répète les exemples 100 fois
for epoch in range(100):
    errors = 0

    for x, expected in training_data:
        prediction = predict(x)
        error = expected - prediction

        # Le neurone ajuste ses paramètres
        weight += learning_rate * error * x
        bias += learning_rate * error

        if error != 0:
            errors += 1

    if epoch % 10 == 0:
        print(f"Époque {epoch} : {errors} erreurs")


print("\\nEntraînement terminé !")
print(f"Poids appris : {weight:.4f}")
print(f"Biais appris : {bias:.4f}")

# Prédictions après apprentissage
for attempts in [3, 12, 25, 60]:
    result = predict(attempts)
    print(f"{attempts} échecs -> Classe {result}")
Depuis le dossier arx-ai-node, avec .venv activé :
python -m py_compile learning/first_model.py
python learning/first_model.py
6. Résultats de notre séance
               Époque   Erreurs affichées

                    0                   1
                   10                   3
                   20                   0
30 à 90 (tous les 10)                   0
Paramètres finaux observés : - Poids : 0.0200 - Biais :
-0.2900

Prédictions obtenues : 3 → 0, 12 → 0, 25 → 1, 60 → 1.

Le nombre d'erreurs peut augmenter temporairement pendant
l'apprentissage : les corrections successives ne garantissent pas une
amélioration à chaque époque.
7. La frontière de décision
Notre modèle entraîné calcule :

z = 0,02 × x − 0,29

La classe 1 est prédite si :

0,02 × x − 0,29 > 0, donc x > 14,5.
Tentatives   Score   Classe

        14   −0,01        0
      14,5       0        0
        15   +0,01        1
        25   +0,21        1
Exercice résolu : pour x = 14, z = 0,02 × 14 − 0,29 = −0,01.
Comme le score n'est pas strictement positif, le neurone répond classe
0.
8. Les limites à retenir
Le perceptron a appris une séparation qui fonctionne sur nos six
exemples fictifs, pas une règle universelle de cybersécurité. Il ne
considère ni l'adresse IP, ni l'heure, ni le service ciblé, ni les
habitudes du réseau.

Un modèle peut réussir sur ses exemples d'entraînement et se tromper sur
des données inédites. Il ne faut donc pas lui confier des blocages
automatiques de réseau à ce stade.
9. Prochaine leçon
Nous allons : 1. Séparer les données d'entraînement des données de
test. 2. Mesurer les bonnes et mauvaises prédictions. 3. Comprendre
les faux positifs, les faux négatifs et le surapprentissage.
4. Puis entraîner un modèle qui analyse plusieurs caractéristiques.

À retenir : le perceptron apprend en modifiant son poids et son
biais à partir des erreurs. Il applique ensuite une frontière
mathématique aux nouvelles données.

ARX AI --- Carnet d'apprentissage, leçon 1.