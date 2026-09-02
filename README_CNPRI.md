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

#### Filtres de la liste des inspections
La page **Inspections** (`/inspections`) offre un bandeau de filtres (méthode GET) :

| Paramètre | Description | Exemple |
|---|---|---|
| `recherche` | Texte libre sur le nom de l'établissement ou l'objet de la mission | `?recherche=PERENCO` |
| `statut` | `Brouillon`, `Approuvée`, `En cours`, `Effectuée`, `Annulée` ou `prevues` (Brouillon + Approuvée + En cours) | `?statut=prevues` |
| `type` | `réglementaire`, `investigation`, `inopiné` | `?type=investigation` |
| `etablissement_id` | Identifiant d'un établissement | `?etablissement_id=1` |
| `inspecteur_id` | Identifiant d'un inspecteur (missions auxquelles il participe) | `?inspecteur_id=3` |
| `date_debut` / `date_fin` | Période de début des missions (`YYYY-MM-DD`) | `?date_debut=2026-07-01&date_fin=2026-12-31` |

Les filtres sont combinables et la réponse JSON (`Accept: application/json`) applique les mêmes conditions.

#### Tri et pagination de la liste des inspections
- **Tri par colonne** : cliquer sur les en-têtes **Date**, **Établissement**, **Type** ou **Statut** (▼ = tri actif, cliquez pour inverser). Paramètres `tri` (`date`, `etablissement`, `type`, `statut`) et `sens` (`asc`, `desc`). Ex. : `?tri=etablissement&sens=asc`.
- **Pagination** : 15 missions par page (`?page=2`), les filtres et le tri sont conservés d'une page à l'autre (`withQueryString`).

#### Programme des inspections (export Word)
La page **Programme** (`/inspections/programme`) présente le programme semestriel des inspections regroupé par type (`Réglementaire`, `Investigation`) puis par zone de tournée (Kinshasa, Kongo-Central, autres provinces), avec les colonnes : N°, Date, Installation, Localisation et Inspecteurs — au format du document `PROPOSITION DU PROGRAMME DES INSPECTIONS DU ... SEMESTRE ... .docx`.

- **Précharger le 2ᵉ semestre 2026** (proposition du programme) :
  ```bash
  php artisan db:seed --class=ProgrammeInspectionsSeeder
  ```
- **Générer le document Word** : depuis la page Programme, cliquer sur `Exporter en Word (.docx)` (ou `GET /inspections/programme/export?annee=2026&semestre=2`).
- **Paramètres** : `annee` (défaut : année courante), `semestre` (`1` ou `2`), `statut` (`prevues` = Brouillon/Approuvée/En cours, `toutes`).
- Le type d'inspection **`investigation`** est disponible dans le formulaire de programmation d'une inspection.

**Modifier le nom du Directeur des inspections (signature du programme)**

Le nom, la fonction et la ville de signature se configurent dans `config/cnpri.php` :

```php
'signature_name'  => env('CNPRI_SIGNATURE_NAME', 'WANGUNA CHING-CHEY Bibiche'),
'signature_title' => env('CNPRI_SIGNATURE_TITLE', 'Directrice des inspections'),
'signature_ville' => env('CNPRI_SIGNATURE_VILLE', 'Kinshasa'),
```

Deux options :
1. **Fichier `.env`** (recommandé) : ajouter / modifier
   ```
   CNPRI_SIGNATURE_NAME="MARTIN NGOLO Justin"
   CNPRI_SIGNATURE_TITLE="Directeur des inspections"
   CNPRI_SIGNATURE_VILLE="Kinshasa"
   ```
   puis `php artisan config:clear` (ou redémarrer le serveur).
2. **Directement dans `config/cnpri.php`** : modifier les valeurs par défaut.

Le changement s'applique à la fois à la page **Programme** et au document Word exporté.

#### Identifiants de test (Seeder)
- **Utilisateur Admin** : admin@cnpri.cd / password
- **Exemples d'établissements** : Clinique Ngaliema, Tenke Fungurume Mining.
