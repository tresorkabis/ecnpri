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

    /*
    |--------------------------------------------------------------------------
    | Ministère de tutelle (en-tête des rapports d'inspection)
    |--------------------------------------------------------------------------
    */
    'ministry' => env('CNPRI_MINISTRY', "Ministère de l'Enseignement Supérieur, Universitaire, Recherche Scientifique et Innovations"),

    /*
    |--------------------------------------------------------------------------
    | Provinces de la République Démocratique du Congo (26)
    |--------------------------------------------------------------------------
    | Liste officielle des provinces et de leurs chefs-lieux, utilisée pour
    | les listes déroulantes des établissements et les regroupements
    | géographiques (zones de tournée) du programme des inspections.
    */
    'provinces' => [
        'Bas-Uele' => 'Buta',
        'Équateur' => 'Mbandaka',
        'Haut-Katanga' => 'Lubumbashi',
        'Haut-Lomami' => 'Kamina',
        'Haut-Uele' => 'Isiro',
        'Ituri' => 'Bunia',
        'Kasaï' => 'Tshikapa',
        'Kasaï central' => 'Kananga',
        'Kasaï oriental' => 'Mbujimayi',
        'Kinshasa' => 'Kinshasa',
        'Kongo-Central' => 'Matadi',
        'Kwango' => 'Kenge',
        'Kwilu' => 'Bandundu',
        'Lomami' => 'Kabinda',
        'Lualaba' => 'Kolwezi',
        'Mai-Ndombe' => 'Inongo',
        'Maniema' => 'Kindu',
        'Mongala' => 'Lisala',
        'Nord-Kivu' => 'Goma',
        'Nord-Ubangi' => 'Gbadolite',
        'Sankuru' => 'Lusambo',
        'Sud-Kivu' => 'Bukavu',
        'Sud-Ubangi' => 'Gemena',
        'Tanganyika' => 'Kalemie',
        'Tshopo' => 'Kisangani',
        'Tshuapa' => 'Boende',
    ],
];