# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Drupal 11 project using the drupal/recommended-project template with DDEV for local development. The project is named "esokami-drp" and uses a composer-based workflow with the web root located in the `web/` directory.

## Development Environment

### ⚠️ NOTE: PROD → DEV Synchronization

**This section is for reference if you have a production environment configured.**

This workflow is useful when configuration is regularly modified directly in production via the Drupal admin interface (CSS, SMTP, settings, etc.).

**The Rule:**
```bash
# ALWAYS run this command FIRST before any development work
./scripts/sync-prod-to-dev.sh

# OU avec la commande DDEV (une fois DDEV redémarré)
ddev sync-prod
```

**What this command does:**
1. Exports configuration from PROD
2. Commits and pushes from PROD to Git
3. Pulls changes to DEV
4. Imports configuration to DEV
5. **Exports database from PROD** (NEW)
6. **Imports database to DEV** (NEW)
7. Clears cache in DEV

**Options:**
```bash
# Mode complet (par défaut) : Config + BDD
./scripts/sync-prod-to-dev.sh

# Mode config uniquement (plus rapide, sans BDD)
./scripts/sync-prod-to-dev.sh --skip-db
```

**Why this is critical:**
- ❌ WITHOUT sync: `drush cim` in prod will OVERWRITE production config with dev config → **LOSS OF PROD DATA**
- ✅ WITH sync: DEV always reflects PROD state → **NO DATA LOSS POSSIBLE**

**Script location:**
- Script: `scripts/sync-prod-to-dev.sh`
- DDEV command: `ddev sync-prod`

### DDEV Commands
```bash
# CRITICAL: Synchronize PROD → DEV (run FIRST, ALWAYS)
ddev sync-prod

# Start the development environment
ddev start

# Stop the development environment
ddev stop

# SSH into the web container
ddev ssh

# Run Drush commands
ddev drush <command>

# Run Composer commands
ddev composer <command>

# Clear Drupal cache
ddev drush cr

# Import configuration
ddev drush cim -y

# Export configuration
ddev drush cex -y

# Database operations
ddev drush sql:dump > backup.sql
ddev import-db < backup.sql
```

### Common Development Tasks
```bash
# Install dependencies
ddev composer install

# Update Drupal core and contributed modules
ddev composer update drupal/core-recommended --with-dependencies

# Install a new module
ddev composer require drupal/<module_name>
ddev drush en <module_name>

# Run database updates
ddev drush updatedb

# Rebuild cache
ddev drush cache:rebuild

# Check status
ddev drush status
```

### Testing Commands
```bash
# Run PHPUnit tests (from web/core)
ddev exec -d /var/www/html/web/core ../../vendor/bin/phpunit

# Run specific test
ddev exec -d /var/www/html/web/core ../../vendor/bin/phpunit path/to/TestClass.php

# Run PHPCS for code standards
ddev exec vendor/bin/phpcs --standard=web/core/phpcs.xml.dist web/modules/custom
```

### SASS/SCSS Workflow (Esokami Theme)

**Important:** SASS/Gulp s'exécute sur l'hôte (votre machine locale), pas dans DDEV.

**Chemin du thème :**
```bash
cd web/themes/custom/esokami
```

**Commandes essentielles :**
```bash
# Installation initiale (une seule fois)
npm install

# Compiler le CSS (build manuel)
gulp styles

# Mode watch (recompilation automatique + browser-sync)
gulp

# Vider le cache Drupal si les styles ne s'appliquent pas
ddev drush cr
```

**Fichiers SCSS à éditer :**
- `scss/variables.scss` - Variables Bootstrap (couleurs, espacements, etc.)
- `scss/typography.scss` - Configuration typographique
- `scss/style.scss` - Styles custom du thème
- `scss/mixins.scss` - Mixins réutilisables

**Fichiers générés (ne pas éditer) :**
- `css/style.css` - CSS compilé (chargé automatiquement par Drupal)
- `css/bootstrap.css` - Bootstrap compilé

**Architecture :**
- Les fichiers `.scss` sont les sources (commités dans Git)
- Les fichiers `.css` sont générés (gitignorés, recréés à la compilation)
- Toujours éditer les `.scss`, jamais les `.css`

## Project Structure

### Key Directories
- `web/` - Document root containing Drupal core and all web-accessible files
- `web/modules/custom/` - Custom modules specific to this project
- `web/modules/contrib/` - Contributed modules from drupal.org
- `web/themes/custom/` - Custom themes
- `web/sites/default/` - Default site configuration and files
- `vendor/` - Composer dependencies (not web-accessible)
- `recipes/` - Drupal recipes for automated configuration
- `.ddev/` - DDEV configuration and custom commands

