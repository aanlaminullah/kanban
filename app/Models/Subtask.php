<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtask extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'text',
        'completed',
        'created_by',
        'order',
    ];

    /**
     * Casting tipe data kolom.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * Dapatkan task yang memiliki subtask ini (Many-to-One).
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Dapatkan user yang membuat subtask ini (Many-to-One).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
