<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'status'
    ];

    // 🔗 Relasi ke User (pembuat pengaduan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🔗 Relasi ke Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 🔗 Relasi ke Attachments
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}