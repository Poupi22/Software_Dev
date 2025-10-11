<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'avatar',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'actif' => 'boolean',
    ];

    // ===== SUPER ADMIN =====
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    // Le Super Admin a TOUTES les permissions (bypass Spatie)
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super Admin bypass tout
        }
        
        return parent::hasPermissionTo($permission, $guardName);
    }

    // Accessor
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    // Relations
    public function devis()
    {
        return $this->hasMany(\App\Models\Devis::class, 'created_by');
    }

    public function factures()
    {
        return $this->hasMany(\App\Models\Facture::class, 'created_by');
    }

    public function clients()
    {
        return $this->hasMany(\App\Models\Client::class, 'created_by');
    }
}