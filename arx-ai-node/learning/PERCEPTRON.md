ARX AI — Le perceptron
Carnet d'apprentissage — leçons 1 à 3
Séances : 12 et 13 novembre 2026
Projet : arx-ai-node/learning/
Fichiers : first_model.py, multi_input_model.py
Objectif pédagogique : comprendre et programmer nous-mêmes un classificateur binaire supervisé, puis le faire évoluer vers plusieurs caractéristiques. Toutes les observations et étiquettes de sécurité de cette fiche sont fictives.

1. Qu'est-ce qu'un perceptron ?
Un perceptron est un neurone artificiel simple capable d'apprendre une frontière de décision linéaire. Dans nos exercices, il attribue une des deux classes : 0 (activité considérée comme normale) ou 1 (activité considérée comme suspecte). Ces classes sont des étiquettes pédagogiques, pas un diagnostic de cyberattaque.

Notre premier modèle ne reçoit qu'une seule caractéristique x : le nombre de tentatives de connexion échouées sur une fenêtre d'observation. La durée exacte de cette fenêtre doit être définie pour un usage réel.
Apprentissage supervisé
Nous donnons au modèle des observations et leurs réponses attendues. Il ajuste ses paramètres lorsqu'il se trompe.
2. Leçon 1 — Perceptron à une entrée (12 novembre)
2.1 Jeu d'entraînement initial
Échecs (x)	Classe attendue
2	0
5	0
8	0
20	1
35	1
50	1


Note historique : ce tableau correspond à notre première séance. Le 13 novembre, le fichier first_model.py présenté utilisait un autre jeu d'entraînement : (4,0), (6,0), (10,0), (20,1), (35,1), (50,1). Les résultats des deux séances ne doivent pas être confondus.
2.2 Calcul et fonction d'activation
z = weight × x + bias
- x : valeur d'entrée ;
- weight : poids appris ;
- bias : biais appris ;
- z : score mathématique, pas une probabilité.
La fonction d'activation renvoie 1 si z > 0, sinon 0.
2.3 Règle d'apprentissage
Initialisation : weight = 0.0, bias = 0.0, learning_rate = 0.01.

Pour chaque observation :
1. Calculer prediction ;
2. Calculer error = expected - prediction ;
3. Mettre à jour les paramètres :
weight += learning_rate * error * x
bias += learning_rate * error
Une erreur de +1 signifie que le modèle a prédit 0 au lieu de 1 ; une erreur de -1 signifie l'inverse ; une erreur nulle ne provoque aucune correction.

Une époque (epoch) est un passage complet sur les exemples. Les erreurs comptées pendant une époque sont celles rencontrées au fil des corrections successives ; leur nombre peut temporairement augmenter.
2.4 Code de référence de la première séance
# ARX AI — Premier perceptron (version pédagogique du 12 novembre)
training_data = [
    (2, 0), (5, 0), (8, 0),
    (20, 1), (35, 1), (50, 1),
]

weight = 0.0
bias = 0.0
learning_rate = 0.01


def predict(x: float) -> int:
    result = weight * x + bias
    return 1 if result > 0 else 0


for epoch in range(100):
    errors = 0
    for x, expected in training_data:
        prediction = predict(x)
        error = expected - prediction
        weight += learning_rate * error * x
        bias += learning_rate * error
        if error != 0:
            errors += 1

    if epoch % 10 == 0:
        print(f"Époque {epoch} : {errors} erreurs")

print("\nEntraînement terminé !")
print(f"Poids appris : {weight:.4f}")
print(f"Biais appris : {bias:.4f}")

for attempts in [3, 12, 25, 60]:
    print(f"{attempts} échecs -> Classe {predict(attempts)}")
Exécution depuis arx-ai-node, environnement .venv activé :
python -m py_compile learning/first_model.py
python learning/first_model.py
2.5 Résultats observés le 12 novembre
Époque	Erreurs affichées
0	1
10	3
20	0
30 à 90 (tous les 10)	0


Poids final observé : 0.0200 ; biais final : -0.2900. Prédictions : 3 → 0, 12 → 0, 25 → 1, 60 → 1.

Frontière de décision :
z = 0.02 × x − 0.29
z > 0 ⇔ x > 14.5
Tentatives	Score	Classe
14	−0.01	0
14.5	0	0
15	+0.01	1
25	+0.21	1


