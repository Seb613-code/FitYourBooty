# Déploiement sur le serveur

Cette documentation décrit la procédure pour mettre à jour l'application sur le VPS.

---

# Connexion au serveur

```
ssh seb@82.165.150.107
```

---

# Aller dans le projet

```
cd /var/www/fit
```

---

# Mettre à jour le code

```
git pull
```

---

# Installer les dépendances

```
composer install --no-dev --optimize-autoloader
```

---

# Lancer les migrations

```
php artisan migrate
```

---

# Nettoyer le cache

```
php artisan optimize:clear
```

---

# Vérifier les permissions

```
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

# Redémarrer le serveur web

Apache :

```
sudo systemctl restart apache2
```

Nginx :

```
sudo systemctl restart nginx
```

---

# Debug

Consulter les logs :

```
tail -f storage/logs/laravel.log
```
