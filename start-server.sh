#!/bin/bash

# Script pour démarrer le serveur PHP intégré
echo "🚀 Démarrage du serveur de développement..."
echo ""
echo "📍 Le site sera accessible à : http://localhost:8000"
echo ""
echo "⚠️  Appuyez sur Ctrl+C pour arrêter le serveur"
echo ""

cd "$(dirname "$0")"
php -S localhost:8000

