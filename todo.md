# TODO - Auth & User Management

## P0 - Bloquant
- Corriger la redirection logout vers la page verify-email (done = redirection sur page publique sans erreur). [DONE]

## P1 - Important
- Diagnostiquer et corriger le flux forgot-password (done = renvoi d'email OK, pas d'erreur au resend). [DONE]
- Forcer la verification email des nouveaux comptes (done = acces protege tant que non verifie). [DONE]

## P2 - Qualite / UX
- Revoir middleware/guards/session pour durcissement prod (done = checklist securite validee). [DONE]
- Ajouter un flow de completion profil (done = ecran dedie + champs optionnels clairs). [DONE]
- Centrer "Fit your Booty" sur login/register (done = alignement OK + spacing 5px). [DONE]
- Traduire toutes les pages d'auth en FR (done = 100% des strings traduites + tests manuels). [DONE]
- Faire un vrai dashboard en page d'accueil qui reprenne un etat des lieux des autres onglets (graphique poids/cal, calendrier entrainement).
