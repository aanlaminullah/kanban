<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
    ];

    /**
     * Dapatkan task untuk Bidang ini (One-to-Many).
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
