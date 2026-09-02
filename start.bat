@echo off
title CNPRI - Système de Gestion des Inspections

cd /d "C:\soft\ecnpri"

echo ======================================================
echo    CNPRI - LANCEMENT DE L'APPLICATION
echo ======================================================
echo.
echo Mise a jour du code depuis GitHub (git pull)...
git pull
if errorlevel 1 (
    echo ATTENTION : echec du git pull. Demarrage avec la version locale.
)
echo.
echo Demarrage du serveur sur http://127.0.0.1:8000 ...
echo Pour arreter l'application, appuyez sur [Ctrl + C]
echo.

start http://127.0.0.1:8000/dashboard

php artisan serve --port=8000

pause
