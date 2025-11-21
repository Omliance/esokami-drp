#!/bin/bash
# Script de backup pour Drupal 11 en production
# Sauvegarde la base de données et les fichiers
# Usage: Exécuté automatiquement par le service backup ou manuellement
#        docker compose -f docker-compose.prod.yml run --rm backup

set -e

# Configuration
BACKUP_DIR="/backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=${BACKUP_RETENTION_DAYS:-30}

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

log_step "Backup Drupal 11 Production"
echo ""
echo "Date : $(date)"
echo "Rétention : ${RETENTION_DAYS} jours"
echo ""

# Créer le répertoire de backup s'il n'existe pas
mkdir -p "${BACKUP_DIR}"

# Step 1: Backup de la base de données
log_step "Étape 1/4 : Backup de la base de données"
DB_FILE="${BACKUP_DIR}/db_${TIMESTAMP}.sql.gz"

log_info "Export de la base de données..."
mysqldump \
    -h "${DB_HOST}" \
    -u root \
    -p"${DB_ROOT_PASSWORD}" \
    --single-transaction \
    --quick \
    --lock-tables=false \
    "${DB_NAME}" | gzip > "${DB_FILE}"

DB_SIZE=$(du -h "${DB_FILE}" | cut -f1)
log_info "Base de données sauvegardée : ${DB_FILE} (${DB_SIZE})"

# Step 2: Backup des fichiers publics
log_step "Étape 2/4 : Backup des fichiers publics"
FILES_PUBLIC="/var/www/html/web/sites/default/files"
if [ -d "${FILES_PUBLIC}" ] && [ "$(ls -A ${FILES_PUBLIC})" ]; then
    PUBLIC_FILE="${BACKUP_DIR}/files_public_${TIMESTAMP}.tar.gz"
    log_info "Compression des fichiers publics..."
    tar -czf "${PUBLIC_FILE}" -C "$(dirname ${FILES_PUBLIC})" "$(basename ${FILES_PUBLIC})"
    PUBLIC_SIZE=$(du -h "${PUBLIC_FILE}" | cut -f1)
    log_info "Fichiers publics sauvegardés : ${PUBLIC_FILE} (${PUBLIC_SIZE})"
else
    log_warning "Pas de fichiers publics à sauvegarder"
fi

# Step 3: Backup des fichiers privés
log_step "Étape 3/4 : Backup des fichiers privés"
FILES_PRIVATE="/var/www/private"
if [ -d "${FILES_PRIVATE}" ] && [ "$(ls -A ${FILES_PRIVATE})" ]; then
    PRIVATE_FILE="${BACKUP_DIR}/files_private_${TIMESTAMP}.tar.gz"
    log_info "Compression des fichiers privés..."
    tar -czf "${PRIVATE_FILE}" -C "$(dirname ${FILES_PRIVATE})" "$(basename ${FILES_PRIVATE})"
    PRIVATE_SIZE=$(du -h "${PRIVATE_FILE}" | cut -f1)
    log_info "Fichiers privés sauvegardés : ${PRIVATE_FILE} (${PRIVATE_SIZE})"
else
    log_warning "Pas de fichiers privés à sauvegarder"
fi

# Step 4: Nettoyage des anciens backups
log_step "Étape 4/4 : Nettoyage des anciens backups"
log_info "Suppression des backups de plus de ${RETENTION_DAYS} jours..."
DELETED_COUNT=$(find "${BACKUP_DIR}" -type f -name "*.gz" -mtime +${RETENTION_DAYS} -delete -print | wc -l)
if [ ${DELETED_COUNT} -gt 0 ]; then
    log_info "✓ ${DELETED_COUNT} anciens backups supprimés"
else
    log_info "Aucun ancien backup à supprimer"
fi

# Créer un fichier de métadonnées
META_FILE="${BACKUP_DIR}/backup_${TIMESTAMP}.info"
cat > "${META_FILE}" <<EOF
Backup créé le : $(date)
Base de données : ${DB_FILE} (${DB_SIZE})
Fichiers publics : ${PUBLIC_FILE:-N/A} (${PUBLIC_SIZE:-N/A})
Fichiers privés : ${PRIVATE_FILE:-N/A} (${PRIVATE_SIZE:-N/A})
Base : ${DB_NAME}
Host : ${DB_HOST}
EOF

log_info "Métadonnées : ${META_FILE}"

# Résumé
echo ""
log_step "✓ Backup terminé !"
echo ""
log_info "Fichiers créés :"
echo "  - Base de données : ${DB_FILE}"
[ -n "${PUBLIC_FILE}" ] && echo "  - Fichiers publics : ${PUBLIC_FILE}"
[ -n "${PRIVATE_FILE}" ] && echo "  - Fichiers privés : ${PRIVATE_FILE}"
echo "  - Métadonnées : ${META_FILE}"
echo ""
log_info "Espace disque utilisé :"
df -h "${BACKUP_DIR}" | tail -1
echo ""
log_warning "Pour restaurer un backup :"
echo "  - Base de données : gunzip < ${DB_FILE} | docker compose -f docker-compose.prod.yml exec -T mariadb mysql -u root -p\${DB_ROOT_PASSWORD} \${DB_NAME}"
echo "  - Fichiers publics : tar -xzf ${PUBLIC_FILE} -C /path/to/restore"
echo "  - Fichiers privés : tar -xzf ${PRIVATE_FILE} -C /path/to/restore"
echo ""

exit 0
