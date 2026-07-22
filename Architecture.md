                     Internet
                         │
                 Box Internet
                         │
                  Switch Gigabit
                         │
                 Raspberry Pi 5
                 (ARX Core)
                         │
        ┌────────────────┼────────────────┐
        │                │                │
   SSD système      Disque HDD       Onduleur (UPS)
     (NVMe)         8 à 20 To
        │
     Docker
        │
 ┌──────┼──────────────────────────────────────┐
 │      │        │        │        │           │
Core   VPN     Cloud     AI      DNS       Monitoring

# Infrastructure physique 

# 🖥️ ARX - Architecture Physique (Matériel)

## 📌 Objectif

Construire une infrastructure domestique fiable, évolutive et sécurisée pour héberger les différents services d'ARX :

- ARX Core
- ARX VPN
- ARX Cloud
- ARX AI
- ARX DNS
- ARX Monitoring

---

# Schéma de l'architecture

```text
                     Internet
                         │
                    Modem / Box
                         │
                  Switch Gigabit
                         │
                  Raspberry Pi 5
                    (ARX Core)
                         │
        ┌────────────────┼────────────────┐
        │                │                │
     SSD NVMe        HDD de données      UPS
       1 To            8 à 20 To
                         │
                      Docker
                         │
 ┌────────┬────────┬────────┬────────┬────────┬────────────┐
 │        │        │        │        │        │
Core     VPN     Cloud      AI      DNS    Monitoring
```

---

# Matériel nécessaire

## ✅ Raspberry Pi

- Raspberry Pi 5 (16 Go de RAM)
- Alimentation officielle Raspberry Pi 27W
- Boîtier ventilé
- Dissipateur thermique

---

## 💾 Stockage système

### SSD NVMe

Utilisé pour :

- Raspberry OS
- Docker
- Laravel
- Bases de données
- Conteneurs
- WireGuard
- Pi-hole
- Nextcloud
- Services ARX

Recommandation :

- 500 Go minimum
- 1 To recommandé

Exemples :

- Samsung 990 EVO
- Crucial P3 Plus
- WD Blue SN580

---

## 🔌 Adaptateur M.2

Permet de connecter le SSD au Raspberry.

Exemples :

- Raspberry Pi M.2 HAT+
- Pineberry HatDrive
- Geekworm X1001

---

## 📦 Stockage des données

Disque dur destiné uniquement aux données.

Contiendra :

- Cloud personnel
- Sauvegardes
- Documents
- IA
- Archives
- Médias
- Logs

Capacité recommandée :

- 8 To minimum
- 16 To recommandé
- 20 To si gros volume

Exemples :

- Seagate IronWolf
- WD Red Plus
- Toshiba N300

---

## 📀 Boîtier USB 3

Boîtier externe avec alimentation indépendante.

Utilisé pour :

- protéger le disque
- améliorer la stabilité
- faciliter le remplacement

---

## ⚡ Onduleur (UPS)

Protège contre :

- coupures de courant
- microcoupures
- corruption des données

Marques recommandées :

- APC
- Eaton
- CyberPower

---

## 🌐 Switch réseau

Minimum :

- Gigabit Ethernet

Idéal :

- Switch 2,5 Gb/s

Nombre de ports conseillé :

- 8 ports

Exemples :

- TP-Link
- Netgear
- Ubiquiti

---

## 🔗 Câblage

Utiliser uniquement :

- Cat6
- Cat6A

---

## 💨 Refroidissement

Le Raspberry Pi 5 chauffe rapidement.

Prévoir :

- ventilateur
- dissipateur
- boîtier ventilé

---

## 💾 Sauvegarde

Prévoir un second disque dur destiné uniquement aux sauvegardes automatiques.

Exemple :

HDD Principal

↓

Sauvegarde quotidienne

↓

HDD Backup

---

# Infrastructure Docker

```text
Docker

├── ARX Core
├── ARX VPN
├── ARX Cloud
├── ARX AI
├── ARX DNS
├── ARX Monitoring
├── PostgreSQL
├── Redis
├── WireGuard
├── Pi-hole
├── Nextcloud
├── Grafana
├── Prometheus
└── Portainer
```

---

# Évolution future

```text
Internet
    │
Firewall
    │
Switch 2.5 Gb
    │
──────────────────────────────────
│               │                │
Pi 5          NAS          Mini PC IA
│               │                │
ARX        Sauvegarde      IA avancée
```

---

# Liste d'achat

| Matériel | État |
|----------|------|
| ✅ Raspberry Pi 5 (16 Go) | Déjà acquis |
| SSD NVMe 1 To | ☐ À acheter |
| Raspberry Pi M.2 HAT+ | ☐ À acheter |
| HDD 16 To | ☐ À acheter |
| Boîtier USB 3 | ☐ À acheter |
| Onduleur (UPS) | ☐ À acheter |
| Switch Gigabit / 2,5 Gb/s | ☐ À acheter |
| Câbles Cat6 / Cat6A | ☐ À acheter |
| Boîtier ventilé | ☐ À acheter |
| Second HDD de sauvegarde | ☐ Optionnel |
| NAS (évolution future) | ☐ Plus tard |

---

# Philosophie d'ARX

> **Un système modulaire, sécurisé et évolutif.**
>
> Chaque composant est indépendant grâce à Docker afin de faciliter les mises à jour, la maintenance et les évolutions futures.
>
> L'objectif est de faire évoluer progressivement ARX d'un serveur domestique vers une véritable plateforme personnelle de cybersécurité et de services auto-hébergés.