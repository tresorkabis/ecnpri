@echo off
chcp 65001 > nul
title CNPRI - Système de Gestion des Inspections

cd /d "%~dp0"

echo ======================================================
echo    CNPRI - SYSTÈME DE GESTION DES INSPECTIONS
echo ======================================================
echo.

:: 1. Vérification de PHP
php -v > nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] PHP n'est pas détecté dans votre variable d'environnement PATH.
    echo Veuillez installer PHP ou l'ajouter au PATH Windows.
    echo.
    pause
    exit /b 1
)

:: 2. Vérification et création du fichier .env
if not exist ".env" (
    echo [INFO] Création du fichier de configuration .env...
    copy .env.example .env > nul
    echo [INFO] Génération de la clé de sécurité (APP_KEY)...
    php artisan key:generate --ansi
    echo.
)

:: 3. Vérification et initialisation de la base SQLite
if not exist "database\database.sqlite" (
    echo [INFO] Création de la base de données SQLite...
    type nul > "database\database.sqlite"
    echo [INFO] Application des migrations et insertion des données CNPRI...
    php artisan migrate:fresh --seed --seeder=CnpriSeeder --force --ansi
    echo.
)

:: 4. Nettoyage préventif du cache
echo [INFO] Optimisation et nettoyage du cache Laravel...
php artisan optimize:clear > nul 2>&1

:: 5. Ouverture automatique du navigateur
echo.
echo [INFO] Démarrage du serveur web local sur http://127.0.0.1:8000 ...
echo [INFO] Ouverture du Tableau de Bord dans votre navigateur...
start http://127.0.0.1:8000/dashboard

echo.
echo ======================================================
echo Application accessible sur : http://127.0.0.1:8000
echo Pour arrêter le serveur, appuyez sur [Ctrl + C]
echo ======================================================
echo.

:: 6. Lancement du serveur Artisan
php artisan serve --port=8000

pause

