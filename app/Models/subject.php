<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subject extends Model
{
    use HasFactory;

    protected $table = 'user_subjek';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ic', 'ic');
    }

    public function students()
    {
        return $this->hasMany(student::class, 'group_id', 'id');
    }
}




