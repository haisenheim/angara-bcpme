# Tutoriel technique — Augmentation de la taille des pièces jointes (ANGARA)

| Élément | Valeur |
|--------|--------|
| **Document** | Tutoriel infrastructure — pièces jointes |
| **Public** | Administrateurs système / DevOps |
| **Environnement cible** | Ubuntu, Nginx, PHP-FPM |
| **Application** | `/var/www/angara` |
| **Utilisateur système** | `angara` |
| **Objectif** | Autoriser le téléversement d’au moins **2 Mo** (recommandé : **10 Mo**, limite déjà prévue par ANGARA) |

---

## 1. Contexte

Les utilisateurs ANGARA signalent l’impossibilité de charger certains documents (PDF, JPG, PNG) lors du traitement des dossiers, des pièces exigibles ou des pièces jointes au dossier d’instruction.

**Point clé :** ANGARA accepte déjà jusqu’à **10 Mo** côté application Laravel. Si un fichier supérieur à 2 Mo échoue, la cause est presque toujours **Nginx** ou **PHP-FPM**, et non le code applicatif.

---

## 2. Limites applicatives ANGARA

| Type de document | Limite Laravel | Formats |
|------------------|----------------|---------|
| Pièces jointes au dossier d’instruction | 10 Mo (`max:10240` Ko) | PDF, JPG, JPEG, PNG |
| Pièces exigibles (prospect / client) | 10 Mo | PDF, JPG, JPEG, PNG |
| CNI des membres d’équipe | 10 Mo | PDF, JPG, JPEG, PNG |
| Modèles de documents (administration) | 15 Mo | PDF, Office, images, etc. |

**Dossiers de stockage sur le serveur :**

```
/var/www/angara/public/files/dossiers_pieces/
/var/www/angara/public/files/pieces_exigibles/
/var/www/angara/public/files/elements_constitutifs/
```

L’interface utilisateur affiche déjà : *« Fichier (PDF, JPG ou PNG, max. 10 Mo) »*.

---

## 3. Architecture du téléversement (3 niveaux)

Un fichier transite par **trois barrières** successives. **La plus restrictive l’emporte.**

```
Navigateur  →  [1] Nginx  →  [2] PHP-FPM  →  [3] Laravel (validation max:10240)
```

| Niveau | Paramètre | Valeur par défaut fréquente | Valeur recommandée |
|--------|-----------|----------------------------|-------------------|
| **Nginx** | `client_max_body_size` | **1 Mo** | **20 Mo** |
| **PHP** | `upload_max_filesize` | **2 Mo** | **20 Mo** |
| **PHP** | `post_max_size` | **8 Mo** | **25 Mo** (≥ upload + marge) |
| **Laravel** | règle `max:10240` | 10 Mo | Déjà OK — pas de changement requis |

> **Règle :** `post_max_size` ≥ `upload_max_filesize` ≥ limite métier souhaitée (2 Mo minimum).

---

## 4. Diagnostic — identifier la couche bloquante

### 4.1 Symptômes et causes probables

| Symptôme observé | Couche probable |
|------------------|-----------------|
| Erreur **413 Request Entity Too Large** (page blanche ou message Nginx) | **Nginx** ou proxy devant l’application |
| Échec silencieux / champ fichier vide après soumission | **PHP** (`upload_max_filesize = 2M` par défaut) |
| Message Laravel *« Le fichier ne doit pas dépasser 10240 kilobytes »* | **Validation Laravel** (fichier > 10 Mo) |
| Exception *PostTooLargeException* | **`post_max_size`** PHP trop bas |

### 4.2 Vérifier la configuration PHP (CLI)

```bash
php -i | grep -E 'upload_max_filesize|post_max_size|memory_limit'
```

### 4.3 Vérifier PHP-FPM (via le web)

```bash
echo '<?php phpinfo();' | sudo tee /var/www/angara/public/_phpinfo-test.php
```

Ouvrir `http://<votre-domaine>/_phpinfo-test.php`, noter `upload_max_filesize` et `post_max_size`, puis **supprimer immédiatement le fichier** :

