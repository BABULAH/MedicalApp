# 🏥 Application de Gestion des Prises de Rendez-vous Médicaux

## 📖 Description générale
Cette application permet aux utilisateurs (patients) de **rechercher des médecins**, consulter leurs **informations et disponibilités**, puis **prendre un rendez-vous médical en ligne**.

Les médecins peuvent **accepter, refuser ou proposer un autre créneau**, et les utilisateurs reçoivent des **notifications en temps réel**.

🎯 **Objectif principal**  
- Simplifier l’accès aux soins  
- Réduire les files d’attente dans les structures médicales  
- Améliorer l’organisation et la gestion des rendez-vous médicaux  

---

## 🚀 Fonctionnalités principales

### 🔍 1. Recherche avancée de médecins

L’utilisateur peut rechercher un médecin selon plusieurs critères combinables.

#### a) Recherche par spécialité
- Cardiologue
- Dentiste
- Pédiatre
- Généraliste, etc.

📋 Résultats affichés :
- Nom et prénom
- Photo
- Spécialité
- Établissement (hôpital / clinique / cabinet)
- Adresse
- Disponibilités par jour
- Horaires précis (ex : 09h–11h, 11h30–13h)

---

#### b) Recherche par nom
- Recherche par nom partiel ou complet (ex : *Diop*)
- Résultats filtrés automatiquement par localité si sélectionnée
- Affichage de tous les médecins correspondants dans la zone choisie

---

#### c) Recherche par hôpital / clinique
- Recherche par nom d’établissement
- Liste de tous les médecins exerçant dans cet établissement

---

#### d) Recherche par localité
Exemples :
- Guédiawaye
- Pikine
- Dakar-Plateau

📍 Filtrage par :
- Spécialité
- Localité
- Distance (si géolocalisation activée)

📌 Exemple :
> Recherche : **Cardiologue + Guédiawaye**  
> Résultat : liste des cardiologues situés à Guédiawaye

---

#### e) Recherche automatique par géolocalisation ⭐
- Utilisation de la position de l’utilisateur (avec autorisation)
- Affichage des médecins les plus proches
- Calcul automatique de la distance (ex : 2,4 km)
- Tri possible par :
  - Distance
  - Disponibilité
  - Prix (optionnel)

---

## 🧩 2. Affichage des résultats de recherche

Les résultats sont affichés sous forme de **fiches médecin** contenant :

✔️ Photo du médecin  
✔️ Nom & prénom  
✔️ Spécialité  
✔️ Établissement  
✔️ Adresse + distance (si géolocalisation active)  
✔️ Disponibilités hebdomadaires  
✔️ Bouton **"Voir détails"**

---

## 👨‍⚕️ 3. Fiche détaillée du médecin

Chaque médecin dispose d’une page dédiée contenant :

### 📌 Informations générales
- Photo professionnelle
- Nom complet
- Spécialité médicale
- Numéro d’identification (si applicable)
- Biographie / description
- Expérience professionnelle
- Établissement (hôpital / clinique / cabinet)
- Adresse complète (avec carte)
- Contact (si autorisé)
- Tarifs (consultation, urgence…)
- Avis et notes (optionnel)

### 📅 Disponibilités détaillées
- Tableau hebdomadaire
- Plusieurs créneaux possibles par jour

---

## 📅 4. Prise de rendez-vous

### 4.1 Sélection de la date
- Affichage uniquement des jours travaillés
- Jours non disponibles grisés

### 4.2 Choix du créneau horaire
Exemples :
- 09h00 – 09h30
- 09h30 – 10h00
- 11h30 – 12h00

### 4.3 Choix du motif
Liste personnalisable :
- Consultation générale
- Urgence
- Suivi médical
- Résultats d’analyse
- Contrôle
- Visite de routine

### 4.4 Confirmation
- Validation via bouton **"Prendre rendez-vous"**
- Envoi de la demande au médecin

---

## 🔔 5. Gestion des rendez-vous

### 5.1 Notifications
- Notification médecin (app / email)
- Confirmation de demande côté utilisateur

### 5.2 Interface médecin
Le médecin peut :
- ✔️ Accepter le rendez-vous
- ❌ Refuser
- 🔄 Proposer un autre créneau (optionnel)

### 5.3 Notification utilisateur
- Notification selon la décision du médecin
- Par application, email ou SMS

---

## 🌍 6. Géolocalisation ⭐

Fonctionnalités :
- Affichage automatique des médecins proches
- Filtrage par distance
- Tri par :
  - Proximité
  - Disponibilité
  - Établissement le plus proche
- Affichage des cabinets sur une carte interactive

---

## 📜 7. Historique des rendez-vous

Section dédiée à l’utilisateur :

### 📌 Rendez-vous passés
- Historique des consultations
- Détails : médecin, date, heure, motif

### 📌 Rendez-vous en attente
- Demandes non encore validées

### 📌 Rendez-vous confirmés
- Rendez-vous acceptés
- QR Code d’accès rapide (optionnel)

### ❌ Annulation
- Annulation possible selon règles définies
  - Exemple : 12h ou 24h avant
- Notification envoyée au médecin

---

## 👥 8. Acteurs et rôles

### 👤 Utilisateur (Patient)
- Rechercher un médecin
- Filtrer par spécialité / nom / localité / établissement
- Consulter les disponibilités
- Prendre un rendez-vous
- Recevoir des notifications
- Gérer l’historique

---

### 👨‍⚕️ Médecin
- Gestion du profil
- Gestion des disponibilités
- Gestion des demandes de rendez-vous
- Validation, refus ou report des rendez-vous

---

### 🏥 Administrateur
- Gestion des médecins
- Gestion des établissements
- Vérification des comptes
- Configuration globale de la plateforme

---

## 🛠️ Technologies utilisées
- Backend : Laravel
- Base de données : MySQL
- Authentification : JWT
- Admin Panel : Filament
- API REST sécurisée
- Géolocalisation & notifications

---

## 🔐 Sécurité
- Fichier `.env` non versionné
- Authentification sécurisée (JWT)
- Séparation des rôles (Admin / Médecin / Patient)
- Accès contrôlé aux ressources

---

## 📌 Statut du projet
🚧 En cours de développement / amélioration continue

---

## 📄 Licence
Ce projet est sous licence **MIT**.
