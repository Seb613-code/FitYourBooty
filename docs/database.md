# Base de données

Le projet utilise une base de données :

MySQL / MariaDB

---

# Configuration

La configuration se trouve dans :

```
.env
```

Variables :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=***
DB_USERNAME=***
DB_PASSWORD=***
```

---

# Migrations

Les migrations Laravel se trouvent dans :

```
database/migrations
```

Elles permettent de créer automatiquement les tables.

Commande :

```
php artisan migrate
```

---

# Connexion manuelle

Connexion :

```
mysql -u USER -p
```

Sélection base :

```
USE DATABASE;
```

Lister tables :

```
SHOW TABLES;
```

---

# Sauvegarde base

Export :

```
mysqldump -u USER -p DATABASE > backup.sql
```

Import :

```
mysql -u USER -p DATABASE < backup.sql
```