```bash
sudo rm /var/www/angara/public/_phpinfo-test.php
```

### 4.4 Vérifier Nginx

```bash
grep -r "client_max_body_size" /etc/nginx/
```

Si absent ou fixé à `1m`, c’est probablement la cause des échecs au-delà de 1 Mo.

### 4.5 Consulter les logs

```bash
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/php*-fpm.log
```

---

## 5. Correctif — Nginx

Éditer le fichier de site ANGARA (ex. `/etc/nginx/sites-available/angara`) :

```nginx
server {
    listen 80;
    server_name votre-domaine.local;
    root /var/www/angara/public;

    index index.php;
    charset utf-8;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

> Adapter `php8.3-fpm.sock` selon la version PHP installée :
>
> ```bash
> ls /var/run/php/
> ```

Appliquer la configuration :

```bash
sudo nginx -t
sudo systemctl reload nginx
```

Un modèle de référence est disponible dans le dépôt ANGARA : `deploy/bcpme-vm/nginx-angara.conf` (avec `client_max_body_size 64M`).

---

## 6. Correctif — PHP-FPM

Identifier la version PHP installée :

```bash
php -v
```

### Option A — Pool dédié `angara` (recommandé)

Si un pool PHP-FPM existe pour l’utilisateur `angara` :

```bash
sudo nano /etc/php/8.3/fpm/pool.d/angara.conf
```

Exemple de configuration :

```ini
[angara]
user = angara
group = angara
listen = /var/run/php/php8.3-fpm-angara.sock
listen.owner = www-data
listen.group = www-data

pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3

php_admin_value[upload_max_filesize] = 20M
php_admin_value[post_max_size] = 25M
php_admin_value[memory_limit] = 256M
php_admin_value[max_execution_time] = 120
```

Dans ce cas, adapter la directive Nginx :

```nginx
fastcgi_pass unix:/var/run/php/php8.3-fpm-angara.sock;
```

### Option B — Pool par défaut `www.conf`

```bash
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
```

Ajouter en fin de fichier :

```ini
php_admin_value[upload_max_filesize] = 20M
php_admin_value[post_max_size] = 25M
php_admin_value[memory_limit] = 256M
php_admin_value[max_execution_time] = 120
```

### Option C — `php.ini` global

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

Modifier :

```ini
upload_max_filesize = 20M
post_max_size = 25M
memory_limit = 256M
max_execution_time = 120
```

Redémarrer PHP-FPM :

```bash
sudo systemctl restart php8.3-fpm
sudo systemctl status php8.3-fpm
```

> **Important :** redémarrer PHP-FPM, pas seulement Nginx.

---

## 7. Permissions disque et dossiers de stockage

Les fichiers sont écrits sous `public/files/` par l’utilisateur **`angara`** (via PHP-FPM).

```bash
cd /var/www/angara

sudo mkdir -p public/files/{dossiers_pieces,pieces_exigibles,elements_constitutifs}

sudo chown -R angara:angara public/files
sudo chmod -R 775 public/files

