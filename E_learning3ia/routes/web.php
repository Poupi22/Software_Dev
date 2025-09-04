<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

//Front
use App\Http\Controllers\FrontHomeController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FrontActualiteController;
use App\Http\Controllers\FrontEvenementController;
use App\Http\Controllers\FrontContactController;
use App\Http\Controllers\FrontAboutController;
use App\Http\Controllers\FrontFormationController;
use App\Http\Controllers\TemoignageController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\FormateurController;
use App\Http\Controllers\ProgrammeSessionController;
use App\Http\Controllers\ChapitreController;
use App\Http\Controllers\LeconController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ContenuAdditionnelController;
use App\Http\Controllers\RessourceAdditionnelleController;
use App\Http\Controllers\FichePreinscriptionController;
use App\Http\Controllers\PublicStudentController;
use App\Http\Controllers\BulletinController;

//Admin
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ForumController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\DashboardController;

//Elearning
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Elearning\ThreadController;
use App\Http\Controllers\Elearning\EmailController;
use App\Http\Controllers\Elearning\CourseController;
use App\Http\Controllers\Elearning\AdminDashboardController;
use App\Http\Controllers\Elearning\StudentDashboardController;
use App\Http\Controllers\PrivateChatController;
use App\Http\Controllers\UserController;


// Public routes
Route::get('/', [FrontHomeController::class, 'index'])->name('acceuil.index');
Route::get('evenements', [FrontEvenementController::class, 'index'])->name('evenements');
Route::get('actualite', [FrontActualiteController::class, 'index'])->name('acceuil.actualite');
Route::get('contact', [FrontContactController::class, 'index'])->name('acceuil.contact');
Route::get('about', [FrontAboutController::class, 'index'])->name('acceuil.about');
Route::get('formation', [FrontFormationController::class, 'index'])->name('acceuil.formation');
Route::get('/formations/{slug}', [FrontFormationController::class, 'show'])->name('formation.details');
Route::get('/evenement/{id}', [FrontEvenementController::class, 'show'])->name('evenement.show');
Route::post('/contact/send', [FrontContactController::class, 'send'])->name('contact.send')->middleware('throttle:5,1');
// Routes publiques pour les étudiants
Route::get('etudiants', [PublicStudentController::class, 'showAllStudents'])->name('acceuil.students');
Route::get('etudiant/{id}/infoperso', [PublicStudentController::class, 'showStudentMarks'])->name('acceuil.student.marks');

require __DIR__.'/auth.php';

