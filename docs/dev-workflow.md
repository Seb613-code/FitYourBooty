# Workflow de développement

Ce document décrit le workflow Git du projet.

---

# Étape 1 : développement local

Cloner le projet :

```
git clone git@github.com:Seb613-code/fit.git
```

Modifier le code.

---

# Étape 2 : commit

Ajouter les fichiers :

```
git add .
```

Créer un commit :

```
git commit -m "description modification"
```

---

# Étape 3 : envoyer sur GitHub

```
git push
```

---

# Étape 4 : mise à jour serveur

Connexion :

```
ssh seb@82.165.150.107
```

Puis :

```
cd /var/www/fit
git pull
composer install
php artisan migrate
php artisan optimize:clear
```

---

# Vérifier l'état du dépôt

```
git status
```

Voir l'historique :

```
git log --oneline
```

---

# Bonnes pratiques

* faire des commits petits et fréquents
* utiliser des messages de commit clairs
* ne jamais commiter le fichier `.env`
* ne jamais commiter les clés API
