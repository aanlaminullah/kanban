<?php

namespace App\Models;

// Pastikan ini adalah model User yang benar, biasanya menggunakan Authenticatable
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal.
     * Menambahkan 'avatar', 'color', dan 'role' dari migrasi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'color',
        'role',
    ];

    /**
     * Kolom yang harus disembunyikan untuk serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- Relasi Baru ---

    /**
     * Dapatkan task yang ditugaskan kepada user ini (Many-to-Many).
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_users')
            ->withPivot('assigned_at');
    }

    /**
     * Dapatkan task yang dibuat oleh user ini (One-to-Many).
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Dapatkan komentar yang ditulis oleh user ini (One-to-Many).
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'author_id');
    }

    /**
     * Dapatkan attachments yang diunggah oleh user ini (One-to-Many).
     */
    public function uploadedAttachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class, 'uploaded_by');
    }
}
