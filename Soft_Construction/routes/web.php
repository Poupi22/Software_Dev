<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontHomeController;


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('')->name('home.')->group(function () {
    // Updated home route to use controller
    Route::get('/', [FrontHomeController::class, 'index'])->name('home');

   // Home routes
Route::get('/about', [App\Http\Controllers\FrontAboutController::class, 'index'])->name('home.about');

    Route::get('/services', [App\Http\Controllers\FrontServiceController::class, 'index'])->name('home.services');

    Route::get('/formations', [App\Http\Controllers\FrontFormationController::class, 'index'])->name('home.formations');

    Route::get('/projets', [App\Http\Controllers\FrontProjetController::class, 'index'])->name('home.projets');

    Route::get('/contact', [App\Http\Controllers\FrontContactController::class, 'index'])->name('home.contact');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:administrator|editor'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
       

    // Home Slides
    Route::resource('home_slides', \App\Http\Controllers\Admin\HomeSlideController::class)
        ->names([
            'index'   => 'home_slides.index',
            'create'  => 'home_slides.create',
            'store'   => 'home_slides.store',
            'show'    => 'home_slides.show',  
            'edit'    => 'home_slides.edit',
            'update'  => 'home_slides.update',
            'destroy' => 'home_slides.destroy'
        ])
        ->middleware([
            'index'   => 'permission:home_slides.view',
            'create'  => 'permission:home_slides.create',
            'store'   => 'permission:home_slides.create',
            'show'    => 'permission:home_slides.view',
            'edit'    => 'permission:home_slides.edit',
            'update'  => 'permission:home_slides.edit',
            'destroy' => 'permission:home_slides.delete'
        ]);

    // Services
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)
        ->names([
            'index'   => 'services.index',
            'create'  => 'services.create',
            'store'   => 'services.store',
            'show'    => 'services.show',  
            'edit'    => 'services.edit',
            'update'  => 'services.update',
            'destroy' => 'services.destroy'
        ])
        ->middleware([
            'index'   => 'permission:services.view',
            'create'  => 'permission:services.create',
            'store'   => 'permission:services.create',
            'show'    => 'permission:services.view',
            'edit'    => 'permission:services.edit',
            'update'  => 'permission:services.edit',
            'destroy' => 'permission:services.delete'
        ]);

    // Abouts
    Route::resource('abouts', \App\Http\Controllers\Admin\AboutController::class)
        ->names([
            'index'   => 'abouts.index',
            'create'  => 'abouts.create',
            'store'   => 'abouts.store',
            'show'    => 'abouts.show',
            'edit'    => 'abouts.edit',
            'update'  => 'abouts.update',
            'destroy' => 'abouts.destroy'
        ])
        ->middleware([
            'index'   => 'permission:abouts.view',
            'create'  => 'permission:abouts.create',
            'store'   => 'permission:abouts.create',
            'show'    => 'permission:abouts.view',
            'edit'    => 'permission:abouts.edit',
            'update'  => 'permission:abouts.edit',
            'destroy' => 'permission:abouts.delete'
        ]);

    // Trainings
    Route::resource('trainings', \App\Http\Controllers\Admin\TrainingController::class)
        ->names([
            'index' => 'trainings.index',
            'create' => 'trainings.create',
            'store' => 'trainings.store',
            'show' => 'trainings.show',
            'edit' => 'trainings.edit',
            'update' => 'trainings.update',
            'destroy' => 'trainings.destroy'
        ])
        ->middleware([
            'index'   => 'permission:trainings.view',
            'create'  => 'permission:trainings.create',
            'store'   => 'permission:trainings.create',
            'show'    => 'permission:trainings.view',
            'edit'    => 'permission:trainings.edit',
            'update'  => 'permission:trainings.edit',
            'destroy' => 'permission:trainings.delete'
        ]);

    // Home Services
    Route::resource('home-services', \App\Http\Controllers\Admin\HomeServiceController::class)
        ->names([
            'index' => 'home-services.index',
            'create' => 'home-services.create',
            'store' => 'home-services.store',
            'show' => 'home-services.show',
            'edit' => 'home-services.edit',
            'update' => 'home-services.update',
            'destroy' => 'home-services.destroy'
        ])
        ->middleware([
            'index'   => 'permission:home_services.view',
            'create'  => 'permission:home_services.create',
            'store'   => 'permission:home_services.create',
            'show'    => 'permission:home_services.view',
            'edit'    => 'permission:home_services.edit',
            'update'  => 'permission:home_services.edit',
            'destroy' => 'permission:home_services.delete'
        ]);

    // Projects
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class)
        ->names([
            'index' => 'projects.index',
            'create' => 'projects.create',
            'store' => 'projects.store',
            'show' => 'projects.show',
            'edit' => 'projects.edit',
            'update' => 'projects.update',
            'destroy' => 'projects.destroy'
        ])
        ->middleware([
            'index'   => 'permission:projects.view',
            'create'  => 'permission:projects.create',
            'store'   => 'permission:projects.create',
            'show'    => 'permission:projects.view',
            'edit'    => 'permission:projects.edit',
            'update'  => 'permission:projects.edit',
            'destroy' => 'permission:projects.delete'
        ]);

    // Testimonials
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)
        ->names([
            'index' => 'testimonials.index',
            'create' => 'testimonials.create',
            'store' => 'testimonials.store',
            'show' => 'testimonials.show',
            'edit' => 'testimonials.edit',
            'update' => 'testimonials.update',
            'destroy' => 'testimonials.destroy'
        ])
        ->middleware([
            'index'   => 'permission:testimonials.view',
            'create'  => 'permission:testimonials.create',
            'store'   => 'permission:testimonials.create',
            'show'    => 'permission:testimonials.view',
            'edit'    => 'permission:testimonials.edit',
            'update'  => 'permission:testimonials.edit',
            'destroy' => 'permission:testimonials.delete'
        ]);

    // Partners
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class)
        ->names([
            'index' => 'partners.index',
            'create' => 'partners.create',
            'store' => 'partners.store',
            'show' => 'partners.show',
            'edit' => 'partners.edit',
            'update' => 'partners.update',
            'destroy' => 'partners.destroy'
        ])
        ->middleware([
            'index'   => 'permission:partners.view',
            'create'  => 'permission:partners.create',
            'store'   => 'permission:partners.create',
            'show'    => 'permission:partners.view',
            'edit'    => 'permission:partners.edit',
            'update'  => 'permission:partners.edit',
            'destroy' => 'permission:partners.delete'
        ]);

    // Contacts
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class)
        ->names([
            'index' => 'contacts.index',
            'create' => 'contacts.create',
            'store' => 'contacts.store',
            'show' => 'contacts.show',
            'edit' => 'contacts.edit',
            'update' => 'contacts.update',
            'destroy' => 'contacts.destroy'
        ])
        ->middleware([
            'index'   => 'permission:contacts.view',
            'create'  => 'permission:contacts.create',
            'store'   => 'permission:contacts.create',
            'show'    => 'permission:contacts.view',
            'edit'    => 'permission:contacts.edit',
            'update'  => 'permission:contacts.edit',
            'destroy' => 'permission:contacts.delete'
        ]);

    // Personnels
    Route::resource('personnels', \App\Http\Controllers\Admin\PersonnelController::class)
        ->names([
            'index' => 'personnels.index',
            'create' => 'personnels.create',
            'store' => 'personnels.store',
            'show' => 'personnels.show',
            'edit' => 'personnels.edit',
            'update' => 'personnels.update',
            'destroy' => 'personnels.destroy'
        ])
        ->middleware([
            'index'   => 'permission:personnels.view',
            'create'  => 'permission:personnels.create',
            'store'   => 'permission:personnels.create',
            'show'    => 'permission:personnels.view',
            'edit'    => 'permission:personnels.edit',
            'update'  => 'permission:personnels.edit',
            'destroy' => 'permission:personnels.delete'
        ]);

    // Roles - Only for administrators
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)
        ->names([
            'index'   => 'roles.index',
            'create'  => 'roles.create',
            'store'   => 'roles.store',
            'show'    => 'roles.show',
            'edit'    => 'roles.edit',
            'update'  => 'roles.update',
            'destroy' => 'roles.destroy'
        ])
        ->middleware(['role:administrator', 
            'index'   => 'permission:roles.view',
            'create'  => 'permission:roles.create',
            'store'   => 'permission:roles.create',
            'show'    => 'permission:roles.view',
            'edit'    => 'permission:roles.edit',
            'update'  => 'permission:roles.edit',
            'destroy' => 'permission:roles.delete'
        ]);

    // Permissions - Only for administrators
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)
        ->names([
            'index'   => 'permissions.index',
            'create'  => 'permissions.create',
            'store'   => 'permissions.store',
            'show'    => 'permissions.show',
            'edit'    => 'permissions.edit',
            'update'  => 'permissions.update',
            'destroy' => 'permissions.destroy'
        ]);

});

// email
use App\Http\Controllers\FrontContactController;
Route::post('/contact/send', [FrontContactController::class, 'send'])->name('contact.send');


require __DIR__.'/auth.php';
