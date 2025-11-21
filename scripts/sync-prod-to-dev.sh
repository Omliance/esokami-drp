#!/bin/bash
set -e

# Paramètres
SKIP_DB=false
if [ "$1" = "--skip-db" ]; then
  SKIP_DB=true
fi

echo "🔄 Synchronisation PROD → DEV"
echo "================================"
if [ "$SKIP_DB" = true ]; then
  echo "Mode: Configuration uniquement (--skip-db)"
else
  echo "Mode: Configuration + Base de données"
fi
echo ""

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROD_HOST="debian@91.134.242.189"
PROD_PATH="/var/www/esokami-drp"
PROD_COMPOSE="docker-compose.prod.yml"
TMP_DB_FILE="/tmp/prod-db-sync-$(date +%Y%m%d-%H%M%S).sql.gz"

TOTAL_STEPS=6
if [ "$SKIP_DB" = false ]; then
  TOTAL_STEPS=8
fi

echo -e "${YELLOW}Étape 1/$TOTAL_STEPS: Synchronisation Git en PROD${NC}"
ssh $PROD_HOST "cd $PROD_PATH && git pull origin main"

if [ $? -ne 0 ]; then
  echo -e "${RED}❌ Erreur lors du pull en prod${NC}"
  exit 1
fi

echo ""
echo -e "${YELLOW}Étape 2/$TOTAL_STEPS: Export de la configuration PROD${NC}"
ssh $PROD_HOST "cd $PROD_PATH && \
  docker compose -f $PROD_COMPOSE exec -T php vendor/bin/drush cex -y"

if [ $? -ne 0 ]; then
  echo -e "${RED}❌ Erreur lors de l'export de la config prod${NC}"
  exit 1
fi

echo ""
echo -e "${YELLOW}Étape 3/$TOTAL_STEPS: Commit de la config PROD${NC}"
ssh $PROD_HOST "cd $PROD_PATH && \
  git add config/ && \
  git diff --staged --quiet || git commit -m 'config: export prod avant sync' || true"

if [ $? -ne 0 ]; then
  echo -e "${RED}❌ Erreur lors du commit en prod${NC}"
  exit 1
fi

echo ""
echo -e "${YELLOW}Étape 4/$TOTAL_STEPS: Push des changements PROD${NC}"
ssh $PROD_HOST "cd $PROD_PATH && git push origin main"

if [ $? -ne 0 ]; then
  echo -e "${RED}❌ Erreur lors du push depuis prod${NC}"
  exit 1
fi

echo ""
echo -e "${YELLOW}Étape 5/$TOTAL_STEPS: Pull des changements en DEV${NC}"
git pull origin main

if [ $? -ne 0 ]; then
  echo -e "${RED}❌ Erreur lors du pull en dev${NC}"
  exit 1
fi

echo ""
echo -e "${YELLOW}Étape 6/$TOTAL_STEPS: Import de la config en DEV${NC}"
ddev drush cim -y

if [ $? -ne 0 ]; then
  echo -e "${RED}❌ Erreur lors de l'import en dev${NC}"
  exit 1
fi

# Synchronisation de la base de données (optionnelle)
if [ "$SKIP_DB" = false ]; then
  echo ""
  echo -e "${YELLOW}Étape 7/$TOTAL_STEPS: Export de la base de données PROD${NC}"
  echo -e "${BLUE}ℹ️  Export en cours... (peut prendre quelques minutes)${NC}"

  ssh $PROD_HOST "cd $PROD_PATH && \
    docker compose -f $PROD_COMPOSE exec -T php vendor/bin/drush sql:dump \
    --extra-dump='--no-tablespaces --skip-ssl' --gzip" > "$TMP_DB_FILE"

  if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erreur lors de l'export de la BDD prod${NC}"
    exit 1
  fi

  DB_SIZE=$(du -h "$TMP_DB_FILE" | cut -f1)
  echo -e "${BLUE}✓ Base de données exportée ($DB_SIZE)${NC}"

  echo ""
  echo -e "${YELLOW}Étape 8/$TOTAL_STEPS: Import de la base de données en DEV${NC}"
  echo -e "${BLUE}ℹ️  Import en cours...${NC}"

  ddev import-db --file="$TMP_DB_FILE"

  if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erreur lors de l'import de la BDD en dev${NC}"
    rm -f "$TMP_DB_FILE"
    exit 1
  fi

  # Nettoyage du fichier temporaire
  rm -f "$TMP_DB_FILE"
  echo -e "${BLUE}✓ Fichier temporaire supprimé${NC}"

  # Rebuild cache après import BDD
  echo -e "${BLUE}ℹ️  Vidage du cache...${NC}"
  ddev drush cr
fi

echo ""
echo -e "${GREEN}✅ Synchronisation terminée avec succès !${NC}"
echo ""
if [ "$SKIP_DB" = false ]; then
  echo "✓ Configuration synchronisée"
  echo "✓ Base de données synchronisée"
  echo ""
  echo "DEV est maintenant un clone exact de PROD."
else
  echo "✓ Configuration synchronisée"
  echo ""
  echo "DEV est maintenant synchronisé avec PROD (config uniquement)."
fi
echo "Vous pouvez travailler en toute sécurité."
