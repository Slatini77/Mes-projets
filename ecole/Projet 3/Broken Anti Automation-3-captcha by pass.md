## Broken Anti Automation-3-captcha by pass

## 1. Méthodologie

1. ouvrir la page de feedback client.
2. inspecter le formulaire et repérer les champs requis (commentaire, note, captcha).
3. ouvrir les DevTools et l’onglet Réseau pour observer les requêtes lors d’un envoi.
4. tester un envoi manuel pour récupérer l’endpoint (ex: /api/Feedbacks).
5. vérifier si le captcha est uniquement vérifié côté client (DOM/JS).
6. supprimer/désactiver l’élément captcha côté client si présent (via DevTools).
7. automatiser l’envoi en boucle via la console (10+ requêtes en < 20s): 
                                                                            for (let i = 0; i < 10; i++) {
                                                                            fetch('/api/Feedbacks', {
                                                                                method: 'POST',
                                                                                headers: { 'Content-Type': 'application/json' },
                                                                                body: JSON.stringify({
                                                                                comment: `Feedback ${i}`,
                                                                                rating: 5,
                                                                                captcha: '' 
                                                                                })
                                                                            });
                                                                            }
8. valider que 10+ feedbacks sont acceptés dans la fenêtre de 20s (notification / historique).

**techniques utilisées :**

-automatisation JavaScript  
-manipulation du DOM  
-requêtes API fetch  
-contournement de contrôle côté client

## 2. Vulnérabilités

-**type :** Contournement CAPTCHA / Absence de rate limiting  
-**composant affecté :** Système de feedback et anti-bot  
-**Sévérité estimée :** Élevée

## 3. Risques

-envoi massif de feedbacks (spam)  
-pollution et incohérence des données  
-dégradation de l’expérience utilisateur  
-mauvaise réputation  
-perte de confiance

## 4. Actions

-**Mitigation :**  
-mettre en place une validation serveur du CAPTCHA.  
-activer un rate limiting et des quotas par utilisateur/IP.  
-utiliser des tokens anti-automation (nonce, délais aléatoires).  
-vérifier la provenance des requêtes (Origin/Referer) et ajouter un jeton CSRF.

-**Correctifs :**  
-remplacer les contrôles côté client par des vérifications serveur.  
-journaliser et bloquer les bursts anormaux (WAF/règles).  
-ajouter des défis progressifs (CAPTCHA adaptatif après n essais).

-**Bonnes pratiques :**  
-effectuer des tests réguliers de sécurité et de charge.  
-suivre les recommandations OWASP pour l’authentification et la gestion des entrées.  
-surveiller les métriques (feedbacks/min) et configurer des alertes.