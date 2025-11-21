#!/bin/bash
# Script d'export de la base de données locale (DDEV)
# Pour préparer une migration vers la production
# Usage: ./scripts/export-local.sh

set -e

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
MIGRATION_DIR="${PROJECT_DIR}/migration"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

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

log_step "Export de la base de données locale pour migration"
echo ""

# Vérifier qu'on est dans un environnement DDEV
cd "${PROJECT_DIR}"
if ! ddev describe &> /dev/null; then
    echo "Erreur : Ce script doit être exécuté dans un projet DDEV"
    exit 1
fi

# Créer le répertoire de migration
mkdir -p "${MIGRATION_DIR}"

# Step 1: Export de la base de données
log_step "Étape 1/3 : Export de la base de données"
log_info "Export depuis DDEV..."
ddev export-db --gzip --file="${MIGRATION_DIR}/db.sql.gz"
log_info "Base de données exportée : ${MIGRATION_DIR}/db.sql.gz"

# Step 2: Vérifier la configuration
log_step "Étape 2/3 : Vérification de la configuration"
if [ -d "${PROJECT_DIR}/config/sync" ] && [ "$(ls -A ${PROJECT_DIR}/config/sync/*.yml 2>/dev/null)" ]; then
    CONFIG_COUNT=$(ls -1 ${PROJECT_DIR}/config/sync/*.yml | wc -l)
    log_info "Configuration Drupal : ${CONFIG_COUNT} fichiers dans config/sync/"
else
    log_warning "Aucune configuration trouvée dans config/sync/"
    log_warning "Pensez à exporter la configuration avec : ddev drush cex"
fi

# Step 3: Créer un fichier d'information
log_step "Étape 3/3 : Création du fichier d'information"
cat > "${MIGRATION_DIR}/migration.info" <<EOF
Migration créée le : $(date)
Base de données : ${MIGRATION_DIR}/db.sql.gz
Taille : $(du -h ${MIGRATION_DIR}/db.sql.gz | cut -f1)
Configuration Drupal : ${PROJECT_DIR}/config/sync/
Drupal version : $(ddev drush status --field=drupal-version)
EOF

log_info "Fichier d'information créé : ${MIGRATION_DIR}/migration.info"

# Résumé
echo ""
log_step "✓ Export terminé !"
echo ""
log_info "Fichiers de migration prêts :"
echo "  - Base de données : ${MIGRATION_DIR}/db.sql.gz"
echo "  - Configuration   : ${PROJECT_DIR}/config/sync/"
echo "  - Info            : ${MIGRATION_DIR}/migration.info"
echo ""
log_warning "Prochaines étapes :"
echo "  1. Commit et push vers GitHub (incluant config/sync/)"
echo "  2. Sur le serveur de production :"
echo "     cd /var/www"
echo "     git clone git@github.com:Omliance/omliance-digital.git"
echo "     cd omliance-digital"
echo "     ./scripts/deploy.sh"
echo ""

exit 0
