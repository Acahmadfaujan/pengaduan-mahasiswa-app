<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $title
 * @property string $description
 * @property string $location
 * @property string $image_url
 * @property string $status
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Category $category
 */
class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'location',
        'image_url', 
        'status'
    ];

    /**
     * Relasi balik ke tabel Users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi balik ke tabel Categories
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * FIX UTAMA: Menambahkan relasi ke tabel Comments agar fungsi dengan komentar tidak crash
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'complaint_id', 'id');
    }
}