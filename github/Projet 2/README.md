**Projet 3 eTodo (Application de gestion de tâches) **

** Objectif **  
Créer une application web complète permettant de gérer des tâches via une API sécurisée et une interface simple.

** Technologies utilisées **  
Backend : Node.js, Express, MySQL  
Frontend : HTML, CSS, JavaScript  
Déploiement : Docker, Docker Compose  
Base de données : MySQL

** Structure du projet **

etodo  
- backend  
  - src  
    - index.js  
    - config  
    - middleware  
    - routes  
  - Dockerfile  
  - wait-for-db.sh  
  - package.json  
- frontend  
  - index.html  
  - script.js  
  - style.css  
- docker-compose.yml  
- e-todo.sql  
- .env.example  

** Fonctionnalités principales **  
- Création de compte et connexion  
- Authentification sécurisée  
- Ajout, modification et suppression de tâches  
- API REST structurée  
- Interface simple et responsive  
- Déploiement via Docker

** Installation et lancement **  
1. Cloner le projet  
2. Créer un fichier `.env` à partir de `.env.example`  
3. Importer `e-todo.sql` dans MySQL  
4. Lancer avec Docker : `docker-compose up`  
5. Ouvrir l’interface sur `http://localhost:3000`

** Ce que j’ai appris **  
- Structurer un backend Express  
- Créer une API REST sécurisée  
- Connecter un frontend à une API  
- Utiliser Docker  
- Organiser un projet complet backend + frontend

** Sécurité **  
Le fichier `.env` n’est pas inclus pour des raisons de sécurité.  
Un fichier `.env.example` est fourni pour la configuration.

** Conclusion **  
Ce projet m’a permis de comprendre le fonctionnement d’une application web complète et de renforcer mes bases en développement web et en déploiement.
