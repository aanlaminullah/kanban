<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskComment extends Model
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
        'author_id',
    ];

    /**
     * Dapatkan task yang memiliki komentar ini (Many-to-One).
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Dapatkan penulis komentar (Many-to-One).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Dapatkan attachments untuk komentar ini (One-to-Many).
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(CommentAttachment::class, 'comment_id');
    }
}