### Configuration Management
- Configuration split is enabled via `drupal/config_split` module
- Settings files:
  - `web/sites/default/settings.php` - Main settings file
  - `web/sites/default/settings.ddev.php` - DDEV-specific settings (auto-generated)

### Key Technologies
- **PHP Version**: 8.3
- **Database**: MariaDB 10.11
- **Web Server**: nginx-fpm
- **Drupal Version**: 11.2
- **Composer Version**: 2
- **Redis**: Available via drupal/redis module for caching

## Architecture Overview

This is a standard Drupal 11 installation using:
1. **Composer-based workflow**: All dependencies managed through composer.json
2. **DDEV for local development**: Provides consistent development environment
3. **Drush**: Command-line interface for Drupal management
4. **Configuration Management**: Built-in configuration sync with config_split for environment-specific configs
5. **Redis caching**: Module installed for performance optimization

The project follows Drupal's standard architecture with:
- MVC pattern through Drupal's routing and controller system
- Service container for dependency injection
- Event-driven architecture using Symfony components
- Entity API for data modeling
- Plugin system for extensible functionality

## Important Notes

- **⚠️ NOTE**: If you have a production environment configured, run `ddev sync-prod` BEFORE any development work (see "PROD → DEV Synchronization" section above)
- Always use DDEV commands (`ddev composer`, `ddev drush`) instead of local versions
- The web root is `web/` not the project root
- Custom code goes in `web/modules/custom/` and `web/themes/custom/`
- Configuration changes should be exported using `ddev drush cex`
- Database updates should be run after code deployments with `ddev drush updatedb`
- Clear caches frequently during development with `ddev drush cr`
- PROD is the source of truth for configuration - never manually edit config files that exist in prod

## Deployment Workflow (Reference - adapt to your production environment)

**NOTE: This section is for reference. Adapt the commands to your production environment if/when you set one up.**

### ÉTAPE 0 : Synchroniser PROD vers DEV (OBLIGATOIRE)

**À faire AVANT toute modification en dev** :

```bash
# Méthode complète (config + BDD) - RECOMMANDÉE
./scripts/sync-prod-to-dev.sh

# Méthode rapide (config uniquement, sans BDD)
./scripts/sync-prod-to-dev.sh --skip-db

# Ou avec commande DDEV (une fois DDEV redémarré)
ddev sync-prod
```

**Ce que fait le script (mode complet)** :
1. Exporte la configuration de PROD
2. Commit et push depuis PROD
3. Pull les changements en DEV
4. Importe la configuration en DEV
5. **Exporte la base de données PROD** (~2.4 MB compressé)
6. **Importe la base de données en DEV**
7. Nettoie les fichiers temporaires
8. Vide le cache

**Résultat** : DEV est maintenant un **clone exact** de PROD (code + config + données). Vous pouvez travailler en sécurité.

**Quand utiliser --skip-db ?**
- Si vous avez déjà synchronisé la BDD récemment
- Si vous voulez juste récupérer des changements de config rapides
- Pour gagner du temps (skip-db prend ~10 secondes vs ~2 minutes pour le mode complet)

---

### Installing a New Module

#### Step 1: Synchroniser PROD → DEV
```bash
ddev sync-prod
```

#### Step 2: Development (Local with DDEV)
```bash
# 1. Install the module
ddev composer require 'drupal/<module_name>:<version>'

# 2. Enable the module
ddev drush en <module_name> -y

# 3. Export configuration
ddev drush cex -y

# 4. Commit and push
git add composer.json composer.lock config/
git commit -m "feat: ajout module <module_name>"
git push origin main
```

#### Step 3: Production Deployment (example - adapt to your environment)
```bash
# Example for a production server - adapt to your setup
# ssh user@production-server "cd /var/www/esokami-drp && \
#   git pull origin main && \
#   docker compose -f docker-compose.prod.yml exec -T php composer require 'drupal/<module_name>:<version>' --update-no-dev --optimize-autoloader && \
#   docker compose -f docker-compose.prod.yml exec -T php vendor/bin/drush cim -y && \
#   docker compose -f docker-compose.prod.yml exec -T php vendor/bin/drush cr"
```

**Pourquoi cette approche ?**
- ✅ PROD et DEV sont toujours synchronisés
- ✅ Pas de perte de configuration faite directement en PROD
- ✅ Workflow sûr et prévisible

### Updating Modules

