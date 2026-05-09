### Application de Gestion des Inspections (Inspirée du CNPRI)

Cette application Laravel a été générée pour répondre aux besoins de gestion des inspections pour une autorité de régulation radiologique telle que le **CNPRI** (RDC).

#### Structure des Données
- **Établissements** (`Establishment`) : Entités réglementées (Hôpitaux, Mines, Centres de recherche).
- **Équipements** (`Equipment`) : Sources de rayonnements ou machines X au sein des établissements.
- **Inspecteurs** (`Inspector`) : Personnel chargé des audits.
- **Inspections** (`Inspection`) : Processus d'inspection avec date, type et statut.
- **Constats** (`Finding`) : Observations relevées lors des inspections, avec sévérité et recommandations.

#### Installation et Utilisation
1. **Migrations et Données** :
   ```bash
   php artisan migrate:fresh --seed --seeder=CnpriSeeder
   ```
2. **Endpoints API de base** :
   - `GET /establishments` : Liste des établissements.
   - `GET /establishments/{id}` : Détails d'un établissement avec ses équipements et inspections.
   - `GET /inspections` : Liste des inspections.
   - `GET /inspections/{id}` : Détails d'une inspection avec les inspecteurs et les constats.

#### Identifiants de test (Seeder)
- **Utilisateur Admin** : admin@cnpri.cd / password
- **Exemples d'établissements** : Clinique Ngaliema, Tenke Fungurume Mining.
