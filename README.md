# FIT

Application web développée avec **Laravel** permettant de suivre des données personnelles (poids, calories, objectifs) et de les visualiser sous forme de tableau et de graphique.

Le projet comprend :

* un formulaire de saisie des données
* un tableau listant les entrées
* un graphique interactif
* un système d'objectifs utilisateurs

Le projet est déployé sur un **VPS Linux** et le code est versionné via **GitHub**.

---

# Stack technique

* Laravel
* PHP 8+
* MySQL / MariaDB
* JavaScript
* Chart.js
* Git / GitHub
* VPS Linux (Ubuntu)

---

# Installation locale

Cloner le projet :

```
git clone git@github.com:Seb613-code/fit.git
cd fit
```

Installer les dépendances :

```
composer install
```

Créer le fichier `.env` :

```
cp .env.example .env
```

Générer la clé Laravel :

```
php artisan key:generate
```

Configurer la base de données dans `.env`.

Puis lancer les migrations :

```
php artisan migrate
```

Démarrer le serveur local :

```
php artisan serve
```

Application disponible sur :

```
http://localhost:8000
```

---

# Documentation

La documentation technique complète est disponible dans :

```
/docs
```

* architecture.md
* deployment.md
* database.md
* dev-workflow.md
