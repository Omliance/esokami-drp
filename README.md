# Esokami DRP - Site Web Drupal 11

Site web Drupal 11 pour Esokami DRP construit avec DDEV.

## 📋 Table des matières

- [Prérequis](#prérequis)
- [Installation initiale](#installation-initiale)
- [Démarrage rapide](#démarrage-rapide)
- [Commandes essentielles](#commandes-essentielles)
- [Workflow de développement](#workflow-de-développement)
- [Structure du projet](#structure-du-projet)
- [Dépannage](#dépannage)

---

## 🔧 Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre machine :

### Logiciels requis

| Logiciel | Version | Installation |
|----------|---------|--------------|
| **Docker Desktop** | Latest | [docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop/) |
| **DDEV** | v1.24+ | [ddev.readthedocs.io/en/stable/users/install](https://ddev.readthedocs.io/en/stable/users/install/) |
| **Git** | 2.x+ | `sudo apt install git` (Linux) |
| **Composer** | 2.x+ | [getcomposer.org/download](https://getcomposer.org/download/) |
| **mkcert** | Latest | Voir ci-dessous |

### Installation de mkcert (HTTPS local sans avertissement)

**macOS :**
```bash
brew install mkcert nss
mkcert -install
```

**Linux (Ubuntu/Debian) :**
```bash
sudo apt install libnss3-tools
wget https://github.com/FiloSottile/mkcert/releases/latest/download/mkcert-v1.4.4-linux-amd64
chmod +x mkcert-v1.4.4-linux-amd64
sudo mv mkcert-v1.4.4-linux-amd64 /usr/local/bin/mkcert
mkcert -install
```

**Windows :**
```powershell
choco install mkcert
mkcert -install
```

### Vérification des installations

```bash
docker --version          # Docker version 24.0+
ddev version             # DDEV version v1.24+
git --version            # git version 2.x+
composer --version       # Composer version 2.x+
mkcert -CAROOT          # Affiche le dossier des certificats
```

---

## 🚀 Installation initiale

### 1. Cloner le dépôt

```bash
# Via SSH (recommandé)
git clone git@github.com:Omliance/esokami-drp.git
cd esokami-drp

# Ou via HTTPS
git clone https://github.com/Omliance/esokami-drp.git
cd esokami-drp
```

### 2. Démarrer DDEV

```bash
# Démarrer l'environnement Docker
ddev start
```

**Attendez quelques minutes lors du premier lancement** (téléchargement des images Docker).

### 3. Installer les dépendances

```bash
# Installation via Composer
ddev composer install
```

### 4. Configuration obligatoire

**⚠️ IMPORTANT :** Le fichier `settings.php` est généré automatiquement par DDEV, mais vous devez ajouter la configuration du répertoire de sync.

Ajoutez cette ligne dans `web/sites/default/settings.php` (juste **avant** la ligne `// Automatically generated include for settings managed by ddev.`) :

```php
// Configuration sync directory (required for config import/export)
$settings['config_sync_directory'] = '../config/sync';
```

**Emplacement exact :** Entre les commentaires migration et l'inclusion DDEV (vers la ligne 878).

### 5. Installer Drupal puis importer la configuration

**⚠️ NOTE :** Vous devez d'abord installer Drupal avec le profil standard, puis importer la configuration (le profil standard ne supporte pas `--existing-config`).

```bash
# 1. Installer Drupal avec le profil standard
ddev drush site:install standard --account-name=admin --account-pass=admin --site-name="Esokami DRP" -y

# 2. Corriger l'UUID du site pour correspondre à la configuration
ddev drush config:set system.site uuid 5ae1abc7-a2a1-4d33-bb57-b05691a89e1b -y

# 3. Supprimer les raccourcis par défaut qui bloquent l'import
ddev drush entity:delete shortcut_set default -y

# 4. Importer toute la configuration du site
ddev drush config:import -y

# 5. Mettre à jour la base de données si nécessaire
ddev drush updatedb -y

# 6. Appliquer les traductions françaises
# ⚠️ IMPORTANT : Lancer locale:update après activation du français pour importer les traductions
ddev drush locale:update

# 7. Vider le cache
ddev drush cache:rebuild
```

### 6. Accéder au site

```bash
# Ouvrir le site dans votre navigateur
ddev launch
```

**URL locale :** https://esokami-drp.ddev.site

### 7. Connexion administrateur

```bash
# Générer un lien de connexion unique (valable 24h)
ddev drush user:login

# Ou avec l'alias court
ddev drush uli
```

**Identifiants par défaut :**
- Username : `admin`
- Password : `admin`

---

## 🚀 Script d'installation automatique (optionnel)

Pour simplifier l'installation, vous pouvez créer un script qui effectue toutes les étapes automatiquement :

```bash
# Créer le fichier install.sh à la racine du projet
cat > install.sh << 'EOF'
#!/bin/bash
set -e

echo "🚀 Installation de Esokami DRP..."

# 1. Démarrer DDEV
echo "📦 Démarrage de DDEV..."
ddev start

# 2. Installer les dépendances
echo "📥 Installation des dépendances Composer..."
ddev composer install

# 3. Ajouter la config sync si elle n'existe pas
echo "⚙️  Configuration du répertoire de sync..."
if ! grep -q "config_sync_directory" web/sites/default/settings.php; then
    sed -i '/\/\/ Automatically generated include for settings managed by ddev./i // Configuration sync directory (required for config import/export)\n$settings['\''config_sync_directory'\''] = '\''../config/sync'\'';\n' web/sites/default/settings.php
fi

# 4. Installer Drupal
echo "🔨 Installation de Drupal..."
ddev drush site:install standard --account-name=admin --account-pass=admin --site-name="Esokami DRP" -y

# 5. Corriger l'UUID
echo "🔑 Correction de l'UUID du site..."
ddev drush config:set system.site uuid 5ae1abc7-a2a1-4d33-bb57-b05691a89e1b -y

# 6. Supprimer les raccourcis par défaut
echo "🗑️  Suppression des raccourcis par défaut..."
ddev drush entity:delete shortcut_set default -y

# 7. Importer la configuration
echo "📋 Import de la configuration Drupal..."
ddev drush config:import -y

# 8. Mettre à jour la base de données
echo "🔄 Mise à jour de la base de données..."
ddev drush updatedb -y

# 9. Appliquer les traductions françaises
echo "🌍 Application des traductions françaises..."
ddev drush locale:update

# 10. Vider le cache
echo "🧹 Vidage du cache..."
ddev drush cache:rebuild

# 11. Générer le lien de connexion
echo ""
echo "✅ Installation terminée !"
echo ""
echo "🌐 URL du site : https://esokami-drp.ddev.site"
echo "🔐 Lien de connexion admin :"
ddev drush uli

EOF

# Rendre le script exécutable
chmod +x install.sh

# Lancer l'installation
./install.sh
```

**Utilisation du script :**
```bash
# Après avoir cloné le dépôt, lancez simplement :
bash install.sh
```

---

## ⚡ Démarrage rapide (jours suivants)

Une fois l'installation initiale terminée, utilisez ces commandes :

```bash
# 1. Se placer dans le projet
cd ~/workspace/esokami-drp

# 2. Démarrer DDEV
ddev start

# 3. Ouvrir le site
ddev launch

# 4. Lien de connexion admin
ddev drush uli
```

**Pour arrêter le projet en fin de journée :**
```bash
ddev stop
```

---

## 📚 Commandes essentielles

### Gestion DDEV

```bash
# Démarrer le projet
ddev start

# Arrêter le projet (libère la RAM)
ddev stop

# Redémarrer (après changement de config)
ddev restart

# Voir les informations du projet
ddev describe

# Arrêter tous les projets DDEV
ddev poweroff

# Entrer dans le conteneur (ligne de commande)
ddev ssh

# Voir les logs en temps réel
ddev logs -f
```

### Drupal / Drush

```bash
# Vider le cache (après chaque modification)
ddev drush cr
# Alias : ddev drush cache:rebuild

# Exporter la configuration (après modifications dans l'admin)
ddev drush cex -y
# Alias : ddev drush config:export

# Importer la configuration (après un git pull)
ddev drush cim -y
# Alias : ddev drush config:import

# Mettre à jour la base de données
ddev drush updb -y
# Alias : ddev drush updatedb

# Lien de connexion admin
ddev drush uli
# Alias : ddev drush user:login

# Statut du site
ddev drush status

# Lister les modules activés
ddev drush pm:list --status=enabled
```

### Composer (gestion des dépendances)

```bash
# Installer un nouveau module
ddev composer require drupal/nom_module

# Mettre à jour un module
ddev composer update drupal/nom_module

# Mettre à jour tous les modules
ddev composer update

# Installer les dépendances (après git pull)
ddev composer install

# Retirer un module
ddev composer remove drupal/nom_module
```

### Base de données

```bash
# Exporter la base de données
ddev export-db --file=backups/db-$(date +%Y%m%d).sql.gz

# Ou version simple
ddev export-db > backup.sql

# Importer une base de données
ddev import-db --file=backup.sql.gz
ddev import-db < backup.sql

# Créer un snapshot (sauvegarde rapide)
ddev snapshot

# Restaurer le dernier snapshot
ddev snapshot restore

# Lister les snapshots disponibles
ddev snapshot list

# Accéder à phpMyAdmin (interface graphique)
ddev launch -p
```

### Git

```bash
# Voir le statut des fichiers
git status

# Voir les différences
git diff

# Récupérer les derniers changements
git pull origin main

# Après modifications : exporter config + commit + push
ddev drush cex -y
git add .
git commit -m "feat: description"
git push origin main
```

---

## 🔄 Workflow de développement

### ⚠️ IMPORTANT : Synchronisation PROD → DEV

**NOUVELLE APPROCHE : PROD est la source de vérité**

Si vous travaillez régulièrement sur la configuration en production (via l'interface Drupal), vous devez **TOUJOURS** synchroniser PROD vers DEV avant toute modification en développement.

#### Pourquoi ce workflow ?

Sans cette synchronisation, vous risquez de **perdre vos configurations production** lors du déploiement :
- Vous modifiez la config en prod (CSS, SMTP, settings, etc.)
- Vous travaillez en dev sans récupérer ces changements
- Lors du déploiement, `drush cim` écrase TOUT en prod avec la config de dev
- ❌ **Perte de vos modifications prod**

#### Solution : Script de synchronisation automatique

```bash
# Commande complète (RECOMMANDÉE) : Config + BDD
./scripts/sync-prod-to-dev.sh

# Commande rapide (config uniquement, sans BDD)
./scripts/sync-prod-to-dev.sh --skip-db

# Ou avec DDEV (une fois DDEV redémarré)
ddev sync-prod
```

**Ce que fait cette commande (mode complet) :**
1. ✅ Exporte la configuration depuis PROD
2. ✅ Commit et push depuis PROD vers Git
3. ✅ Pull les changements en DEV
4. ✅ Importe la configuration en DEV
5. ✅ **Exporte la base de données PROD** (~2.4 MB compressé)
6. ✅ **Importe la base de données en DEV**
7. ✅ Nettoie les fichiers temporaires
8. ✅ Vide le cache

**Résultat :** DEV est maintenant un **clone exact de PROD** (code + config + données). Vous pouvez travailler en sécurité sans risque de perte.

**Options :**
- **Mode complet** (par défaut) : Synchronise config + BDD (~2 minutes)
- **Mode --skip-db** : Synchronise config uniquement (~10 secondes)
  - Utile si vous avez déjà la BDD à jour
  - Pratique pour récupérer juste des changements de config

---

### Synchronisation quotidienne (MÉTHODE RECOMMANDÉE)

**Chaque matin avant de commencer :**

```bash
# 1. Synchroniser PROD → DEV (mode complet recommandé)
./scripts/sync-prod-to-dev.sh

# C'est tout ! Cette commande effectue automatiquement :
# - Export config depuis PROD
# - Export BDD depuis PROD
# - Pull des changements
# - Installation des dépendances
# - Import de la configuration
# - Import de la base de données
# - Vidage du cache

# Alternative rapide (sans BDD)
./scripts/sync-prod-to-dev.sh --skip-db
```

**Méthode alternative (manuelle) :**
```bash
# Si vous ne souhaitez pas utiliser ddev sync-prod
git pull origin main
ddev composer install
ddev drush config:import -y
ddev drush updatedb -y
ddev drush cache:rebuild
```

**Script automatique (ancienne méthode) :**
```bash
# Créer un alias dans ~/.bashrc ou ~/.zshrc
alias ddev-sync='git pull origin main && ddev composer install && ddev drush cim -y && ddev drush updb -y && ddev drush cr'

# Utilisation
ddev-sync
```

### Développer une nouvelle fonctionnalité

```bash
# 1. Créer une branche depuis develop
git checkout develop
git pull origin develop
git checkout -b feature/nom-fonctionnalite

# 2. Développer et tester localement
# ... modifications dans web/modules/custom ou via l'admin ...

# 3. Exporter la configuration Drupal
ddev drush config:export -y

# 4. Vérifier les fichiers modifiés
git status
git diff

# 5. Commit avec un message descriptif
git add .
git commit -m "feat: ajout formulaire de contact

- Créer le module custom contact_form
- Configurer le webform
- Ajouter la page de remerciement
- Tests unitaires ajoutés"

# 6. Push vers GitHub
git push -u origin feature/nom-fonctionnalite

# 7. Créer une Pull Request sur GitHub
# Aller sur https://github.com/Omliance/esokami-drp/pulls
# Cliquer sur "New Pull Request"
# Base: develop ← Compare: feature/nom-fonctionnalite
```

### Installer un nouveau module

```bash
# 0. TOUJOURS synchroniser PROD → DEV en premier (OBLIGATOIRE)
ddev sync-prod

# 1. Installer via Composer
ddev composer require drupal/module_name

# 2. Activer le module
ddev drush en module_name -y

# 3. Vider le cache
ddev drush cr

# 4. Configurer le module via l'interface admin
ddev launch

# 5. Exporter la configuration
ddev drush cex -y

# 6. Commit
git add composer.json composer.lock config/
git commit -m "feat: add module_name module"
git push origin main

# 7. Déployer en PROD (adapter selon votre environnement de production)
# ssh user@production-server "cd /var/www/esokami-drp && \
#   git pull origin main && \
#   docker compose -f docker-compose.prod.yml exec -T php composer require 'drupal/module_name' --update-no-dev --optimize-autoloader && \
#   docker compose -f docker-compose.prod.yml exec -T php vendor/bin/drush cim -y && \
#   docker compose -f docker-compose.prod.yml exec -T php vendor/bin/drush cr"
```

**Note importante :** L'étape 0 (`ddev sync-prod`) est **critique**. Si vous l'oubliez, vous risquez d'écraser des configurations faites directement en production.

### Conventions de messages de commit

Utilisez des préfixes clairs pour une meilleure traçabilité :

| Préfixe | Usage | Exemple |
|---------|-------|---------|
| `feat:` | Nouvelle fonctionnalité | `feat: add user registration form` |
| `fix:` | Correction de bug | `fix: resolve menu display on mobile` |
| `config:` | Modification configuration | `config: update site email settings` |
| `style:` | CSS/design | `style: improve homepage layout` |
| `refactor:` | Refactoring code | `refactor: reorganize custom module` |
| `docs:` | Documentation | `docs: update README installation` |
| `perf:` | Performance | `perf: enable Redis cache` |
| `test:` | Tests | `test: add unit tests for API` |

---

## 📁 Structure du projet

```
esokami-drp/
├── .ddev/
│   ├── config.yaml              # Configuration DDEV du projet
│   └── ...                      # Fichiers générés automatiquement
├── config/
│   └── sync/                    # Configuration Drupal (versionné)
│       ├── core.extension.yml
│       ├── system.site.yml
│       └── ...
├── private/                     # Fichiers privés (non accessibles web)
├── scripts/                     # Scripts personnalisés
│   └── sync-prod-to-dev.sh     # Synchronisation PROD → DEV
├── web/                         # Document root (accessible web)
│   ├── core/                    # Drupal core (géré par Composer)
│   ├── modules/
│   │   ├── contrib/            # Modules contrib (non versionné)
│   │   └── custom/             # Vos modules (versionné)
│   ├── themes/
│   │   ├── contrib/            # Thèmes contrib (non versionné)
│   │   └── custom/             # Vos thèmes (versionné)
│   ├── sites/
│   │   └── default/
│   │       ├── files/          # Uploads utilisateurs (non versionné)
│   │       └── settings.php    # Config DB (non versionné)
│   └── index.php
├── vendor/                      # Dépendances PHP (non versionné)
├── .gitignore
├── composer.json                # Dépendances du projet
├── composer.lock                # Versions exactes (versionné)
└── README.md
```

### Fichiers importants à connaître

| Fichier | Description |
|---------|-------------|
| `composer.json` | Liste des modules et dépendances |
| `composer.lock` | Versions exactes installées |
| `config/sync/*` | Configuration Drupal exportée |
| `.ddev/config.yaml` | Configuration de l'environnement local |
| `web/sites/default/settings.php` | Configuration base de données |
| `.gitignore` | Fichiers exclus du versionnement |

---

## 🛠️ Stack technique

### Environnement de développement

- **CMS** : Drupal 11.1
- **PHP** : 8.3
- **Serveur web** : Nginx-FPM
- **Base de données** : MariaDB 10.11
- **Node.js** : 20 (pour compilation assets)
- **Gestionnaire de paquets** : Composer 2.x
- **Environnement dev** : DDEV v1.24+
- **Containerisation** : Docker

### Modules Drupal installés

#### Core activés
- `language` - Gestion multilingue
- `locale` - Traduction interface
- `content_translation` - Traduction contenu
- `config_translation` - Traduction configuration

#### Contrib
- `admin_toolbar` - Barre d'administration améliorée
- `admin_toolbar_tools` - Outils supplémentaires admin
- `pathauto` - URLs automatiques
- `redirect` - Gestion redirections
- `metatag` - Méta-données SEO
- `webform` - Formulaires avancés
- `devel` - Outils de développement (dev only)

#### Custom
- Vos modules personnalisés dans `web/modules/custom/`

---

## 🐛 Dépannage

### Problèmes d'installation Drupal

**Problème : "Site UUID does not match" lors de l'import de configuration**

Ce problème survient lorsque l'UUID de la nouvelle installation ne correspond pas à celui de la configuration exportée.

```bash
# Solution : Appliquer l'UUID de la configuration au site
ddev drush config:set system.site uuid 5ae1abc7-a2a1-4d33-bb57-b05691a89e1b -y

# Puis relancer l'import
ddev drush config:import -y
```

**Problème : "Entities exist of type Shortcut" lors de l'import**

Les raccourcis créés par défaut lors de l'installation bloquent l'import de configuration.

```bash
# Solution : Supprimer les raccourcis par défaut
ddev drush entity:delete shortcut_set default -y

# Puis relancer l'import
ddev drush config:import -y
```

**Problème : "The selected profile has a hook_install() implementation"**

Le profil standard ne supporte pas l'installation avec `--existing-config`.

```bash
# Solution : Installer normalement puis importer la config
ddev drush site:install standard --account-name=admin --account-pass=admin -y

# Suivre ensuite les étapes 2 à 7 de la section "5. Installer Drupal puis importer la configuration"
```

**Problème : "Configuration directory not found"**

La configuration du répertoire de sync n'est pas définie dans `settings.php`.

```bash
# Vérifier si la configuration existe
grep "config_sync_directory" web/sites/default/settings.php

# Si absent, éditer web/sites/default/settings.php et ajouter AVANT la ligne DDEV :
# $settings['config_sync_directory'] = '../config/sync';
```

**Problème : "Command config:import was not found"**

Drupal n'est pas encore installé, la base de données n'est pas initialisée.

```bash
# Solution : Installer d'abord Drupal
ddev drush site:install standard --account-name=admin --account-pass=admin -y
```

### DDEV ne démarre pas

**Problème : "docker daemon not running"**
```bash
# Vérifier Docker
docker ps

# Si erreur, redémarrer Docker Desktop
# Ou sur Linux :
sudo systemctl restart docker

# Nettoyer et redémarrer DDEV
ddev poweroff
ddev start
```

**Problème : "port already in use"**
```bash
# Voir quel processus utilise le port 80/443
sudo lsof -i :80
sudo lsof -i :443

# Arrêter Apache/Nginx local si actif
sudo systemctl stop apache2
sudo systemctl stop nginx

# Ou changer les ports DDEV
ddev config --router-http-port=8080 --router-https-port=8443
ddev restart
```

### Erreur "Composer out of memory"

```bash
# Augmenter la mémoire PHP
echo "memory_limit = -1" >> .ddev/php/memory.ini
ddev restart

# Puis réessayer
ddev composer install
```

### Site inaccessible après git pull

```bash
# Synchronisation complète
ddev composer install
ddev drush config:import -y
ddev drush updatedb -y
ddev drush cache:rebuild

# Si problème persiste, reconstruire l'environnement
ddev restart
```

### Erreur de permissions sur les fichiers

```bash
# Réparer les permissions
ddev exec chmod -R 755 web/sites/default/files
ddev exec chown -R www-data:www-data web/sites/default/files
```

### Base de données corrompue

```bash
# Restaurer le dernier snapshot
ddev snapshot restore

# Ou réimporter une sauvegarde
ddev import-db --file=backup.sql.gz
ddev drush cr
```

### HTTPS avec avertissement de sécurité

```bash
# Réinstaller les certificats mkcert
mkcert -uninstall
mkcert -install

# Supprimer les anciens certificats DDEV
rm -rf ~/.ddev/traefik/certs/*

# Redémarrer DDEV
ddev restart

# Fermer COMPLÈTEMENT le navigateur
# Puis rouvrir et tester
```

### Effacer complètement et recommencer

```bash
# ⚠️ ATTENTION : Cela supprime tout (base de données incluse)
ddev delete -O

# Puis recommencer l'installation
ddev start
ddev composer install
ddev drush config:import -y
```

---

## 📞 Support et ressources

### Documentation officielle

- **Drupal** : [drupal.org/docs](https://www.drupal.org/docs)
- **DDEV** : [ddev.readthedocs.io](https://ddev.readthedocs.io/)
- **Drush** : [drush.org](https://www.drush.org/)

### Commandes d'aide

```bash
# Aide DDEV
ddev help
ddev help start

# Aide Drush
ddev drush help
ddev drush help config:import

# Documentation d'un module
ddev drush pm:list --status=enabled
ddev drush pm:info module_name
```

### Contacts équipe

- **Dépôt GitHub** : [github.com/Omliance/esokami-drp](https://github.com/Omliance/esokami-drp)
- **Issues/Bugs** : [Issues GitHub](https://github.com/Omliance/esokami-drp/issues)
- **Pull Requests** : [Pull Requests GitHub](https://github.com/Omliance/esokami-drp/pulls)

---

## 📝 Checklist développeur

### Installation initiale ✓

- [ ] Docker Desktop installé et démarré
- [ ] DDEV installé (v1.24+)
- [ ] mkcert installé et certificat CA configuré
- [ ] Dépôt cloné depuis GitHub
- [ ] `ddev start` réussi
- [ ] `ddev composer install` réussi
- [ ] Configuration importée (`ddev drush cim -y`)
- [ ] Site accessible via https://esokami-drp.ddev.site
- [ ] Connexion admin fonctionnelle (`ddev drush uli`)

### Chaque jour ✓

- [ ] **`./scripts/sync-prod-to-dev.sh`** pour synchroniser PROD → DEV (OBLIGATOIRE)
- [ ] Vérifier que la synchronisation s'est bien passée (config + BDD)
- [ ] Commencer à travailler sur le développement

**OU version rapide (config uniquement) :**
- [ ] **`./scripts/sync-prod-to-dev.sh --skip-db`** pour synchroniser uniquement la config
- [ ] Utile si vous avez déjà la BDD à jour

**OU version manuelle :**
- [ ] `git pull origin main` pour récupérer les changements
- [ ] `ddev composer install` si composer.lock a changé
- [ ] `ddev drush cim -y` pour importer la nouvelle config
- [ ] `ddev drush updb -y` pour les mises à jour DB
- [ ] `ddev drush cr` pour vider le cache

### Avant chaque commit ✓

- [ ] `ddev drush cex -y` pour exporter la configuration
- [ ] `git status` pour vérifier les fichiers modifiés
- [ ] Tests fonctionnels effectués
- [ ] Message de commit descriptif avec préfixe
- [ ] Push vers une branche feature (pas directement sur main)

---

## 🎯 Objectifs du projet

Ce site Drupal 11 vise à fournir :

- Un site corporate moderne et performant
- Une interface d'administration intuitive en français
- Une architecture modulaire et maintenable
- Un workflow de développement standardisé avec DDEV
- Un déploiement automatisé vers la production

---

## 📄 Licence

Propriétaire - Tous droits réservés © Esokami 2025

---

**Dernière mise à jour :** Septembre 2025  
**Version Drupal :** 11.1  
**Version DDEV :** 1.24.8