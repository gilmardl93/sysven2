<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use softDeletes;

    protected $table = "clientes";

    protected $dates = ['deleted_at']; 
}
