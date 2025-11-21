#!/bin/bash
# Script de mise à jour pour la production
# Récupère les dernières modifications depuis GitHub et met à jour Drupal
# Usage: ./scripts/update.sh

set -e

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log_info() {
    echo -e "${GREEN}[✓]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

log_step() {
    echo -e "\n${BLUE}==>${NC} $1"
}

log_step "Mise à jour de la production"
echo ""

cd "${PROJECT_DIR}"

# Step 1: Backup avant mise à jour
log_step "Étape 1/8 : Backup avant mise à jour"
if [ -f "${SCRIPT_DIR}/backup.sh" ]; then
    log_info "Création d'un backup de sécurité..."
    docker compose -f docker-compose.prod.yml run --rm backup
    log_info "Backup créé"
else
    log_warning "Script de backup non trouvé, continuation sans backup"
fi

# Step 2: Récupération des modifications depuis GitHub
log_step "Étape 2/8 : Récupération des modifications depuis GitHub"
log_info "Git pull..."
git pull origin main
log_info "Code mis à jour"

# Step 3: Mise à jour des dépendances Composer
log_step "Étape 3/8 : Mise à jour des dépendances Composer"
if [ -f "composer.lock" ]; then
    log_info "Mise à jour des dépendances..."
    docker run --rm -v "${PROJECT_DIR}:/app" -w /app composer:2 install --no-dev --optimize-autoloader --no-interaction
    log_info "Dépendances mises à jour"
else
    log_warning "composer.lock non trouvé, skip"
fi

# Step 4: Rebuild de l'image Docker si le Dockerfile a changé
log_step "Étape 4/8 : Rebuild de l'image Docker si nécessaire"
if git diff --name-only HEAD@{1} HEAD | grep -q "Dockerfile\|docker/"; then
    log_info "Dockerfile modifié, rebuild..."
    docker compose -f docker-compose.prod.yml build php
    log_info "Image reconstruite"
else
    log_info "Pas de modification du Dockerfile, skip rebuild"
fi

# Step 5: Redémarrage des services
log_step "Étape 5/8 : Redémarrage des services"
docker compose -f docker-compose.prod.yml up -d
log_info "Services redémarrés"
sleep 10

# Step 6: Import de la configuration Drupal
log_step "Étape 6/8 : Import de la configuration Drupal"
log_info "Import de la configuration..."
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush config:import -y || {
    log_warning "Échec de l'import de configuration (peut être normal si pas de changements)"
}

# Step 7: Mise à jour de la base de données
log_step "Étape 7/8 : Mise à jour de la base de données"
log_info "Mise à jour de la base de données..."
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush updatedb -y

# Step 8: Clear cache
log_step "Étape 8/8 : Vidage du cache"
log_info "Vidage du cache..."
docker compose -f docker-compose.prod.yml exec php vendor/bin/drush cache:rebuild

# Résumé
echo ""
log_step "✓ Mise à jour terminée !"
echo ""
log_info "Status des services :"
docker compose -f docker-compose.prod.yml ps
echo ""
log_warning "Vérifiez que le site fonctionne correctement"
echo ""

exit 0
