# Architecture technique

## Vue générale

Le projet suit une architecture classique :

Développement local → GitHub → VPS production

Flux :

Développeur
↓
GitHub
↓
VPS
↓
Serveur Web
↓
Laravel
↓
Base de données

---

# Composants

## Application

Framework :

Laravel

Langage :

PHP

Structure principale :

```
app/
routes/
resources/
database/
public/
storage/
```

---

# Serveur

Type :

VPS Linux

Accès :

SSH

Commande :

```
ssh seb@82.165.150.107
```

---

# Serveur web

Apache ou Nginx.

Le document root pointe vers :

```
/var/www/fit/public
```

---

# Base de données

Type :

MySQL / MariaDB

Configuration dans :

```
.env
```

Variables principales :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=***
DB_USERNAME=***
DB_PASSWORD=***
```

---

# Stockage des secrets

Les secrets ne sont **pas dans GitHub**.

Ils sont stockés dans :

```
.env
```

Ce fichier est ignoré par Git via :

```
.gitignore
```

---

# Logs

Logs Laravel :

```
storage/logs/laravel.log
```
