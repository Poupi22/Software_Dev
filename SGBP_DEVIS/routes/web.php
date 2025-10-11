<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\DevisController;
use App\Http\Controllers\Admin\FactureController;
use App\Http\Controllers\Admin\PvController;
use App\Http\Controllers\Admin\ProspectController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProjetController;

// ── Routes publiques (Front Office) ──
Route::get('/', [FrontController::class, 'welcome'])->name('home');
Route::get('/projets', [FrontController::class, 'projets'])->name('projets');
Route::get('/contact', [FrontController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontController::class, 'sendContact'])->name('contact.send');

// Routes protégées (Authentification requise)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

    // Users (avec permission)
    Route::middleware('permission:users.read')->group(function () {
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:users.create');
            Route::post('/', 'store')->name('store')->middleware('permission:users.create');
            Route::get('/{user}', 'show')->name('show');
            Route::get('/{user}/edit', 'edit')->name('edit')->middleware('permission:users.update');
            Route::put('/{user}', 'update')->name('update')->middleware('permission:users.update');
            Route::delete('/{user}', 'destroy')->name('destroy')->middleware('permission:users.delete');

            // ⚡ AJOUTER CES 2 LIGNES
            Route::patch('/{user}/toggle-status', 'toggleStatus')->name('toggle-status')->middleware('permission:users.update');
            Route::post('/{user}/reset-password', 'resetPassword')->name('reset-password')->middleware('permission:users.update');
        });
    });

    // Roles (Super Admin uniquement)
    Route::middleware('permission:roles.manage')->group(function () {
        Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{role}/edit', 'edit')->name('edit');
            Route::put('/{role}', 'update')->name('update');
            Route::delete('/{role}', 'destroy')->name('destroy');
        });
    });

    // Permissions (Super Admin uniquement)
    Route::middleware('permission:permissions.manage')->group(function () {
        Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{permission}', 'show')->name('show');
            Route::get('/{permission}/edit', 'edit')->name('edit');
            Route::put('/{permission}', 'update')->name('update');
            Route::delete('/{permission}', 'destroy')->name('destroy');
            Route::post('/sync-config', 'sync')->name('sync');
        });
    });

    // Clients

    Route::middleware('permission:clients.read')->group(function () {
        Route::controller(ClientController::class)->prefix('clients')->name('clients.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:clients.create');
            Route::post('/', 'store')->name('store')->middleware('permission:clients.create');
            Route::get('/{client}', 'show')->name('show');
            Route::get('/{client}/edit', 'edit')->name('edit')->middleware('permission:clients.update');
            Route::put('/{client}', 'update')->name('update')->middleware('permission:clients.update');
            Route::delete('/{client}', 'destroy')->name('destroy')->middleware('permission:clients.delete');

            // Route spéciale pour toggle status
            Route::patch('/{client}/toggle-status', 'toggleStatus')->name('toggle-status')->middleware('permission:clients.update');
        });
    });

    // Categories
    Route::middleware('permission:categories.read')->group(function () {
        Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:categories.create');
            Route::post('/', 'store')->name('store')->middleware('permission:categories.create');
            Route::get('/{category}/edit', 'edit')->name('edit')->middleware('permission:categories.update');
            Route::put('/{category}', 'update')->name('update')->middleware('permission:categories.update');
            Route::delete('/{category}', 'destroy')->name('destroy')->middleware('permission:categories.delete');
            Route::patch('/{category}/toggle-status', 'toggleStatus')->name('toggle-status')->middleware('permission:categories.update');
        });
    });

    // Articles
    Route::middleware('permission:articles.read')->group(function () {
        Route::controller(ArticleController::class)->prefix('articles')->name('articles.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:articles.create');
            Route::post('/', 'store')->name('store')->middleware('permission:articles.create');
            Route::get('/{article}', 'show')->name('show');
            Route::get('/{article}/edit', 'edit')->name('edit')->middleware('permission:articles.update');
            Route::put('/{article}', 'update')->name('update')->middleware('permission:articles.update');
            Route::delete('/{article}', 'destroy')->name('destroy')->middleware('permission:articles.delete');
            Route::patch('/{article}/toggle-status', 'toggleStatus')->name('toggle-status')->middleware('permission:articles.update');
        });
    });

    // Devis
    Route::middleware('permission:devis.read')->group(function () {
        Route::controller(DevisController::class)->prefix('devis')->name('devis.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:devis.create');
            Route::post('/', 'store')->name('store')->middleware('permission:devis.create');
            Route::get('/{devis}', 'show')->name('show');
            Route::get('/{devis}/edit', 'edit')->name('edit')->middleware('permission:devis.update');
            Route::put('/{devis}', 'update')->name('update')->middleware('permission:devis.update');
            Route::delete('/{devis}', 'destroy')->name('destroy')->middleware('permission:devis.delete');
            // Actions spéciales
            Route::post('/{devis}/send', 'send')->name('send')->middleware('permission:devis.send');
            Route::post('/{devis}/convert', 'convertToFacture')->name('convert')->middleware('permission:devis.convert');
            Route::get('/{devis}/pdf', 'generatePDF')->name('pdf');
            Route::get('/{devis}/pdf-sans-cachet', 'generatePDFSansCachet')->name('pdf-sans-cachet');
            Route::patch('/{devis}/finaliser', 'finaliser')->name('finaliser')->middleware('permission:devis.update');
            Route::post('/{devis}/accept', 'accept')->name('accept')->middleware('permission:devis.update');
            Route::get('/{devis}/bc/download', 'downloadBc')->name('bc.download');
        });
    });

    // Factures
    Route::middleware('permission:factures.read')->group(function () {
        Route::controller(FactureController::class)->prefix('factures')->name('factures.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:factures.create');
            Route::post('/', 'store')->name('store')->middleware('permission:factures.create');
            Route::get('/{facture}', 'show')->name('show');
            Route::get('/{facture}/edit', 'edit')->name('edit')->middleware('permission:factures.update');
            Route::put('/{facture}', 'update')->name('update')->middleware('permission:factures.update');
            Route::delete('/{facture}', 'destroy')->name('destroy')->middleware('permission:factures.delete');
            // Actions spéciales
            Route::post('/{facture}/send', 'send')->name('send')->middleware('permission:factures.send');
            Route::post('/{facture}/payment', 'registerPayment')->name('payment')->middleware('permission:factures.payment');
            Route::get('/{facture}/pdf', 'generatePDF')->name('pdf');
        });
    });

    // PVs
    Route::middleware('permission:pvs.read')->group(function () {
        Route::controller(PvController::class)->prefix('pvs')->name('pvs.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:pvs.create');
            Route::post('/', 'store')->name('store')->middleware('permission:pvs.create');
            Route::get('/{pv}', 'show')->name('show');
            Route::get('/{pv}/edit', 'edit')->name('edit')->middleware('permission:pvs.update');
            Route::put('/{pv}', 'update')->name('update')->middleware('permission:pvs.update');
            Route::delete('/{pv}', 'destroy')->name('destroy')->middleware('permission:pvs.delete');
            Route::get('/{pv}/pdf', 'generatePDF')->name('pdf');
            Route::post('/{pv}/send', 'send')->name('send')->middleware('permission:pvs.send');
            Route::patch('/{pv}/signer', 'signer')->name('signer')->middleware('permission:pvs.update');
        });
    });

    // Prospects
    Route::middleware('permission:prospects.read')->group(function () {
        Route::controller(ProspectController::class)->prefix('prospects')->name('prospects.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{prospect}', 'show')->name('show');
            Route::put('/{prospect}', 'update')->name('update')->middleware('permission:prospects.update');
            Route::delete('/{prospect}', 'destroy')->name('destroy')->middleware('permission:prospects.delete');
            Route::post('/{prospect}/convert', 'convertToClient')->name('convert')->middleware('permission:prospects.convert');
        });
    });

    // Services (site vitrine)
    Route::middleware('permission:services.read')->group(function () {
        Route::controller(ServiceController::class)->prefix('services')->name('services.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:services.create');
            Route::post('/', 'store')->name('store')->middleware('permission:services.create');
            Route::get('/{service}/edit', 'edit')->name('edit')->middleware('permission:services.update');
            Route::put('/{service}', 'update')->name('update')->middleware('permission:services.update');
            Route::delete('/{service}', 'destroy')->name('destroy')->middleware('permission:services.delete');
        });
    });

    // Projets (site vitrine)
    Route::middleware('permission:projets.read')->group(function () {
        Route::controller(ProjetController::class)->prefix('projets')->name('projets.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create')->middleware('permission:projets.create');
            Route::post('/', 'store')->name('store')->middleware('permission:projets.create');
            Route::get('/{projet}/edit', 'edit')->name('edit')->middleware('permission:projets.update');
            Route::put('/{projet}', 'update')->name('update')->middleware('permission:projets.update');
            Route::delete('/{projet}', 'destroy')->name('destroy')->middleware('permission:projets.delete');
            // Gestion des photos
            Route::delete('/{projet}/photos/{photo}', 'deletePhoto')->name('photos.delete')->middleware('permission:projets.update');
            Route::patch('/{projet}/photos/{photo}/principale', 'setPrincipale')->name('photos.principale')->middleware('permission:projets.update');
        });
    });

    // Parametres
    Route::middleware('permission:settings.view')->group(function () {
        Route::controller(ParametreController::class)->prefix('parametres')->name('parametres.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/', 'update')->name('update')->middleware('permission:settings.edit');
        });
    });

    // Notifications
    Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{notification}/read', 'markAsRead')->name('read');
        Route::post('/read-all', 'markAllAsRead')->name('readAll');
    });
});

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

require __DIR__.'/auth.php';
