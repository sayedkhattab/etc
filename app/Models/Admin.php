<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'avatar',
        'role_id',
        'status',
        'permissions',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'permissions' => 'json',
    ];

    /**
     * Get the role that the admin belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the admin has a specific role.
     */
    public function hasRole($roles): bool
    {
        $role = $this->role; // string attribute or Role model instance
        $roleName = is_object($role) ? $role->name : $role;

        if (is_array($roles)) {
            return in_array($roleName, $roles);
        }

        return $roleName === $roles;
    }

    /**
     * Accessor to always return the role name as string regardless of storage strategy.
     */
    public function getRoleNameAttribute(): ?string
    {
        $role = $this->role;
        return is_object($role) ? $role->name : $role;
    }

    /**
     * Check if the admin has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->permissions) {
            return false;
        }
        
        return in_array($permission, $this->permissions);
    }

    /**
     * Check if the admin is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
