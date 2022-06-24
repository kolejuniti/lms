<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    use HasFactory;

    protected $table = 'student_subjek';

    protected $guarded = [];

    public function subject()
    {
        $this->belongsTo(subject::class, 'group_id', 'id');
    }

}
