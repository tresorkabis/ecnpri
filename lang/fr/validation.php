<?php

return [
    'required' => 'Le champ :attribute est obligatoire.',
    'date' => 'Le champ :attribute doit être une date valide.',
    'after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
    'in' => 'Le champ :attribute sélectionné est invalide.',
    'string' => 'Le champ :attribute doit être du texte.',
    'array' => 'Le champ :attribute doit être une sélection.',
    'max' => [
        'numeric' => 'Le champ :attribute ne peut pas dépasser :max.',
        'file' => 'Le fichier :attribute ne peut pas dépasser :max kilo-octets.',
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
        'array' => 'Le champ :attribute ne peut pas contenir plus de :max éléments.',
    ],
    'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
    'mimes' => 'Le fichier :attribute doit être au format :values.',

    'custom' => [
        'establishment_id' => [
            'required' => 'Veuillez sélectionner un établissement à inspecter.',
        ],
        'start_date' => [
            'required' => 'La date de début est obligatoire.',
        ],
        'end_date' => [
            'required' => 'La date de fin est obligatoire.',
        ],
        'inspectors' => [
            'required' => 'Veuillez sélectionner au moins un inspecteur.',
        ],
        'report' => [
            'mimes' => 'Le rapport doit être un fichier PDF.',
        ],
    ],

    'attributes' => [
        'establishment_id' => 'établissement',
        'team_leader_id' => 'chef de mission',
        'start_date' => 'date de début',
        'end_date' => 'date de fin',
        'type' => "type d'inspection",
        'purpose' => 'objet de la mission',
        'inspectors' => 'inspecteurs',
        'summary' => 'résumé',
        'methodology' => 'méthodologie',
        'conclusion' => 'conclusion',
        'site_representative' => "représentant de l'établissement",
        'site_representative_title' => 'fonction du représentant',
        'authorized_by' => 'autorisée par',
        'report' => 'rapport',
    ],
];
