# Documentation de déploiement en production

Guide complet pour déployer un projet Drupal 11 avec Docker sur un serveur de production.

## Prérequis serveur

### Logiciels requis
- Debian 12 ou Ubuntu 22.04+
- Docker Engine 24.0+
- Docker Compose V2
- Git
- OpenSSL (pour générer les secrets)

### Configuration réseau
- Ports 80 et 443 ouverts (HTTP/HTTPS)
- Nom de domaine configuré avec DNS pointant vers le serveur
- Accès SSH configuré

### Installation Docker (si nécessaire)
```bash
# Mise à jour du système
sudo apt update && sudo apt upgrade -y

# Installation Docker
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER

# Installation Docker Compose (si pas inclus)
sudo apt install docker-compose-plugin

# Redémarrer la session pour appliquer les groupes
```

## Architecture de déploiement

### Services Docker
- **Caddy** : Reverse proxy avec SSL automatique (Let's Encrypt)
- **PHP-FPM** : Serveur d'application Drupal 11
- **MariaDB 10.11** : Base de données MySQL
- **Redis 7** : Cache en mémoire

### Volumes persistants
- `mariadb_data` : Données de la base
- `drupal_files` : Fichiers publics Drupal
- `drupal_private` : Fichiers privés
- `redis_data` : Données Redis
- `caddy_data` : Certificats SSL
- `caddy_config` : Configuration Caddy
- `caddy_logs` : Logs Caddy

## Procédure de déploiement

### Étape 1 : Préparation du serveur

```bash
# Connexion SSH
ssh debian@VOTRE_IP

# Créer le répertoire de projet
sudo mkdir -p /var/www/VOTRE_PROJET
sudo chown $USER:$USER /var/www/VOTRE_PROJET
cd /var/www

# Cloner le dépôt Git
git clone git@github.com:VOTRE_ORG/VOTRE_PROJET.git
cd VOTRE_PROJET
```

### Étape 2 : Configuration environnement (.env)

Créer le fichier `.env` à la racine du projet :

```bash
# IMPORTANT : Ne JAMAIS commiter ce fichier dans Git
# Générer des mots de passe sécurisés :
# openssl rand -base64 32 | tr -d "=+/" | cut -c1-32

# Database
DB_ROOT_PASSWORD=GENERER_MOT_DE_PASSE_ROOT
DB_HOST=mariadb
DB_PORT=3306
DB_NAME=drupal_prod
DB_USER=drupal_user
DB_PASSWORD=GENERER_MOT_DE_PASSE_USER

# Drupal
DRUPAL_HASH_SALT=GENERER_HASH_SALT_64_CHARS
TRUSTED_HOST_PATTERNS=^votre-domaine\\.fr$|^www\\.votre-domaine\\.fr$
CONFIG_SYNC_DIRECTORY=../config/sync
FILE_PRIVATE_PATH=/var/www/private
FILE_TEMP_PATH=/tmp

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=GENERER_MOT_DE_PASSE_REDIS

# PHP
PHP_MEMORY_LIMIT=256M
PHP_MAX_EXECUTION_TIME=300
PHP_UPLOAD_MAX_FILESIZE=64M
PHP_POST_MAX_SIZE=64M

# Domain and SSL
DOMAIN_NAME=votre-domaine.fr
LETSENCRYPT_EMAIL=admin@votre-domaine.fr

# Backup
BACKUP_RETENTION_DAYS=30
```

Générer des secrets sécurisés :
```bash
# Mot de passe (32 caractères)
openssl rand -base64 32 | tr -d "=+/" | cut -c1-32

# Hash salt (64 caractères base64)
openssl rand -base64 64 | tr -d "\n"
```

Sécuriser le fichier :
```bash
chmod 600 .env
```

### Étape 3 : Préparer la base de données (migration)

Sur votre environnement local DDEV :
```bash
# Exporter la base de données locale
ddev ssh
cd /var/www/html
drush sql:dump --gzip > /var/www/html/migration/db.sql.gz
exit

# Ou utiliser le script fourni
./scripts/export-local.sh
```

Transférer vers le serveur :
```bash
# Sur votre machine locale
scp migration/db.sql.gz debian@VOTRE_IP:/var/www/VOTRE_PROJET/migration/
```

### Étape 4 : Créer les répertoires nécessaires

```bash
mkdir -p backups
mkdir -p web/sites/default/files
chmod 755 web/sites/default/files
```

### Étape 5 : Build de l'image Docker PHP

```bash
docker compose -f docker-compose.prod.yml build php
```

### Étape 6 : Démarrer MariaDB et Redis

```bash
# Démarrer les services de données
docker compose -f docker-compose.prod.yml up -d mariadb redis

# Attendre que MariaDB soit prêt (environ 30 secondes)
sleep 30

# Vérifier le statut
docker compose -f docker-compose.prod.yml ps
```

### Étape 7 : Importer la base de données

```bash
# Importer le dump SQL
gunzip -c migration/db.sql.gz | docker compose -f docker-compose.prod.yml exec -T mariadb \
  mysql -u root -p"${DB_ROOT_PASSWORD}" "${DB_NAME}"

# Vérifier l'import
docker compose -f docker-compose.prod.yml exec mariadb \
  mysql -u root -p"${DB_ROOT_PASSWORD}" -e "USE ${DB_NAME}; SHOW TABLES;" | head -20
```

### Étape 8 : Installer les dépendances Composer

```bash
# Installation en production (sans dev)
docker compose -f docker-compose.prod.yml exec -T php \
  composer install --no-dev --optimize-autoloader --no-interaction

# Vérifier l'installation
docker compose -f docker-compose.prod.yml exec -T php ls -la vendor/bin/drush
```

### Étape 9 : Démarrer PHP-FPM

```bash
# Démarrer le service PHP
docker compose -f docker-compose.prod.yml up -d php

# Attendre que le service soit healthy
sleep 15

# Vérifier les logs
docker compose -f docker-compose.prod.yml logs php --tail=50
```

### Étape 10 : Configuration Drupal

Le fichier `web/sites/default/settings.local.php` est déjà configuré pour lire les variables d'environnement. Il est automatiquement inclus par `settings.php`.

Vérifier la configuration :
```bash
docker compose -f docker-compose.prod.yml exec php \
  vendor/bin/drush status
```

### Étape 11 : Démarrer Caddy (HTTPS)

```bash
# Démarrer Caddy
docker compose -f docker-compose.prod.yml up -d caddy

# Suivre les logs pour voir l'obtention du certificat SSL
docker compose -f docker-compose.prod.yml logs -f caddy
```

Caddy va automatiquement :
- Obtenir un certificat SSL Let's Encrypt
- Configurer HTTPS avec HTTP/2 et HTTP/3
- Rediriger HTTP vers HTTPS
- Gérer le renouvellement automatique des certificats

### Étape 12 : Vérifications finales

```bash
# Statut de tous les services
docker compose -f docker-compose.prod.yml ps

# Tous les services doivent être "healthy"

# Tester l'accès HTTPS
curl -I https://votre-domaine.fr

# Générer un lien de connexion admin
docker compose -f docker-compose.prod.yml exec php \
  vendor/bin/drush user:login --uid=1 --uri=https://votre-domaine.fr
```

### Étape 13 : Nettoyage post-déploiement

```bash
# Supprimer le fichier de migration (sécurité)
rm -f migration/db.sql.gz

# Vider les caches Drupal
docker compose -f docker-compose.prod.yml exec php \
  vendor/bin/drush cache:rebuild
```

## Configuration DNS

Avant le déploiement, configurer les enregistrements DNS :

```
Type A    : votre-domaine.fr      → IP_DU_SERVEUR
Type A    : www.votre-domaine.fr  → IP_DU_SERVEUR
Type AAAA : votre-domaine.fr      → IPv6 (si disponible)
```

## Gestion quotidienne

### Connexion admin Drupal
```bash
docker compose -f docker-compose.prod.yml exec php \
  vendor/bin/drush uli --uri=https://votre-domaine.fr
```

### Vider les caches
```bash
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush cr
```

### Voir les logs en temps réel
```bash
# Tous les services
docker compose -f docker-compose.prod.yml logs -f

# Service spécifique
docker compose -f docker-compose.prod.yml logs -f php
docker compose -f docker-compose.prod.yml logs -f caddy
docker compose -f docker-compose.prod.yml logs -f mariadb
```

### Redémarrer un service
```bash
docker compose -f docker-compose.prod.yml restart php
docker compose -f docker-compose.prod.yml restart caddy
```

### Accéder au shell PHP
```bash
docker compose -f docker-compose.prod.yml exec php bash
```

### Exécuter des commandes Drush
```bash
# Mettre à jour la base de données
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush updatedb -y

# Importer la configuration
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush config:import -y

# Exporter la configuration
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush config:export -y

# Voir le statut
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush status
```

## Sauvegardes

### Créer une sauvegarde manuelle
```bash
docker compose -f docker-compose.prod.yml run --rm backup
```

Les sauvegardes sont stockées dans `./backups/` et incluent :
- Dump de la base de données
- Fichiers Drupal (sites/default/files)
- Fichiers privés

### Automatiser les sauvegardes (cron)
```bash
# Éditer le crontab
crontab -e

# Ajouter cette ligne pour une sauvegarde quotidienne à 2h du matin
0 2 * * * cd /var/www/VOTRE_PROJET && docker compose -f docker-compose.prod.yml run --rm backup
```

## Mise à jour du code

### Procédure de mise à jour
```bash
# 1. Connexion au serveur
ssh debian@VOTRE_IP
cd /var/www/VOTRE_PROJET

# 2. Créer une sauvegarde avant mise à jour
docker compose -f docker-compose.prod.yml run --rm backup

# 3. Mettre le site en maintenance
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush state:set system.maintenance_mode 1
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush cr

# 4. Récupérer les dernières modifications
git pull origin main

# 5. Mettre à jour les dépendances Composer
docker compose -f docker-compose.prod.yml exec php \
  composer install --no-dev --optimize-autoloader --no-interaction

# 6. Appliquer les mises à jour de base de données
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush updatedb -y

# 7. Importer la configuration
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush config:import -y

# 8. Vider les caches
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush cr

# 9. Désactiver le mode maintenance
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush state:set system.maintenance_mode 0
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush cr
```

Ou utiliser le script fourni :
```bash
./scripts/update.sh
```

## Monitoring et performance

### Vérifier l'utilisation des ressources
```bash
# Stats des conteneurs
docker stats

# Espace disque
df -h
du -sh /var/lib/docker/volumes/*
```

### Optimiser les performances
```bash
# Vérifier la configuration PHP
docker compose -f docker-compose.prod.yml exec php php -i | grep memory

# Vérifier Redis
docker compose -f docker-compose.prod.yml exec redis redis-cli -a "${REDIS_PASSWORD}" INFO stats
```

## Dépannage

### Les services ne démarrent pas
```bash
# Voir les logs d'erreur
docker compose -f docker-compose.prod.yml logs

# Redémarrer tous les services
docker compose -f docker-compose.prod.yml restart

# En dernier recours, tout recréer
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up -d
```

### Problèmes de certificat SSL
```bash
# Voir les logs Caddy
docker compose -f docker-compose.prod.yml logs caddy

# Vérifier que les ports 80 et 443 sont accessibles
sudo netstat -tlnp | grep -E ':(80|443)'

# Tester la connectivité Let's Encrypt
curl -I http://votre-domaine.fr/.well-known/acme-challenge/test
```

### Problèmes de base de données
```bash
# Accéder à MySQL
docker compose -f docker-compose.prod.yml exec mariadb \
  mysql -u root -p"${DB_ROOT_PASSWORD}" "${DB_NAME}"

# Vérifier les tables
SHOW TABLES;

# Vérifier la taille de la base
SELECT table_schema AS "Database",
       ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS "Size (MB)"
FROM information_schema.TABLES
GROUP BY table_schema;
```

### Erreurs de permissions fichiers
```bash
# Corriger les permissions des fichiers
docker compose -f docker-compose.prod.yml exec php chown -R www-data:www-data /var/www/html/web/sites/default/files
docker compose -f docker-compose.prod.yml exec php chmod -R 755 /var/www/html/web/sites/default/files
```

## Sécurité

### Bonnes pratiques
- Ne JAMAIS commiter le fichier `.env` dans Git (déjà dans .gitignore)
- Utiliser des mots de passe forts générés aléatoirement
- Garder Docker et les images à jour
- Faire des sauvegardes régulières
- Monitorer les logs pour détecter les tentatives d'intrusion
- Limiter l'accès SSH (clés uniquement, pas de mot de passe)

### Mise à jour de sécurité
```bash
# Mettre à jour les images Docker
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d

# Mettre à jour le système
sudo apt update && sudo apt upgrade -y
```

## Ressources utiles

- [Documentation Drupal 11](https://www.drupal.org/docs/11)
- [Documentation Caddy](https://caddyserver.com/docs/)
- [Documentation Docker Compose](https://docs.docker.com/compose/)
- [Drush Commands](https://www.drush.org/latest/commands/)

## Support

Pour toute question ou problème :
1. Vérifier les logs : `docker compose -f docker-compose.prod.yml logs`
2. Consulter cette documentation
3. Contacter l'équipe de développement
