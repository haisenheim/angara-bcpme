# Procédures d’installation — Solution ANGARA

| Élément | Valeur |
|--------|--------|
| **Éditeur** | ANGARA SOLUTIONS |
| **Client** | BCPME — Banque Camerounaise des Petites et Moyennes Entreprises |
| **Document** | Procédures d’installation |
| **Référence document** | **ANG-BCPME-REC-001** |
| **Indice** | B |
| **Date d’effet de l’indice** | 13/05/2026 |
| **Registre** | Voir `NUMEROTATION.md` |

---

## 1. Objet et périmètre

Le présent document décrit les étapes d’installation et de mise en service de la solution **ANGARA** dans l’environnement cible du Client (hébergement, réseau, base de données, services externes). Il s’applique aux versions **standard** et **cible** livrées dans le cadre du projet BCPME.

**Hors périmètre** (sauf mention contraire au bon de commande ou à l’annexe projet) : câblage réseau physique, fourniture de matériel, licences tiers non incluses contractuellement.

---

## 2. Prérequis côté Client

Le Client s’engage à mettre à disposition, avant installation :

| Ressource | Spécification indicative (à ajuster selon charge) |
|-----------|---------------------------------------------------|
| Serveur applicatif | **Ubuntu Server, édition LTS** (version LTS de préférence, ex. 22.04 ou 24.04 selon cycle support) ; même hôte pour l’application Laravel et le conteneur du microservice ci-dessous |
| Accès administration | **SSH** ; compte **non root**, membre du groupe **`sudo`** (élévation ponctuelle `sudo` autorisée, connexion directe `root` par SSH déconseillée) |
| Interpréteur PHP | Version **≥ 8.3** (minimum requis sur l’environnement cible) |
| Serveur web | Nginx ou Apache avec configuration PHP-FPM |
| Base de données | **MySQL ≥ 8** (minimum requis) ; utilisateur et schéma dédiés ANGARA |
| Conteneurisation | **Docker Engine** et plugin **Docker Compose** (ou équivalent validé) pour exécuter le microservice ci-après |
| Microservice instruction | Image **`alliages/angara-instruction`** (registre Docker Hub ou dépôt indiqué par l’éditeur), **obligatoire en production** pour la génération de la grille de notation par l’analyste financier : conteneur **actif sur le même serveur**, port d’écoute **8080** (TCP), accessible localement par l’application (ex. `127.0.0.1:8080`) |
| Stockage | Espace disque suffisant (code, logs, sauvegardes, pièces jointes, données Docker) |
| Réseau | URL d’accès, certificat TLS, règles firewall ouvertes vers services requis ; **ne pas exposer le port 8080 sur Internet** sauf avis de sécurité explicite (usage attendu : bouclage local ou réseau interne) |
| Comptes de service | Boîtes ou identifiants pour notifications, intégrations (ex. stockage objet, push) si contractuellement prévus |
| Sauvegarde / supervision | Mécanismes de sauvegarde BDD et fichiers selon politique BCPME ; monitoring du conteneur `alliages/angara-instruction` |

**Contacts Client** : [NOM, FONCTION, TÉLÉPHONE, E-MAIL].

---

## 3. Livrables logiciels (référence)

| Livrable | Format | Emplacement / référence |
|----------|--------|-------------------------|
| Code source ou package de déploiement | [ZIP / dépôt Git / artefact CI] | [Chemin / URL / numéro de build] |
| Fichier d’environnement modèle | `.env.example` | Joint au package |
| Scripts de migration base de données | Artisan / migrations Laravel | Répertoire `database/migrations` |
| Documentation technique minimale | [Lien / pièce jointe] | [Référence] |

---

## 4. Procédure d’installation (séquence type)

Les commandes ci-dessous sont données à titre indicatif ; l’exécution réelle est validée sur la version livrée et la note de version associée.

### 4.1 Préparation

1. Se connecter au serveur en SSH avec le **compte de déploiement** (non root, membre de `sudo`).
2. Créer l’arborescence d’application sur le serveur cible : `[chemin]` (appartenance cohérente avec l’utilisateur du service web et les bonnes pratiques du Client).
3. Déposer le package livré et vérifier son intégrité (checksum si fourni) : `[valeur]`.
4. Configurer le serveur web (vhost) pointant vers le répertoire `public` de Laravel.

### 4.2 Dépendances et configuration

1. Contrôler la version PHP : `php -v` → **8.3 ou supérieur** sur ce serveur.
2. Installer les dépendances PHP : `composer install --no-dev --optimize-autoloader` (ou procédure équivalente validée).
3. Dupliquer `.env.example` vers `.env` et renseigner les paramètres : base de données, URL d’application, mail, queues, clés API, chemins de stockage, **URL du microservice instruction** (pointant vers `http://127.0.0.1:8080` ou la clé prévue par la note de version / `docker-compose` fourni), etc.
4. Générer la clé applicative : `php artisan key:generate`.
5. Vérifier les droits sur `storage/` et `bootstrap/cache/` (écriture par l’utilisateur du service web).

### 4.3 Base de données

1. Vérifier la version du moteur **MySQL ≥ 8** (ex. `SELECT VERSION();` ou procédure d’inventaire SI).
2. Créer la base et l’utilisateur SQL avec les droits nécessaires.
3. Exécuter les migrations : `php artisan migrate` (avec options validées pour l’environnement).
4. Si prévu au plan de déploiement : exécuter les seeders ou imports de données de référence : `[commandes ou scripts]`.

### 4.4 Microservice Docker `alliages/angara-instruction` (grille de notation)  

En **production**, la génération de la grille de notation par l’**analyste financier** suppose ce service **actif** sur **le même serveur** que l’application ANGARA.

