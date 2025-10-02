<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentAttachment extends Model
{
    use HasFactory;

    /**
     * Tabel yang terasosiasi dengan model.
     *
     * @var string
     */
    protected $table = 'comment_attachments';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'comment_id',
        'name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    /**
     * Dapatkan komentar yang memiliki attachment ini (Many-to-One).
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class, 'comment_id');
    }
}
