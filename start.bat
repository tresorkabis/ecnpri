@echo off
chcp 65001 > nul
title CNPRI - Système de Gestion des Inspections

:: Se positionner dans le dossier de l'application
if exist "C:\soft\ecnpri" (
    cd /d "C:\soft\ecnpri"
) else (
    cd /d "%~dp0"
)

echo ======================================================
echo    CNPRI - LANCEMENT DE L'APPLICATION
echo    Emplacement : C:\soft\ecnpri
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

:: 2. Ouverture automatique du navigateur
echo [INFO] Ouverture du Tableau de Bord dans le navigateur...
start http://127.0.0.1:8000/dashboard

echo.
echo ======================================================
echo Application accessible sur : http://127.0.0.1:8000
echo Pour arrêter le serveur, appuyez sur [Ctrl + C]
echo ======================================================
echo.

:: 3. Lancement du serveur Artisan
php artisan serve --port=8000

pause
