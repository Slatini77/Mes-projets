# Injection-3-login Jim

## 1. Méthodologie

   1.consulter le code source du site à la recherche d'information.
   2.trouver le fichier tutorial.js
   3.cherchez des information sur jim.
   4.chercher l'adresse mail de jim dans la boutique.
   5.trouver l'adresse mail dans l'avis d'un des produit.
   6.entrée l'adresse mail dans la page de connexion.
   7.teste d'injection SQL en ajoutant ' '-- ' a la fin du mail.
   8.connexion reussi.

**techniques utilisées :**

   -injection SQL

## 2. Vulnérabilités

-**type :** Injection SQL
-**composant affecté :** Systeme d'authentification
-**Séverité estimée :**Critique

## 3.Risques

-Connexion a des comptes tiers
-Accés aux informations personnelles des utilisateurs
-Fuite de donnes 
-mauvaise reputation
-perte de confiance

## 4.Actions

-**Mitigation :**
   -Mettre en place une validation stricte des entrées.
   -Utiliser des requêtes préparées au lieu de concaténer les inputs.

-**Correctifs :**
   -Modifier le code SQL pour éviter l’exécution directe des données utilisateur.
   -Ajouter une authentification multi-facteurs.

-**Bonnes pratiques :**
   -Effectuer des tests réguliers de sécurité.
   -Suivre les recommandations OWASP pour l’authentification.