Route::middleware(['auth','verified'])->group(function () {

    // Dashboard selection for administrators
   // Route pour le dashboard administrateur
    Route::get('/dashboard/selection', function () {
        return view('admin_site.dashboard-selection');
    })->middleware('role:Administrateur')->name('dashboard.selection');

    // Profile User

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', 'edit')->name('edit')->middleware('can:update profiles');
        Route::put('/', 'update')->name('update')->middleware('can:update profiles');
        Route::delete('/', 'destroy')->name('destroy')->middleware('can:delete profiles');
        Route::get('/student_edit', 'edits')->name('student_edit')->middleware('can:update profiles');
    });

    // Dashboard Administrateur
    Route::middleware(['auth', 'verified', 'role:Administrateur'])->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');



        Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read users');
            Route::get('/create', 'create')->name('create')->middleware('can:create users');
            Route::post('/', 'store')->name('store')->middleware('can:create users');
            Route::get('/{user}', 'show')->name('show')->middleware('can:read users');
            Route::get('/{user}/edit', 'edit')->name('edit')->middleware('can:update users');
            Route::put('/{user}', 'update')->name('update')->middleware('can:update users');
            Route::delete('/{user}', 'destroy')->name('destroy')->middleware('can:delete users');
        });

        Route::controller(ActualiteController::class)->prefix('actualite')->name('actualite.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read actualites');
            Route::get('/create', 'create')->name('create')->middleware('can:create actualites');
            Route::post('/', 'store')->name('store')->middleware('can:create actualites');
            Route::get('/{actualite}', 'show')->name('show')->middleware('can:read actualites');
            Route::get('/{actualite}/edit', 'edit')->name('edit')->middleware('can:update actualites');
            Route::put('/{actualite}', 'update')->name('update')->middleware('can:update actualites');
            Route::delete('/{actualite}', 'destroy')->name('destroy')->middleware('can:delete actualites');
        });

        Route::controller(EvenementController::class)->prefix('evenement')->name('evenement.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read evenements');
            Route::get('/create', 'create')->name('create')->middleware('can:create evenements');
            Route::post('/', 'store')->name('store')->middleware('can:create evenements');
            Route::get('/{evenement}', 'show')->name('show')->middleware('can:read evenements');
            Route::get('/{evenement}/edit', 'edit')->name('edit')->middleware('can:update evenements');
            Route::put('/{evenement}', 'update')->name('update')->middleware('can:update evenements');
            Route::delete('/{evenement}', 'destroy')->name('destroy')->middleware('can:delete evenements');
        });

        Route::controller(AccueilController::class)->prefix('accueil')->name('accueil.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read accueils');
            Route::get('/create', 'create')->name('create')->middleware('can:create accueils');
            Route::post('/', 'store')->name('store')->middleware('can:create accueils');
            Route::get('/{accueil}', 'show')->name('show')->middleware('can:read accueils');
            Route::get('/{accueil}/edit', 'edit')->name('edit')->middleware('can:update accueils');
            Route::put('/{accueil}', 'update')->name('update')->middleware('can:update accueils');
            Route::delete('/{accueil}', 'destroy')->name('destroy')->middleware('can:delete accueils');
        });

        Route::controller(AboutController::class)->prefix('about')->name('about.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read abouts');
            Route::get('/create', 'create')->name('create')->middleware('can:create abouts');
            Route::post('/', 'store')->name('store')->middleware('can:create abouts');
            Route::get('/{about}', 'show')->name('show')->middleware('can:read abouts');
            Route::get('/{about}/edit', 'edit')->name('edit')->middleware('can:update abouts');
            Route::put('/{about}', 'update')->name('update')->middleware('can:update abouts');
            Route::delete('/{about}', 'destroy')->name('destroy')->middleware('can:delete abouts');
        });

        Route::controller(TemoignageController::class)->prefix('temoignage')->name('temoignage.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read temoignages');
            Route::get('/create', 'create')->name('create')->middleware('can:create temoignages');
            Route::post('/', 'store')->name('store')->middleware('can:create temoignages');
            Route::get('/{temoignage}', 'show')->name('show')->middleware('can:read temoignages');
            Route::get('/{temoignage}/edit', 'edit')->name('edit')->middleware('can:update temoignages');
            Route::put('/{temoignage}', 'update')->name('update')->middleware('can:update temoignages');
            Route::delete('/{temoignage}', 'destroy')->name('destroy')->middleware('can:delete temoignages');
        });

        Route::controller(ContactController::class)->prefix('contact')->name('contact.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read contacts');
            Route::get('/create', 'create')->name('create')->middleware('can:create contacts');
            Route::post('/', 'store')->name('store')->middleware('can:create contacts');
            Route::get('/{contact}', 'show')->name('show')->middleware('can:read contacts');
            Route::get('/{contact}/edit', 'edit')->name('edit')->middleware('can:update contacts');
            Route::put('/{contact}', 'update')->name('update')->middleware('can:update contacts');
            Route::delete('/{contact}', 'destroy')->name('destroy')->middleware('can:delete contacts');
        });


        Route::controller(FichePreinscriptionController::class)->prefix('fiche_preinscription')->name('fiche_preinscription.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{fiche_preinscription}', 'show')->name('show');
            Route::get('/{fiche_preinscription}/edit', 'edit')->name('edit');
            Route::put('/{fiche_preinscription}', 'update')->name('update');
            Route::delete('/{fiche_preinscription}', 'destroy')->name('destroy');
        });

        Route::controller(FormationController::class)->prefix('formation')->name('formation.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read formations');
            Route::get('/create', 'create')->name('create')->middleware('can:create formations');
            Route::post('/', 'store')->name('store')->middleware('can:create formations');
            Route::get('/{formation}', 'show')->name('show')->middleware('can:read formations');
            Route::get('/{formation}/edit', 'edit')->name('edit')->middleware('can:update formations');
            Route::put('/{formation}', 'update')->name('update')->middleware('can:update formations');
            Route::delete('/{formation}', 'destroy')->name('destroy')->middleware('can:delete formations');
            Route::post('/formation/temp/add', 'addTemp')->name('temp.add')->middleware('can:update formations');
            Route::get('/formation/temp/get', 'getTempItems')->name('temp.get')->middleware('can:read formations');
            Route::delete('/formation/temp/remove/{id}', 'removeTemp')->name('temp.remove')->middleware('can:delete formations');
        });

        Route::controller(InscriptionController::class)->prefix('inscription')->name('inscription.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read inscriptions');
            Route::get('/create', 'create')->name('create')->middleware('can:create inscriptions');
            Route::post('/', 'store')->name('store')->middleware('can:create inscriptions');
            Route::get('/{inscription}', 'show')->name('show')->middleware('can:read inscriptions');
            Route::get('/{inscription}/edit', 'edit')->name('edit')->middleware('can:update inscriptions');
            Route::put('/{inscription}', 'update')->name('update')->middleware('can:update inscriptions');
            Route::delete('/{inscription}', 'destroy')->name('destroy')->middleware('can:delete inscriptions');
            Route::post('/{inscription}/add-paiement', 'addPaiement')->name('add_paiement')->middleware('can:update inscriptions');
            Route::get('/{inscription}/situation-financiere', 'genererSituationFinanciere')->name('situation_financiere')->middleware('can:read inscriptions');
        });

        Route::controller(FormateurController::class)->prefix('formateur')->name('formateur.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read users');
            Route::get('/create', 'create')->name('create')->middleware('can:create users');
            Route::post('/', 'store')->name('store')->middleware('can:create users');
            Route::get('/{formateur}', 'show')->name('show')->middleware('can:read users');
            Route::get('/{formateur}/edit', 'edit')->name('edit')->middleware('can:update users');
            Route::put('/{formateur}', 'update')->name('update')->middleware('can:update users');
            Route::delete('/{formateur}', 'destroy')->name('destroy')->middleware('can:delete users');
        });

        Route::controller(EtudiantController::class)->prefix('etud')->name('etud.')->group(function () {
            Route::get('/search', 'search')->name('search')->middleware('can:read users');
            Route::get('/', 'index')->name('index')->middleware('can:read users');
            Route::get('/{etud}', 'show')->name('show')->middleware('can:read users');
            Route::get('/{etud}/edit', 'edit')->name('edit')->middleware('can:update users');
            Route::put('/{etud}', 'update')->name('update')->middleware('can:update users');

        });

        Route::controller(AnneeAcademiqueController::class)->prefix('annee_academique')->name('annee_academique.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read annee_academiques');
            Route::get('/create', 'create')->name('create')->middleware('can:create annee_academiques');
            Route::post('/', 'store')->name('store')->middleware('can:create annee_academiques');
            Route::get('/{annee_academique}', 'show')->name('show')->middleware('can:read annee_academiques');
            Route::get('/{annee_academique}/edit', 'edit')->name('edit')->middleware('can:update annee_academiques');
            Route::put('/{annee_academique}', 'update')->name('update')->middleware('can:update annee_academiques');
            Route::delete('/{annee_academique}', 'destroy')->name('destroy')->middleware('can:delete annee_academiques');
        });

        Route::controller(QualificationController::class)->prefix('qualification')->name('qualification.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read qualifications');
            Route::get('/create', 'create')->name('create')->middleware('can:create qualifications');
            Route::post('/', 'store')->name('store')->middleware('can:create qualifications');
            //Route::get('/{qualification}', 'show')->name('show')->middleware('can:read qualifications');
            Route::get('/{qualification}/edit', 'edit')->name('edit')->middleware('can:update qualifications');
            Route::put('/{qualification}', 'update')->name('update')->middleware('can:update qualifications');
            Route::delete('/{qualification}', 'destroy')->name('destroy')->middleware('can:delete qualifications');
        });

        Route::controller(MatiereController::class)->prefix('matiere')->name('matiere.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read matieres');
            Route::get('/create', 'create')->name('create')->middleware('can:create matieres');
            Route::post('/', 'store')->name('store')->middleware('can:create matieres');
            Route::get('/{matiere}', 'show')->name('show')->middleware('can:read matieres');
            Route::get('/{matiere}/edit', 'edit')->name('edit')->middleware('can:update matieres');
            Route::put('/{matiere}', 'update')->name('update')->middleware('can:update matieres');
            Route::delete('/{matiere}', 'destroy')->name('destroy')->middleware('can:delete matieres');
        });

        Route::controller(RessourceAdditionnelleController::class)->prefix('ressource_additionnelle')->name('ressource_additionnelle.')->group(function () {
           // Route::get('/', 'index')->name('index')->middleware('can:read ressources_additionnelles');
           // Route::get('/create', 'create')->name('create')->middleware('can:create ressources_additionnelles');
            Route::post('/', 'store')->name('store')->middleware('can:create ressources_additionnelles');
           // Route::get('/{ressource-additionnelle}', 'show')->name('show')->middleware('can:read ressources_additionnelles');
           // Route::get('/{ressource-additionnelle}/edit', 'edit')->name('edit')->middleware('can:update ressources_additionnelles');
           // Route::put('/{ressource-additionnelle}', 'update')->name('update')->middleware('can:update ressources_additionnelles');
            Route::delete('/{ressource_additionnelle}', 'destroy')->name('destroy')->middleware('can:delete ressources_additionnelles');
        });

        Route::controller(ContenuAdditionnelController::class)->prefix('contenu_additionnel')->name('contenu_additionnel.')->group(function () {
           // Route::get('/', 'index')->name('index')->middleware('can:read contenus_additionnels');
           // Route::get('/create', 'create')->name('create')->middleware('can:create contenus_additionnels');
            Route::post('/', 'store')->name('store')->middleware('can:create contenus_additionnels');
            Route::get('/{contenu_additionnel}', 'show')->name('show')->middleware('can:read contenus_additionnels');
            Route::get('/{contenu_additionnel}/edit', 'edit')->name('edit')->middleware('can:update contenus_additionnels');
            Route::put('/{contenu_additionnel}', 'update')->name('update')->middleware('can:update contenus_additionnels');
            Route::delete('/{contenu_additionnel}', 'destroy')->name('destroy')->middleware('can:delete contenus_additionnels');
            Route::post('/{contenuAdditionnel}/toggle-visibility', 'toggleVisibility')->name('toggle_visibility')->middleware('can:read contenus_additionnels');
        });

        Route::controller(ChapitreController::class)->prefix('chapitre')->name('chapitre.')->group(function () {
           // Route::get('/', 'index')->name('index')->middleware('can:read chapitres');
            Route::get('/create', 'create')->name('create')->middleware('can:create chapitres');
            Route::post('/', 'store')->name('store')->middleware('can:create chapitres');
           // Route::get('/{chapitre}', 'show')->name('show')->middleware('can:read chapitres');
            Route::get('/{chapitre}/edit', 'edit')->name('edit')->middleware('can:update chapitres');
            Route::put('/{chapitre}', 'update')->name('update')->middleware('can:update chapitres');
            Route::delete('/{chapitre}', 'destroy')->name('destroy')->middleware('can:delete chapitres');
        });

        Route::controller(LeconController::class)->prefix('lecon')->name('lecon.')->group(function () {
           // Route::get('/', 'index')->name('index')->middleware('can:read lecons');
            Route::get('/create', 'create')->name('create')->middleware('can:create lecons');
            Route::post('/', 'store')->name('store')->middleware('can:create lecons');
           // Route::get('/{lecon}', 'show')->name('show')->middleware('can:read lecons');
            Route::get('/{lecon}/edit', 'edit')->name('edit')->middleware('can:update lecons');
            Route::put('/{lecon}', 'update')->name('update')->middleware('can:update lecons');
            Route::delete('/{lecon}', 'destroy')->name('destroy')->middleware('can:delete lecons');
        });

        Route::controller(QuizController::class)->prefix('quiz')->name('quiz.')->group(function () {
           // Route::get('/', 'index')->name('index')->middleware('can:read quizzes');
            Route::get('/create', 'create')->name('create')->middleware('can:create quizzes');
            Route::post('/', 'store')->name('store')->middleware('can:create quizzes');
            Route::get('/{quiz}', 'show')->name('show')->middleware('can:read quizzes');
            Route::get('/{quiz}/edit', 'edit')->name('edit')->middleware('can:update quizzes');
            Route::put('/{quiz}', 'update')->name('update')->middleware('can:update quizzes');
            Route::delete('/{quiz}', 'destroy')->name('destroy')->middleware('can:delete quizzes');
        });

        Route::controller(RessourceController::class)->prefix('ressource')->name('ressource.')->group(function () {
           // Route::get('/', 'index')->name('index')->middleware('can:read ressources');
            Route::get('/create', 'create')->name('create')->middleware('can:create ressources');
            Route::post('/', 'store')->name('store')->middleware('can:create ressources');
           // Route::get('/{ressource}', 'show')->name('show')->middleware('can:read ressources');
            Route::get('/{ressource}/edit', 'edit')->name('edit')->middleware('can:update ressources');
            Route::put('/{ressource}', 'update')->name('update')->middleware('can:update ressources');
            Route::delete('/{ressource}', 'destroy')->name('destroy')->middleware('can:delete ressources');
        });

        Route::controller(ProgrammeController::class)->prefix('programme')->name('programme.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read programmes');
            Route::get('/create', 'create')->name('create')->middleware('can:create programmes');
            Route::post('/', 'store')->name('store')->middleware('can:create programmes');
            Route::get('/{programme}', 'show')->name('show')->middleware('can:read programmes');
            Route::get('/{programme}/edit', 'edit')->name('edit')->middleware('can:update programmes');
            Route::put('/{programme}', 'update')->name('update')->middleware('can:update programmes');
            Route::delete('/{programme}', 'destroy')->name('destroy')->middleware('can:delete programmes');
        });

        Route::controller(ProgrammeSessionController::class)->prefix('programme_session')->name('programme_session.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read programme_sessions');
            Route::get('/create', 'create')->name('create')->middleware('can:create programme_sessions');
            Route::post('/', 'store')->name('store')->middleware('can:create programme_sessions');
            Route::get('/{programme_session}', 'show')->name('show')->middleware('can:read programme_sessions');
            Route::get('/{programme_session}/edit', 'edit')->name('edit')->middleware('can:update programme_sessions');
            Route::put('/{programme_session}', 'update')->name('update')->middleware('can:update programme_sessions');
            Route::delete('/{programme_session}', 'destroy')->name('destroy')->middleware('can:delete programme_sessions');
            Route::post('/{programmeSession}/change-status', 'changeStatus')->name('change_status')->middleware('can:update programme_sessions');
            Route::post('/cours-instance/{coursInstance}/assigner-formateur', 'assignerFormateur')->name('cours_instance.assigner_formateur')->middleware('can:update programme-sessions');
           // Route::post('/cours-instance/{coursInstance}/assigner-formateur', [ProgrammeSessionController::class, 'assignerFormateur'])->name('dashboard.cours_instance.assigner_formateur')->middleware('can:update programme-sessions');
        });

        Route::get('/analytics-data', [DashboardController::class, 'getAnalyticsData'])->name('analytics_data');

        // Routes pour la gestion des bulletins
        Route::controller(BulletinController::class)->prefix('bulletin')->name('bulletin.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read notes');
            Route::get('/saisie-notes', 'saisieNotes')->name('saisie-notes')->middleware('can:create notes');
            Route::post('/notes', 'storeNotes')->name('store-notes')->middleware('can:create notes');
            Route::get('/saisie-notes/{etudiant}', 'saisieNotesEtudiant')->name('saisie-notes-etudiant')->middleware('can:create notes');
            Route::post('/notes/{etudiant}', 'storeNotesEtudiant')->name('store-notes-etudiant')->middleware('can:create notes');
            Route::get('/saisie-assiduite', 'saisieAssiduite')->name('saisie-assiduite')->middleware('can:create notes');
            Route::post('/assiduite', 'storeAssiduite')->name('store-assiduite')->middleware('can:create notes');
            Route::get('/liste-etudiants', 'listeEtudiants')->name('liste-etudiants')->middleware('can:read notes');
            Route::get('/preview', 'preview')->name('preview')->middleware('can:read notes');
            Route::get('/download-pdf', 'downloadPdf')->name('download-pdf')->middleware('can:read notes');
        });
    });

    Route::middleware(['auth', 'verified', 'role:Administrateur'])->prefix('dashboard1')->name('dashboard1.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');

            Route::controller(PermissionController::class)->prefix('permission')->name('permission.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{permission}', 'show')->name('show');
                Route::get('/{permission}/edit', 'edit')->name('edit');
                Route::put('/{permission}', 'update')->name('update');
                Route::delete('/{permission}', 'destroy')->name('destroy');
            });

            Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{role}', 'show')->name('show');
                Route::get('/{role}/edit', 'edit')->name('edit');
                Route::put('/{role}', 'update')->name('update');
                Route::delete('/{role}', 'destroy')->name('destroy');
            });

        Route::controller(ForumController::class)->prefix('forum')->name('forum.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read forums');
            Route::get('/create', 'create')->name('create')->middleware('can:create forums');
            Route::post('/', 'store')->name('store')->middleware('can:create forums');
            Route::get('/{forum}', 'show')->name('show')->middleware('can:read forums');
            Route::get('/{forum}/edit', 'edit')->name('edit')->middleware('can:update forums');
            Route::put('/{forum}', 'update')->name('update')->middleware('can:update forums');
            Route::delete('/{forum}', 'destroy')->name('destroy')->middleware('can:delete forums');
        });

    });

    // Route pour gerer le coter etudiant du site E-Learning
    Route::prefix('etudiant')->name('etudiant.')->group(function () {

        Route::controller(App\Http\Controllers\Elearning\QuizController::class)->prefix('quiz')->name('quiz.')->group(function () {
            Route::get('/{quiz}', 'show')->name('show')->middleware('can:read quizzes');
            Route::post('/{quiz}/save-answer', 'saveAnswer')->name('save-answer')->middleware(['can:read quizzes', 'throttle:60,1']);
            Route::get('/{quiz}/check-time/{attempt}', 'checkTime')->name('check-time')->middleware('can:read quizzes');
            Route::post('/{quiz}/submit', 'submit')->name('submit')->middleware(['can:update quizzes', 'throttle:3,1']);
            Route::post('/{quiz}/auto-submit/{attempt}', 'autoSubmit')->name('auto-submit')->middleware(['can:update quizzes', 'throttle:3,1']);
            Route::get('/{quiz}/retry', 'retry')->name('retry')->middleware(['can:update quizzes', 'throttle:5,1']);
            Route::get('/{quiz}/results/{attempt}', 'results')->name('results')->middleware('can:read quizzes');
            Route::get('/quiz/{quiz}/practice-results', 'practiceResults')->name('practice_results')->middleware('can:read quizzes');
        });
        
        Route::controller(App\Http\Controllers\Elearning\RessourceController::class)->prefix('pdf')->name('pdf.')->group(function () {
            Route::get('/pdf/{id}', 'show')->name('show');
            Route::get('/etudiant/pdf/{ressource}', 'preview')->name('preview');
        });

        Route::controller(CourseController::class)->prefix('course')->name('course.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:read matieres');
            Route::get('/{coursInstance}', 'show')->name('show')->middleware('can:read matieres');
        });

        Route::controller(ChatController::class)->prefix('chat')->name('chat.')->group(function () {
            Route::get('chat', 'index')->name('index')->middleware('can:read forums');
            Route::get('chat/messages/{type}/{id}', 'getMessages')->name('messages')->middleware('can:read forums');
            Route::post('chat/send', 'sendMessage')->name('send')->middleware('can:read posts');
            Route::post('chat/threads', 'createThread')->name('threads.create')->middleware('can:read threads');
        });

        Route::middleware(['role:Administrateur'])->group(function (){
        Route::controller(AdminDashboardController::class)->group(function () {
            Route::get('profile', 'profile')->name('profile');
            Route::get('profile', 'profile')->name('profile');
            Route::get('quiz-average-scores', 'getAverageScores')->name('quiz-average-scores');
            Route::get('students/{student}/details', 'getStudentDetails');
            Route::get('students/{student}/quizzes/{quiz}/details', 'getQuizDetails');
            Route::post('students/{student}/quizzes/{quiz}/reset', 'resetQuizAttempts');
            Route::post('students/{student}/unblock', 'unblockStudent');

        });
        });

        Route::controller(ThreadController::class)->group(function () {
            Route::post('/threads', 'store')->name('threads.store')->middleware('can:create threads');
            Route::get('/threads/{thread}', 'show')->name('threads.show')->whereNumber('thread')->middleware('can:read threads');
            Route::post('/threads/{thread}/posts', 'storePost')->name('posts.store')->whereNumber('thread')->middleware('can:create threads');
            Route::get('/etudiant/message/{forum}', 'show')->name('message')->middleware('can:read messages');
        });


        Route::get('index', [App\Http\Controllers\Elearning\StudentDashboardController::class, 'index'])->name('index');
        Route::get('lecon/{lecon}', [\App\Http\Controllers\Elearning\LeconController::class, 'show'])->name('lecon.show');
        Route::post('/etudiant/contact/send', [EmailController::class, 'sendContactForm'])->name('email.send');
        Route::get('email', function () {
            return view('etudiant.email');
        })->name('email');
        Route::get('notifications', function () {
            return view('etudiant.notifications');
        })->name('notifications');

        // Routes bulletins pour étudiants
        Route::controller(\App\Http\Controllers\Etudiant\BulletinController::class)->prefix('bulletin')->name('bulletin.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show', 'show')->name('show');
            Route::get('/final', 'showFinal')->name('final');
            Route::get('/download-pdf', 'downloadPdf')->name('download-pdf');
        });

    });
    Route::prefix('Elearning')->group(function() {
    Route::delete('/posts/{post}', [\App\Http\Controllers\Elearning\PostController::class, 'destroy'])
        ->name('Elearning.posts.destroy');
    });
    Route::controller(PrivateChatController::class)->group(function () {
        Route::get('/chat/{user}','openConversation')->name('chat.with')->middleware('can:read messages');
        Route::get('/conversation/{conversation}', 'show')->name('chat.show')->middleware('can:read messages');
        Route::post('/chat/{conversation}/send', 'sendMessage')->name('chat.sendMessage')->middleware(['can:create messages', 'throttle:30,1']);
        Route::delete('/messages/{message}', 'deleteMessage')->name('messages.destroy')->middleware(['can:delete messages', 'throttle:20,1']);
        Route::put('/messages/{message}', 'updateMessage')->name('messages.update')->middleware(['can:update messages', 'throttle:20,1']);
    });
});
