<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tblevent extends Model
{
    use HasFactory;

    protected $table = 'tblevents';

    protected $guarded = [];

    public $timestamps = false;
}
