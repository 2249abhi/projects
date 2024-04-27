<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    //protected $table = 'Modules';
    protected $fillable = [
        'name', 'description','pid','cid','controller','depth','action'
    ];
}