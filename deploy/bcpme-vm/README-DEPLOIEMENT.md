# Fichiers pour la VM Ubuntu (Nginx + PHP 8.3-FPM)

Contenu du dossier :

| Fichier | Usage |
|--------|--------|
| `.env` ou `laravel.env` | Même contenu : placer en `/var/www/angara-bcpme/.env` |
| `nginx-angara.conf` | Copier vers `/etc/nginx/sites-available/angara` |
| `mysql-init.sql` | Créer les bases et l’utilisateur MySQL |

## Sécurité

Ce dossier contient des secrets (mots de passe, clés). Ne le commitez pas dans un dépôt public. Régénérez `APP_KEY` et `JWT_SECRET` si ce fichier a fuité.

Pour régénérer sur le serveur :

```bash
cd /var/www/angara-bcpme
php artisan key:generate --force
# JWT : mettre une nouvelle chaîne longue dans JWT_SECRET puis php artisan config:cache
```

## Déploiement rapide

1. **MySQL** : `sudo mysql < mysql-init.sql` (adapter le chemin).
2. **Laravel** : copier `laravel.env` → `/var/www/angara-bcpme/.env`, propriétaire `www-data` si besoin.
3. **Nginx** : installer le fichier site, activer le lien symbolique, `sudo nginx -t`, recharger Nginx.
4. **Laravel** : `composer install --no-dev --optimize-autoloader`, `php artisan storage:link`, migrations, `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`.

## Nginx

Si le socket PHP-FPM n’est pas `/var/run/php/php8.3-fpm.sock`, vérifier avec :

```bash
ls /var/run/php/
```

Adapter la ligne `fastcgi_pass` dans `nginx-angara.conf`.

## URL

`APP_URL` est défini sur `http://192.168.120.20`. Si l’accès se fait par un autre nom ou en HTTPS, mettre à jour `APP_URL` et `STRUCTURATION_CENTRAL_EXTRA` / `SANCTUM_STATEFUL_DOMAINS` en conséquence, puis `php artisan config:cache`.