1. Vérifier Docker : `docker --version` et, si pertinent, `docker compose version`.
2. **Charger l’image** depuis le dépôt Docker (adapter le tag si la note de version en impose un) :  
   `docker pull alliages/angara-instruction:[tag]`
3. **Démarrer** le conteneur avec publication du port **8080** vers l’hôte (exemple — à ajuster selon le `docker-compose.yml` ou la fiche livrable officielle) :  
   `docker run -d --name angara-instruction -p 127.0.0.1:8080:8080 --restart unless-stopped alliages/angara-instruction:[tag]`  
   *Ou*, si le projet fournit un compose : `docker compose up -d` depuis le répertoire indiqué dans la livraison.
4. Contrôler que le conteneur est **en cours d’exécution** : `docker ps` (statut « Up ») et journal : `docker logs angara-instruction` (ou nom réel du conteneur).
5. Vérifier la **joignabilité** depuis l’hôte : `curl -sf http://127.0.0.1:8080/[health|endpoint fourni]` ou équivalent décrit dans la documentation du microservice.
6. S’assurer que la variable d’environnement Laravel (nom exact selon livraison, ex. URL de service instruction) **correspond** à cette base URL.
7. **Politique de redémarrage** : `unless-stopped` ou équivalent afin que le service reprenne après reboot du serveur ; documenter toute dépendance (fichiers montés, secrets).

### 4.4.1 Référentiel notation PME (`sme_notes`)

La **notation PME** et l’**avis SME** affichés dans la grille d’instruction sont lus dans la table `sme_notes` : la ligne est sélectionnée par la colonne **`note`** (égale à la note pondérée finale arrondie) ; la **mention** et la **description** de cette ligne constituent l’avis SME.

Après migration, charger le référentiel depuis le fichier `sme_notes.sql` à la racine du projet :

```bash
php artisan db:seed --class=SmeNotesSeeder
```

(`DatabaseSeeder` appelle aussi ce seeder. Source : `sme_notes.sql` ou chemin `SME_NOTES_SQL_PATH` dans `.env`. En recette, vérifier `SELECT COUNT(*) FROM sme_notes` → **10** lignes.)

### 4.5 Optimisation et services

1. Mise en cache configuration / routes / vues si applicable :  
   `php artisan config:cache` — `php artisan route:cache` — `php artisan view:cache`.
2. Files d’attente / planificateur : configurer le worker (Supervisor ou équivalent) et la tâche cron `php artisan schedule:run`.
3. Configurer la rotation des logs et la supervision HTTP / disque selon standards BCPME.

### 4.6 Contrôles post-installation (smoke tests)

| Contrôle | Résultel attendu | Résultat (OK / KO) | Commentaire |
|----------|------------------|---------------------|-------------|
| Page de connexion / healthcheck | HTTP 200 | | |
| Connexion utilisateur test | Authentification OK | | |
| Accès module métier représentatif | Fonctionnel | | |
| Écriture BDD (action simple) | Persistant | | |
| Upload / fichier (si applicable) | Fichier accessible | | |
| Tâche planifiée / queue (si applicable) | Exécution observée | | |
| Conteneur `alliages/angara-instruction` | `docker ps` — état Up ; port 8080 joignable en local | | |
| Génération grille de notation (parcours analyste financier) | Appel au microservice OK (pas d’erreur métier bloquante) | | |
| Référentiel `sme_notes` (notation PME / avis SME) | 10 lignes ; avis SME visible après note finale | | |

---

## 5. Sécurité et conformité (rappels)

- Ne pas exposer `.env`, répertoires de sauvegarde ni outils d’administration sur Internet sans restriction.
- Appliquer le principe du moindre privilège pour comptes BDD et OS ; **compte SSH dédié** avec `sudo`, sans exposition SSH du compte `root`.
- Limiter l’écoute du port **8080** à l’interface de bouclage (**127.0.0.1**) sauf architecture réseau interne explicitement validée.
- Journaliser les accès administrateurs selon politique BCPME ; inclure la rotation des logs Docker si pertinent.

---

## 6. Retour arrière (rollback)

En cas d’échec critique avant bascule production :

1. Restaurer la sauvegarde BDD prise avant migration.
2. Repositionner la version précédente du code (balise Git / package archivé).
3. Vider les caches applicatifs : `php artisan cache:clear` et commandes associées validées.
4. Documenter l’incident dans le journal d’intervention.

---

## 7. Responsabilités

| Acteur | Rôle |
|--------|------|
| ANGARA SOLUTIONS | Fourniture des livrables, assistance technique à l’installation, validation technique des étapes sous réserve des accès fournis |
| BCPME | Environnement, accès, sauvegardes, ouvertures réseau, validation fonctionnelle et recette |

---

## 8. Historique des révisions

| Indice | Date | Auteur | Modifications |
|--------|------|--------|----------------|
| A | [date] | [nom] | Création initiale |
| B | 13/05/2026 | ANGARA SOLUTIONS | Cible Ubuntu LTS ; PHP ≥ 8.3 ; MySQL ≥ 8 ; SSH non-root / sudo ; microservice Docker `alliages/angara-instruction` (port 8080, même serveur) |

---

## 9. Annexes

- Annexe A : Schéma d’architecture cible (application Laravel + MySQL 8 + conteneur `alliages/angara-instruction:8080` sur même hôte) [lien ou référence].  
- Annexe B : Liste des variables d’environnement `.env` [tableau] — inclure la variable d’URL du microservice instruction selon la livraison.  
- Annexe C : Contacts support éditeur [coordonnées].  
- Annexe D (optionnel) : Extrait du `docker-compose` ou commande `docker run` validée pour `alliages/angara-instruction`.
