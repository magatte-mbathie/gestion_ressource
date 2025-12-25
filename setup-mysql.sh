#!/bin/bash

# Script pour installer et configurer MySQL sur macOS

echo "🔧 Configuration de MySQL pour le système de gestion de cours"
echo ""

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Vérifier si Homebrew est installé
if ! command -v brew &> /dev/null; then
    echo -e "${YELLOW}⚠️  Homebrew n'est pas installé${NC}"
    echo "Installation de Homebrew..."
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
    
    # Ajouter Homebrew au PATH
    if [[ -f "/opt/homebrew/bin/brew" ]]; then
        echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zshrc
        eval "$(/opt/homebrew/bin/brew shellenv)"
    fi
fi

# Vérifier si MySQL est installé
if ! command -v mysql &> /dev/null; then
    echo -e "${YELLOW}📦 Installation de MySQL...${NC}"
    brew install mysql
    echo -e "${GREEN}✅ MySQL installé${NC}"
else
    echo -e "${GREEN}✅ MySQL est déjà installé${NC}"
fi

# Démarrer MySQL
echo ""
echo "🚀 Démarrage de MySQL..."
brew services start mysql 2>/dev/null || mysql.server start

# Attendre que MySQL soit prêt
echo "⏳ Attente du démarrage de MySQL..."
sleep 3

# Vérifier que MySQL fonctionne
if mysql -u root -e "SELECT 1" &>/dev/null; then
    echo -e "${GREEN}✅ MySQL est en cours d'exécution${NC}"
else
    echo -e "${YELLOW}⚠️  MySQL nécessite un mot de passe${NC}"
    echo "Vous devrez entrer le mot de passe root pour continuer"
fi

echo ""
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}✅ Configuration terminée !${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo ""
echo "📝 Prochaines étapes :"
echo ""
echo "1. Créer la base de données :"
echo "   ${YELLOW}mysql -u root -p < database.sql${NC}"
echo ""
echo "2. Ajouter les utilisateurs :"
echo "   ${YELLOW}mysql -u root -p gestion_cours < insert_users.sql${NC}"
echo ""
echo "   OU via le navigateur :"
echo "   ${YELLOW}http://localhost:8000/add_users.php${NC}"
echo ""
echo "3. Vérifier la connexion :"
echo "   ${YELLOW}http://localhost:8000/test-connection.php${NC}"
echo ""