Pour x = 14, le score vaut 0.02 × 14 − 0.29 = −0.01 : la classe est 0.
3. Leçon 2 — Séparer entraînement et test (13 novembre)
Un modèle peut réussir sur les données qu'il a vues pendant son entraînement et se tromper sur des observations inédites. C'est pourquoi nous séparons :
- training_data : exemples utilisés pour ajuster poids et biais ;
- test_data : exemples jamais utilisés pour les ajustements, servant à évaluer le modèle après l'entraînement.
Dans le fichier first_model.py partagé le 13 novembre, les observations étaient :
training_data = [
    (4, 0), (6, 0), (10, 0),
    (20, 1), (35, 1), (50, 1),
]

test_data = [
    (12, 0),
    (30, 1),
]
3.1 Évaluer sans réentraîner
correct_predictions = 0

for attempts, expected in test_data:
    prediction = predict(attempts)
    if prediction == expected:
        correct_predictions += 1
    print(f"{attempts} échecs -> Prédiction : {prediction}, Attendu : {expected}")

accuracy = correct_predictions / len(test_data) * 100
print(f"\nAccuracy du modèle : {accuracy:.2f}%")
L'accuracy est la proportion de classifications correctes :
accuracy = (nombre de prédictions correctes / nombre de tests) × 100
Ce n'est pas la même chose que la métrique precision (précision des alertes positives), que nous étudierons plus tard.
3.2 Résultats réellement observés le 13 novembre
Époque	Erreurs affichées
0	1
10	4
20	4
30	4
40	4
50	2
60, 70, 80, 90	0


Paramètres finaux : weight = 0.0800, bias = -0.9800.
Échecs	Prédiction	Attendu
12	0	0
30	1	1


Accuracy observée : 100 % (2/2). C'est encourageant pour le fonctionnement du code, mais deux exemples ne permettent pas d'estimer la fiabilité réelle. La nouvelle frontière est x > 12.25 : elle est différente de celle du 12 novembre, car les données d'entraînement ont changé.
3.3 Notions à approfondir
- Généralisation : performance sur de nouvelles observations ;
- Surapprentissage (overfitting) : un modèle s'adapte trop aux données d'entraînement et généralise mal ;
- Faux positif : une activité normale classée suspecte ;
- Faux négatif : une activité suspecte classée normale.
Ces notions sont introduites, mais nous n'avons pas encore calculé de matrice de confusion ni de taux de faux positifs/faux négatifs sur un jeu de test représentatif.
4. Leçon 3 — Perceptron à deux entrées (13 novembre)
Fichier : learning/multi_input_model.py

Nous souhaitons distinguer deux situations : de nombreux échecs répartis sur une longue durée et des échecs très rapprochés dans le temps.
- x1 : nombre d'échecs de connexion ;
- x2 : durée d'observation en secondes.
Notre nouveau neurone utilise deux poids, mais reste un seul neurone :
z = weight1 × x1 + weight2 × x2 + bias
Chaque poids est corrigé à partir de la même erreur, mais de sa propre entrée :
weight1 += learning_rate * error * x1
weight2 += learning_rate * error * x2
bias += learning_rate * error
4.1 Fonctions développées
Le code suivant représente l'état de travail communiqué le 13 novembre, avec indentation et commentaires clarifiés :
# ARX AI — Perceptron à deux entrées
weight1 = 0.0
weight2 = 0.0
bias = 0.0
learning_rate = 0.01


def normalize(attempts: float, duration: float) -> tuple[float, float]:
    """Prépare les caractéristiques selon nos échelles fictives."""
    normalized_attempts = attempts / 50
    normalized_duration = duration / 3600
    return normalized_attempts, normalized_duration


def predict_activity(attempts: float, duration: float) -> int:
    """Normalise des observations brutes avant la prédiction."""
    x1, x2 = normalize(attempts, duration)
    return predict(x1, x2)


def predict(x1: float, x2: float) -> int:
    """Applique le calcul mathématique du perceptron."""
    z = weight1 * x1 + weight2 * x2 + bias
    return 1 if z > 0 else 0


def train(x1: float, x2: float, expected: int) -> int:
    """Apprend à partir d'une observation et renvoie l'erreur."""
    global weight1, weight2, bias
    prediction = predict(x1, x2)
    error = expected - prediction
    weight1 += learning_rate * error * x1
    weight2 += learning_rate * error * x2
    bias += learning_rate * error
    return error


