<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    // Karena di migration Anda hanya menggunakan 'created_at' dan 
    // 'updated_at' yang nullable, kita harus mengatur $timestamps.
    // Namun, jika Anda menggunakan format default $table->timestamps(), 
    // maka ini tidak perlu. Mengacu pada file Anda, kita akan atur manual.
    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'priority',
        'column',
        'bidang_id',
        'created_by',
        'completed_at',
        'pending_reason',
        'order',
    ];

    /**
     * Casting tipe data kolom.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'date',
        'completed_at' => 'datetime',
    ];

    /**
     * Dapatkan bidang yang memiliki task ini (Many-to-One).
     */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Dapatkan user yang membuat task ini (Many-to-One).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Dapatkan user yang ditugaskan pada task ini (Many-to-Many).
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_users')
            ->withPivot('assigned_at');
    }

    /**
     * Dapatkan subtasks untuk task ini (One-to-Many).
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('order');
    }

    /**
     * Dapatkan attachments untuk task ini (One-to-Many).
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    /**
     * Dapatkan komentar untuk task ini (One-to-Many).
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }
}
