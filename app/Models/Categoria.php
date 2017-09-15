<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use softDeletes;

    protected $table = "categoria";

    protected $dates = ['deleted_at']; 
}
