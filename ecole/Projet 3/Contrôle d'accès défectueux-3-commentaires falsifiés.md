# contrôle d'accès défectueux-3-Commentaires falsifiés

## 1. Méthodologie

   1. Consulter la section des commentaires dans la boutique.
   2. Identifier le formulaire permettant de poster un commentaire.
   3. Observer le code source et les requêtes envoyées lors de la soumission.
   4. Remarquer que le champ 'id' du commentaire cote serveur .
   5. Modifier manuellement la valeur du champ "id".
   6. envoyer un commentaire, le serveur reactualise juste avant l'envoie.
   7. utilisation d'un code qui change l'id et envoie manuellement la requete:
                                                                                    fetch("/api/Feedbacks", {
                                                                                    method: "POST",
                                                                                    headers: { "Content-Type": "application/json" },
                                                                                    body: JSON.stringify({
                                                                                        comment: "erreur",
                                                                                        rating: 5,
                                                                                        UserId: 11
                                                                                    })
                                                                                    }).then(res => res.json()).then(console.log)
    8. étape reussi.

**techniques utilisées :**

   - manipulation de champ non validé

## 2. Vulnérabilités

- **type :** Absence de validation des entrées / falsification de données
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
   - Mettre en place une validation stricte des champs soumis.
   - Vérifier côté serveur que l’auteur correspond à l’utilisateur authentifié.

- **Correctifs :**
   - Lier automatiquement le champ "id" au compte connecté, sans possibilité de modification manuelle.
   - Ajouter une authentification renforcée pour la publication de contenu.

- **Bonnes pratiques :**
   - Effectuer des tests réguliers de sécurité sur les formulaires.
   - Suivre les recommandations OWASP concernant l’intégrité des données et la gestion des entrées utilisateur.