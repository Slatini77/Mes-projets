# Contôle d'accès défecteux-3-Avis falsifié

## 1. Méthodologie

   1. Consulter la section des avis produits dans la boutique.  
   2. Identifier le formulaire permettant de poster un commentaire.  
   3. Observer les requêtes envoyées lors de la soumission.  
   4. Modifier le **token d’authentification** pour usurper l’identité d’un autre utilisateur.  
   5. Soumettre l’avis falsifié.  
   6. Vérifier que l’avis apparaît avec le nom usurpé.  
   7. Exploit réussi.  

**techniques utilisées :**

   - Altération du token d’authentification  

## 2. Vulnérabilités

- **type :** Mauvaise gestion des tokens / falsification de données  
- **composant affecté :** Système de gestion des commentaires  
- **Séverité estimée :** Élevée  

## 3. Risques

- Usurpation d’identité d’autres utilisateurs  
- Diffusion de faux avis ou informations trompeuses  
- Atteinte à la réputation de la boutique  
- Perte de confiance des clients  
- Potentiel litige légal en cas de diffamation  

## 4. Actions

- **Mitigation :**  
   - Vérifier la validité et l’intégrité du token avant d’accepter la requête.  
   - Associer les actions uniquement au compte authentifié.  

- **Correctifs :**  
   - Mettre en place une signature forte des tokens (JWT avec vérification).  
   - Restreindre les droits d’écriture aux utilisateurs authentifiés.  

- **Bonnes pratiques :**  
   - Effectuer des tests réguliers de sécurité sur les formulaires.  
   - Suivre les recommandations OWASP concernant l’intégrité des données et la gestion des tokens.