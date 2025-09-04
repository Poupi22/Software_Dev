<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'matricule',
        'name',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'nationalite',
        'tel1',
        'tel2',
        'email',
        'ville',
        'tuteur',
        'tel_tuteur',
        'cni',
        'demande',
        'photo',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date',
    ];

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function leconsTerminees() {
        return $this->belongsToMany(Lecon::class, 'progressions');
    }

    public function matieres() {
        return $this->hasMany(Matiere::class);
    }

    /**
     * Relation avec les notes de l'étudiant
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Relation avec l'assiduité de l'étudiant
     */
    public function assiduites(): HasMany
    {
        return $this->hasMany(Assiduite::class);
    }

    public function scopeWhereIsStaff(Builder $query): void
    {
        $query->whereDoesntHave('roles', function (Builder $query) {
            $query->whereIn('name', ['Etudiant', 'Formateur']);
        });
    }

    // --- NOUVELLES RELATIONS POUR LES NOTIFICATIONS ---

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function sentAdminNotifications(): HasMany
    {
        return $this->hasMany(AdminNotification::class, 'sender_id');
    }

    public function userAdminNotifications(): HasMany
    {
        return $this->hasMany(UserAdminNotification::class);
    }

    public function getAllNotifications()
    {
        $manualNotifications = $this->userAdminNotifications()
                                    ->with('adminNotification.sender')
                                    ->get()
                                    ->filter(function ($userNotif) {
                                        $notif = $userNotif->adminNotification;
                                        return (!$notif->send_at || $notif->send_at->lte(now())) &&
                                               (!$notif->expires_at || $notif->expires_at->gte(now()));
                                    })
                                    ->map(function ($userNotif) {
                                        return (object) [
                                            'source' => 'admin_manual',
                                            'id' => $userNotif->id,
                                            'original_id' => $userNotif->adminNotification->id,
                                            'type' => $userNotif->adminNotification->type,
                                            'title' => $userNotif->adminNotification->title,
                                            'message' => $userNotif->adminNotification->message,
                                            'sender_name' => $userNotif->adminNotification->sender->name ?? 'Administrateur',
                                            'read_at' => $userNotif->read_at,
                                            'created_at' => $userNotif->adminNotification->created_at,
                                            'link' => route('dashboard.admin-notifications.show', $userNotif->adminNotification->id),
                                            'icon' => 'fas fa-bullhorn',
                                        ];
                                    });

        $systemNotifications = $this->notifications()
                                    ->get()
                                    ->map(function ($notification) {
                                        return (object) [
                                            'source' => 'system_laravel',
                                            'id' => $notification->id,
                                            'original_id' => null,
                                            'type' => $notification->data['type'] ?? 'info',
                                            'title' => $notification->data['title'] ?? 'Notification Système',
                                            'message' => $notification->data['message'] ?? 'Message système',
                                            'sender_name' => 'Système',
                                            'read_at' => $notification->read_at,
                                            'created_at' => $notification->created_at,
                                            'link' => $notification->data['link'] ?? '#',
                                            'icon' => $notification->data['icon'] ?? 'fas fa-info-circle',
                                        ];
                                    });

        return $manualNotifications->merge($systemNotifications)
                                  ->sortByDesc('created_at')
                                  ->sortBy(fn($notification) => $notification->read_at ? 1 : 0);
    }

    public function markAdminNotificationAsRead(AdminNotification $adminNotification)
    {
        $userAdminNotification = $this->userAdminNotifications()
                                      ->where('admin_notification_id', $adminNotification->id)
                                      ->first();

        if ($userAdminNotification && is_null($userAdminNotification->read_at)) {
            $userAdminNotification->update(['read_at' => now()]);
            return true;
        }
        return false;
    }

    public function markSystemNotificationAsRead(string $notificationId)
    {
        $notification = $this->notifications()->find($notificationId);
        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }
}
