<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Activation des notes de quiz en ligne
    |--------------------------------------------------------------------------
    |
    | Phase 1 (false) : Notes manuelles uniquement (CC + Normale)
    | Phase 2 (true)  : Notes manuelles + Quiz en ligne
    |
    */
    'include_quiz_online' => false,

    /*
    |--------------------------------------------------------------------------
    | Pondérations Phase 1 (sans quiz en ligne)
    |--------------------------------------------------------------------------
    |
    | Normale : 70%
    | CC      : 30%
    | Total   : 100%
    |
    */
    'ponderation_phase1' => [
        'normale' => 70,
        'cc' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pondérations Phase 2 (avec quiz en ligne)
    |--------------------------------------------------------------------------
    |
    | Quiz    : 20%
    | Normale : 56% (70% de 80%)
    | CC      : 24% (30% de 80%)
    | Total   : 100%
    |
    */
    'ponderation_phase2' => [
        'quiz' => 20,
        'normale' => 56,
        'cc' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Mentions selon la moyenne générale
    |--------------------------------------------------------------------------
    |
    | Clé = seuil minimum pour la mention
    | Valeur = nom de la mention
    |
    */
    'mentions' => [
        16 => 'Très Bien',
        14 => 'Bien',
        12 => 'Assez Bien',
        10 => 'Passable',
        0  => 'Insuffisant',
    ],

    /*
    |--------------------------------------------------------------------------
    | Note maximale
    |--------------------------------------------------------------------------
    */
    'note_max' => 20,

    /*
    |--------------------------------------------------------------------------
    | Informations de l'institution (pour le bulletin)
    |--------------------------------------------------------------------------
    */
    'institution' => [
        'nom' => env('INSTITUTION_NOM', 'Institut 3iA - Ingénierie Informatique Appliquée'),
        'adresse' => env('INSTITUTION_ADRESSE', ''),
        'telephone' => env('INSTITUTION_TEL', ''),
        'email' => env('INSTITUTION_EMAIL', ''),
        'logo' => env('INSTITUTION_LOGO', 'acceuille/assets/images/3ia logo-01 1.png'),
    ],
];
