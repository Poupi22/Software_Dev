<?php
return [
    // Tables nécessitant les 4 permissions CRUD
    'resources' => [
        'devis' => [
            'display_name' => 'Devis',
            'icon' => 'description',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'factures' => [
            'display_name' => 'Factures',
            'icon' => 'receipt_long',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'clients' => [
            'display_name' => 'Clients',
            'icon' => 'people',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'articles' => [
            'display_name' => 'Articles',
            'icon' => 'inventory_2',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'categories' => [
            'display_name' => 'Catégories',
            'icon' => 'category',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'pvs' => [
            'display_name' => 'PV de Réception',
            'icon' => 'assignment_turned_in',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'prospects' => [
            'display_name' => 'Prospects',
            'icon' => 'contact_page',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'users' => [
            'display_name' => 'Utilisateurs',
            'icon' => 'person',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'services' => [
            'display_name' => 'Services (Site)',
            'icon' => 'build',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
        'projets' => [
            'display_name' => 'Projets (Site)',
            'icon' => 'photo_library',
            'permissions' => ['create', 'read', 'update', 'delete']
        ],
    ],

    // Permissions spéciales (pas de CRUD standard)
    'special_permissions' => [
        'devis.send' => 'Envoyer un devis',
        'devis.convert' => 'Convertir devis en facture',
        'factures.send' => 'Envoyer une facture',
        'factures.payment' => 'Gérer les paiements',
        'pvs.send' => 'Envoyer un PV de réception',
        'prospects.convert' => 'Convertir prospect en client',
        'settings.view' => 'Voir les paramètres',
        'settings.edit' => 'Modifier les paramètres',
        'dashboard.view' => 'Accéder au tableau de bord',
        'dashboard.stats' => 'Voir les statistiques',
        'roles.manage' => 'Gérer les rôles',
        'permissions.manage' => 'Gérer les permissions',
    ],
];
