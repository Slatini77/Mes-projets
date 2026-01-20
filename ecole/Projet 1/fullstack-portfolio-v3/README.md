Projet 2 – Fullstack Portfolio v3
 Objectif
Créer un portfolio dynamique avec une interface d’administration permettant de gérer les informations affichées (compétences, expériences, formations, etc.).
 Technologies
- PHP
- MySQL
- HTML / CSS / JS
- Docker
- CRUD complet
Structure
- database/ → script SQL
- src/admin/ → gestion du contenu
- src/includes/ → db.php, header, footer
- src/assets/ → styles, scripts, images
- index.php → page principale
- view.php → affichage détaillé
 Fonctionnalités
- Connexion admin
- Gestion des utilisateurs
- Gestion des compétences
- Gestion des expériences
- Gestion des formations
- Affichage dynamique sur le portfolio public
 Installation
- Importer database/init.sql
- Configurer src/includes/db.php
- Lancer avec Docker :
docker-compose up
- Ouvrir : http://localhost
 Ce que j’ai appris
- CRUD complet
- Admin panel
- PHP + MySQL
- Organisation d’un projet fullstack
- Docker

 