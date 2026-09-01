#!/usr/bin/env bash

# Se positionner dans le dossier du script / projet
cd "$(dirname "$0")"

echo "======================================================"
echo "   CNPRI - LANCEMENT DE L'APPLICATION"
echo "======================================================"
echo ""
echo "Démarrage du serveur sur http://127.0.0.1:8000 ..."
echo "Pour arrêter l'application, appuyez sur [Ctrl + C]"
echo ""

# Ouvrir le navigateur (compatible macOS et Linux)
if command -v open >/dev/null 2>&1; then
    # macOS
    open "http://127.0.0.1:8000/dashboard"
elif command -v xdg-open >/dev/null 2>&1; then
    # Linux
    xdg-open "http://127.0.0.1:8000/dashboard" >/dev/null 2>&1 &
fi

# Lancement du serveur Artisan
php artisan serve --port=8000