Same workflow - **TOUJOURS commencer par la synchronisation** :
```bash
# 1. SYNC PROD → DEV
ddev sync-prod

# 2. DEV
ddev composer update drupal/<module_name> --with-dependencies
ddev drush updatedb -y
ddev drush cex -y
git add composer.json composer.lock config/
git commit -m "chore: mise à jour module <module_name>"
git push origin main

# 3. PROD (example - adapt to your environment)
# ssh user@production-server "cd /var/www/esokami-drp && \
#   git pull origin main && \
#   docker compose -f docker-compose.prod.yml exec -T php composer update drupal/<module_name> --with-dependencies --no-dev --optimize-autoloader && \
#   docker compose -f docker-compose.prod.yml exec -T php vendor/bin/drush updatedb -y && \
#   docker compose -f docker-compose.prod.yml exec -T php vendor/bin/drush cim -y && \
#   docker compose -f docker-compose.prod.yml exec -T php vendor/bin/drush cr"
```

### Configuration Changes Only

Si vous faites des changements de configuration directement en PROD via l'interface Drupal :

```bash
# 1. Synchroniser vers DEV pour récupérer les changements
ddev sync-prod

# 2. Si besoin de modifier en DEV, faire les changements puis :
ddev drush cex -y
git add config/
git commit -m "config: description du changement"
git push origin main

# 3. PROD (example - adapt to your environment)
# ssh user@production-server "cd /var/www/esokami-drp && \
#   git pull origin main && \
#   docker compose -f docker-compose.prod.yml exec php vendor/bin/drush cim -y && \
#   docker compose -f docker-compose.prod.yml exec php vendor/bin/drush cr"
```

### Why This Workflow?

1. **PROD = Source de vérité** : Les changements faits en prod ne sont jamais perdus
2. **Sync automatique** : Un script simple (`ddev sync-prod`) synchronise tout
3. **Git history** : Traceabilité complète de tous les changements
4. **Easy rollback** : `git revert` fonctionne sans perte de données
5. **Pas de surprises** : DEV reflète toujours l'état exact de PROD

### Why Always Export Configuration?

**TOUJOURS exporter la configuration avec `drush cex`** même si le module semble simple :

**Raisons** :
- ✅ Certains modules créent de la configuration par défaut (settings, permissions, views, etc.)
- ✅ Garantit que dev et prod sont identiques
- ✅ Permet le rollback complet (code + config) avec `git revert`
- ✅ Historique complet des changements dans Git
- ✅ Code review possible sur la configuration

**Exemple** :
```yaml
# config/sync/pathauto.settings.yml (créé automatiquement)
enabled: true
separator: '-'
max_length: 100
```

Sans l'export, ces settings pourraient être différents entre dev et prod !

**Exception** : Tests rapides en dev uniquement. Mais pour prod → **toujours avec config**.

### Production Server Details (example - update when configured)

- **Host**: (to be configured)
- **Path**: /var/www/esokami-drp
- **Docker Compose file**: docker-compose.prod.yml
- **Container**: (to be configured)
- **Important**: Composer and Drush typically run INSIDE the PHP container

## Troubleshooting Production Deployments

### Problème: Nouveaux modules non installés après `composer install`

**Symptôme**:
```bash
Installing dependencies from lock file
Nothing to install, update or remove
```

Puis `drush cim` échoue avec "Impossible d'installer le module car il n'existe pas"

**Cause**: `composer install` compare le `vendor/` existant avec `composer.lock` et ne détecte pas toujours les nouveaux packages, même s'ils sont dans le lock file. C'est une limitation de Composer quand vendor/ existe déjà.

**Solution définitive** (testée et validée):
```bash
# Utiliser composer require au lieu de composer install
docker compose -f docker-compose.prod.yml exec -T php composer require 'drupal/<module_name>:<version>' --update-no-dev --optimize-autoloader
```

**Pourquoi ça marche ?**
- `composer require` force l'installation du package
- Met à jour composer.lock automatiquement
- Installe uniquement le nouveau module + dépendances (rapide)
- Pas de problème de détection de nouveaux packages

### Problème: Erreur "composer.json is not writable" en prod

**Cause**: Les volumes sont montés en lecture seule (`:ro`) dans docker-compose.prod.yml

**Solution**: Les `:ro` ont été retirés des volumes suivants dans docker-compose.prod.yml:
- `./config:/var/www/html/config`
- `./composer.json:/var/www/html/composer.json`
- `./composer.lock:/var/www/html/composer.lock`

### Problème: Erreurs de permissions Git en prod

**Symptôme**: `fatal: detected dubious ownership` ou `Permission denied`

**Solution**: Corriger les permissions avec:
```bash
# Example - adapt to your production environment
# ssh user@production-server "cd /var/www/esokami-drp && sudo chown -R user:user ."
```

### Problème: Erreurs de téléchargement de traductions FR

**Symptôme**: `Unable to download translation file` lors de `drush cim`

**Impact**: Mineur - le module fonctionne sans traductions

**Solution**: Ignorer ou installer manuellement les traductions plus tard via l'interface Drupal