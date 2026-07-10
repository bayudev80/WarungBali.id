<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';

    protected $primaryKey = 'id_review';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_warung',
        'rating',
        'komentar',
        'created_at'
    ];

    public function warung()
    {
        return $this->belongsTo(Warung::class, 'id_warung', 'id_warung');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}