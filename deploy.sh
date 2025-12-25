#!/bin/bash

# Script de déploiement pour le système de gestion de cours
echo "🚀 Déploiement du système de gestion de cours..."
echo ""

# Couleurs pour les messages
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Vérifier si PHP est installé
echo "📋 Vérification des prérequis..."
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP n'est pas installé${NC}"
    exit 1
fi
echo -e "${GREEN}✅ PHP est installé${NC}"

# Vérifier si MySQL est installé
if ! command -v mysql &> /dev/null; then
    echo -e "${YELLOW}⚠️  MySQL n'est pas installé ou n'est pas dans le PATH${NC}"
else
    echo -e "${GREEN}✅ MySQL est installé${NC}"
fi

# Créer le dossier uploads s'il n'existe pas
echo ""
echo "📁 Création des dossiers nécessaires..."
if [ ! -d "uploads" ]; then
    mkdir -p uploads
    chmod 755 uploads
    echo -e "${GREEN}✅ Dossier uploads créé${NC}"
else
    echo -e "${GREEN}✅ Dossier uploads existe déjà${NC}"
fi

# Vérifier la configuration de la base de données
echo ""
echo "🔧 Vérification de la configuration..."
if [ ! -f "lib/dsn_perso.php" ]; then
    if [ -f "lib/dsn_perso.example.php" ]; then
        cp lib/dsn_perso.example.php lib/dsn_perso.php
        echo -e "${YELLOW}⚠️  Fichier dsn_perso.php créé à partir de l'exemple${NC}"
        echo -e "${YELLOW}   Veuillez configurer vos identifiants de base de données dans lib/dsn_perso.php${NC}"
    else
        echo -e "${RED}❌ Fichier de configuration de base de données introuvable${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}✅ Configuration de base de données trouvée${NC}"
fi

# Vérifier si la base de données existe
echo ""
echo "🗄️  Vérification de la base de données..."
read -p "Voulez-vous créer/importer la base de données maintenant ? (o/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Oo]$ ]]; then
    if [ -f "database.sql" ]; then
        echo "Importation de la base de données..."
        read -p "Nom d'utilisateur MySQL (par défaut: root): " db_user
        db_user=${db_user:-root}
        read -sp "Mot de passe MySQL: " db_pass
        echo
        mysql -u "$db_user" -p"$db_pass" < database.sql
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✅ Base de données créée avec succès${NC}"
        else
            echo -e "${RED}❌ Erreur lors de la création de la base de données${NC}"
        fi
    else
        echo -e "${RED}❌ Fichier database.sql introuvable${NC}"
    fi
fi

# Vérifier les permissions
echo ""
echo "🔐 Vérification des permissions..."
chmod 755 uploads
echo -e "${GREEN}✅ Permissions configurées${NC}"

# Résumé
echo ""
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}✅ Déploiement terminé !${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo ""
echo "📝 Prochaines étapes :"
echo "   1. Configurez lib/dsn_perso.php avec vos identifiants MySQL"
echo "   2. Assurez-vous que la base de données est créée (exécutez database.sql)"
echo "   3. Démarrez votre serveur web :"
echo ""
echo "   Option 1 - Serveur PHP intégré :"
echo "   ${YELLOW}php -S localhost:8000${NC}"
echo ""
echo "   Option 2 - Apache/Nginx :"
echo "   Configurez votre serveur web pour pointer vers ce dossier"
echo ""
echo "   4. Accédez à l'application :"
echo "   ${YELLOW}http://localhost:8000${NC}"
echo ""
echo "   Compte administrateur par défaut :"
echo "   Email: ${YELLOW}admin@ecole.fr${NC}"
echo "   Mot de passe: ${YELLOW}admin123${NC}"
echo ""