# Premier test, encore effectué sur des valeurs BRUTES :
x1 = 20
x2 = 5
expected = 1
print("Avant entraînement :", predict(x1, x2))
error = train(x1, x2, expected)
print("Erreur :", error)
print(f"Poids 1 : {weight1:.4f}")
print(f"Poids 2 : {weight2:.4f}")
print(f"Biais : {bias:.4f}")
print("Après entraînement :", predict(x1, x2))
print("Données normalisées :", normalize(20, 1800))
4.2 Premier test exécuté
Avant entraînement : 0
Erreur : 1
Poids 1 : 0.2000
Poids 2 : 0.0500
Biais : 0.0100
Après entraînement : 1
Données normalisées : (0.4, 0.5)
Le modèle a corrigé une seule observation (20, 5, 1) : ce succès ne prouve aucune généralisation. Dans ce test, les valeurs ont été données sans normalisation à train() ; il ne faut donc pas réutiliser ces poids pour évaluer directement des données normalisées.
4.3 Pourquoi normaliser ?
Les deux entrées n'ont pas la même unité ni la même échelle. Pour notre exercice :
normalized_attempts = attempts / 50
normalized_duration = duration / 3600
Par exemple : normalize(20, 1800) → (0.4, 0.5).

Ces diviseurs sont des références pédagogiques choisies manuellement. Ils ne garantissent pas des valeurs comprises entre 0 et 1 et devront être justifiés ou remplacés pour de vraies données. Les transformations appliquées pendant l'entraînement doivent être identiques à celles utilisées lors de la prédiction.
4.4 Séparation des responsabilités
Observation brute
    ↓
Validation (à développer)
    ↓
Normalisation : normalize()
    ↓
Calcul : predict()
    ↓
Classe 0 ou 1
predict_activity() sert de point d'entrée pour les données brutes. predict() ne fait que le calcul du neurone. Il faudra également valider les observations (par exemple refuser une durée négative).
4.5 Limite importante de notre exemple
Avec un seul exemple positif, les deux poids sont devenus positifs. Augmenter la durée augmente donc aussi le score z, ce qui n'exprime pas forcément la relation recherchée entre rapidité des échecs et suspicion. Il faut plusieurs exemples variés et un protocole de test pour apprendre une règle utile. Un perceptron simple ne peut représenter que des séparations linéaires.
5. Architecture future — ARX AI Lab
Vision validée le 13 novembre 2026, non implémentée : ARX AI pourra à terme proposer de nouveaux algorithmes, les entraîner et les comparer dans un laboratoire isolé. Aucun nouveau code généré ne sera déployé sur le réseau réel sans validation humaine explicite.

Chaîne envisagée :
ARX SOC / ARX Server : événements et journaux
    ↓
Validation, extraction de caractéristiques
    ↓
Détecteurs et modèles ARX AI
    ↓
Analyse des limites / proposition de nouveau modèle
    ↓
ARX AI Lab : expérimentation isolée et tests
    ↓
Rapport de performances, risques et traçabilité
    ↓
Autorisation humaine avant déploiement
Principes : moindre privilège, isolation du code expérimental, comparaison aux modèles existants, mesures des faux positifs/faux négatifs, historique des versions et possibilité de retour arrière.
6. Point de reprise — Prochaine séance
État confirmé : le fichier multi_input_model.py contient les fonctions normalize(), predict_activity(), predict() et train(), ainsi que le test historique sur une seule observation brute. Le jeu d'entraînement à plusieurs observations n'a pas encore été ajouté.

Étapes suivantes, dans cet ordre :
1. Ajouter un petit training_data fictif avec trois valeurs par ligne : (échecs, durée, classe) ;
2. Isoler ou retirer le test historique sur les valeurs brutes ;
3. Réinitialiser weight1, weight2 et bias avant l'entraînement ;
4. Normaliser avant chaque appel à train() ;
5. Effectuer plusieurs époques et observer l'évolution des erreurs ;
6. Préparer un test_data distinct, normalisé avec la même fonction ;
7. Évaluer l'accuracy et étudier les faux positifs/faux négatifs ;
8. Discuter des limites : données trop peu nombreuses, déséquilibre des classes, choix des caractéristiques et généralisation.
Jeu d'entraînement proposé, mais pas encore implémenté :
training_data = [
    (3, 1800, 0),
    (5, 1200, 0),
    (8, 2400, 0),
    (12, 3600, 0),
    (15, 10, 1),
    (20, 5, 1),
    (25, 15, 1),
    (30, 8, 1),
]
Ce jeu est volontairement simple et artificiel : il permettra d'expliquer les mécanismes, mais pas de valider un détecteur réel.

À retenir : un perceptron apprend à partir d'erreurs, pas d'une compréhension humaine du réseau. La qualité des données, leur préparation, leur diversité et une évaluation indépendante sont aussi importantes que la formule mathématique.
ARX AI — Carnet d'apprentissage, leçons 1 à 3 — mise à jour du 13 novembre 2026.