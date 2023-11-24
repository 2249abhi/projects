<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesPermissions extends Model
{
    use HasFactory;

    protected $guarded = [];

    //protected $table = 'Rolespermissions';

    public function Module()
    {
        return $this->belongsTo(Module::class,'mid');
    }

    public function Role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
}
