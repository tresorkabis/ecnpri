<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CNPRI - Programme des inspections
    |--------------------------------------------------------------------------
    | Configuration du document "Proposition du Programme des Inspections".
    |
    | signature_name  : nom du signataire du programme (Directeur des inspections)
    | signature_title : fonction officielle du signataire affichée sous le nom
    | signature_ville : lieu d'émission du programme
    */
    'signature_name' => env('CNPRI_SIGNATURE_NAME', 'WANGUNA CHING-CHEY Bibiche'),
    'signature_title' => env('CNPRI_SIGNATURE_TITLE', 'Directrice des inspections'),
    'signature_ville' => env('CNPRI_SIGNATURE_VILLE', 'Kinshasa'),

    /*
    |--------------------------------------------------------------------------
    | Autorité d'autorisation des missions d'inspection
    |--------------------------------------------------------------------------
    | Autorité qui autorise les inspections du CNPRI (champ "Autorisée par").
    */
    'authorization_authority' => env('CNPRI_AUTHORIZATION_AUTHORITY', 'Le Président du CNPRI'),
];