df -h /var/www
```

Vérifier que PHP-FPM s’exécute bien sous l’utilisateur **`angara`** :

```bash
grep -E '^user|^group' /etc/php/8.3/fpm/pool.d/*.conf
```

---

## 8. Cache Laravel (après changement de configuration serveur)

```bash
cd /var/www/angara
sudo -u angara php artisan config:cache
sudo -u angara php artisan route:cache
sudo -u angara php artisan view:cache
```

---

## 9. Plan de tests de validation

| Test | Taille | Résultat attendu |
|------|--------|------------------|
| 1 | ~500 Ko (PDF) | Succès |
| 2 | ~2,5 Mo (PDF) | **Succès** (test principal) |
| 3 | ~8 Mo (PDF) | Succès |
| 4 | ~12 Mo (PDF) | Échec Laravel « > 10 Mo » (comportement normal) |

**Procédure utilisateur :**

1. Se connecter à ANGARA.
2. Ouvrir un dossier d’instruction.
3. Cliquer **« Ajouter une pièce au dossier »**.
4. Téléverser un PDF de test de 2,5 Mo.
5. Vérifier l’apparition du fichier dans le tableau des pièces jointes.

**Vérification côté serveur :**

```bash
ls -lh /var/www/angara/public/files/dossiers_pieces/
```

---

## 10. Checklist de déploiement

```
[ ] client_max_body_size = 20M dans /etc/nginx/sites-available/angara
[ ] upload_max_filesize = 20M (PHP-FPM)
[ ] post_max_size = 25M (PHP-FPM)
[ ] nginx -t OK + reload nginx
[ ] restart php8.3-fpm OK
[ ] Pool PHP-FPM user = angara
[ ] public/files/ propriétaire angara:angara, chmod 775
[ ] Espace disque suffisant
[ ] Test 2,5 Mo réussi en conditions réelles
[ ] _phpinfo-test.php supprimé (si créé)
```

---

## 11. Synthèse des fichiers à modifier

| Fichier | Action |
|---------|--------|
| `/etc/nginx/sites-available/angara` | Ajouter ou augmenter `client_max_body_size 20M;` |
| `/etc/php/8.3/fpm/pool.d/angara.conf` ou `www.conf` | Définir `upload_max_filesize` et `post_max_size` |
| `/var/www/angara/public/files/` | Permissions `angara:angara`, mode `775` |

---

## 12. Commandes de référence (copier-coller)

```bash
# Nginx
sudo nano /etc/nginx/sites-available/angara
sudo nginx -t && sudo systemctl reload nginx

# PHP-FPM
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
sudo systemctl restart php8.3-fpm

# Permissions
sudo mkdir -p /var/www/angara/public/files/{dossiers_pieces,pieces_exigibles,elements_constitutifs}
sudo chown -R angara:angara /var/www/angara/public/files
sudo chmod -R 775 /var/www/angara/public/files

# Vérification
php -i | grep -E 'upload_max_filesize|post_max_size'
grep client_max_body_size /etc/nginx/sites-available/angara
```

---

## 13. Proxy / load balancer / WAF

Si ANGARA est derrière un **reverse proxy**, un **WAF** ou un **load balancer**, vérifier aussi leurs limites de taille de requête. Un proxy mal configuré peut renvoyer **413** avant que la requête n’atteigne Nginx.

---

## 14. Sécurité et bonnes pratiques

- Ne pas augmenter indéfiniment la limite (risque de saturation disque et d’abus).
- **20 Mo** couvre le besoin métier (≥ 2 Mo) et la limite applicative actuelle (10 Mo).
- Surveiller l’espace disque de `/var/www/angara/public/files/`.
- Ne jamais laisser de fichier `phpinfo.php` accessible en production.

---

## 15. Escalade vers l’éditeur ANGARA

Contacter l’éditeur **uniquement si**, après application de ce tutoriel :

- les trois niveaux (Nginx, PHP-FPM, Laravel) sont conformes ;
- les tests à 2,5 Mo et 8 Mo échouent encore ;
- ou si la banque souhaite une limite **supérieure à 10 Mo** (modification applicative requise).

---

## Annexe A — Messages d’erreur fréquents

| Message | Interprétation |
|---------|----------------|
| `413 Request Entity Too Large` | Nginx ou proxy |
| `POST Content-Length exceeds the limit` | `post_max_size` PHP |
| `The file "xxx" exceeds your upload_max_filesize ini directive` | `upload_max_filesize` PHP |
| `Le fichier ne doit pas dépasser 10240 kilobytes` | Validation Laravel (fichier > 10 Mo) |
| `Format de fichier non accepté` | Extension non autorisée (PDF / JPG / PNG uniquement) |

---

## Annexe B — Valeurs recommandées (synthèse)

| Paramètre | Minimum (besoin métier) | Recommandé |
|-----------|-------------------------|------------|
| `upload_max_filesize` | 2M | **20M** |
| `post_max_size` | 3M | **25M** |
| `client_max_body_size` | 2M | **20M** |
| Limite Laravel | — | **10M** (déjà en place) |
